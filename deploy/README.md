# Deploy da OpenFinance Platform

Os arquivos deste diretório cobrem três níveis distintos: desenvolvimento local, staging autocontido e produção com serviços de dados externos/HA. Nenhum segredo real deve ser versionado.

## Desenvolvimento local

Pré-requisitos: Docker Engine/Desktop, Docker Compose v2, `curl` e `openssl`.

```bash
./deploy/bin/dev-up.sh
```

O comando é idempotente e preserva PostgreSQL, Redis e MinIO entre execuções. Para acompanhar a aplicação:

```bash
docker compose ps
docker compose logs -f app web horizon scheduler
./deploy/bin/dev-down.sh
```

Portas podem ser alteradas sem editar YAML:

```bash
HTTP_PORT=8180 MAILPIT_PORT=8125 MINIO_CONSOLE_PORT=9101 ./deploy/bin/dev-up.sh
```

Use `WITH_MONITORING=1` para incluir Prometheus em `:9090` e Grafana em `:3000`.

## Staging autocontido

O staging usa as imagens publicadas pelo CI e inclui banco, Redis e MinIO no mesmo host. Ele não representa a topologia de alta disponibilidade de produção.

```bash
cp deploy/env/staging.env.example deploy/env/staging.env
chmod 600 deploy/env/staging.env
# preencha os valores REPLACE_WITH_*
./deploy/bin/staging-up.sh
```

## Imagens

Após os testes da branch `main`, o GitHub Actions publica dois artefatos no GHCR:

- `ghcr.io/<owner>/<repo>/app:sha-<commit>`;
- `ghcr.io/<owner>/<repo>/web:sha-<commit>`.

As tags por SHA são o identificador de release. `latest` existe por conveniência, mas não deve ser usado em produção.

## Produção

`docker-compose.production.yml` contém somente processos stateless. PostgreSQL/PgBouncer, Redis/Sentinel e MinIO são endpoints externos definidos no arquivo de ambiente.

Prepare os segredos e execute a migração compatível antes de substituir os processos:

```bash
cp deploy/env/production.env.example deploy/env/production.env
chmod 600 deploy/env/production.env
# defina IMAGE_TAG=sha-<commit> e os segredos reais
./deploy/bin/release.sh deploy/env/production.env
```

Distribua os papéis separadamente. Em produção, execute `app` em pelo menos dois nós, `workers` conforme a carga e apenas uma instância de `scheduler`:

```bash
./deploy/bin/deploy.sh app deploy/env/production.env
./deploy/bin/deploy.sh workers deploy/env/production.env
./deploy/bin/deploy.sh scheduler deploy/env/production.env
```

Cada papel guarda localmente as tags atual/anterior. O rollback troca apenas a imagem; migrações financeiras nunca são revertidas automaticamente:

```bash
./deploy/bin/rollback.sh app deploy/env/production.env
# ou escolha uma tag explicitamente:
./deploy/bin/rollback.sh workers deploy/env/production.env sha-0123456789abcdef
```

As migrações precisam seguir a estratégia expand/contract: uma release adiciona estruturas compatíveis e somente uma release posterior remove estruturas antigas.

## Coolify

Crie recursos Docker Compose separados usando o mesmo `docker-compose.production.yml`:

1. `openfinance-app-a` e `openfinance-app-b`, com profile `app`, um em cada servidor de aplicação;
2. `openfinance-workers`, com profile `workers` e escala horizontal conforme o lag;
3. `openfinance-scheduler`, com profile `scheduler` e exatamente uma réplica.

Configure as mesmas variáveis do `production.env.example`, fixe `IMAGE_TAG` em uma tag `sha-*` e conecte os hosts pela rede WireGuard. Os endpoints de banco, Redis e MinIO devem ser endereços internos. O endpoint `/up` é o healthcheck de processo; `/api/v1/health` verifica as dependências.

O Coolify pode executar o serviço `release` como comando pré-deploy. Não execute migrações simultaneamente em cada réplica; `--isolated` evita concorrência acidental, mas o release deve continuar sendo uma etapa única e observável.

## Edge HAProxy

Em cada VPS de borda, prepare um PEM contendo certificado e chave privada (Cloudflare Origin Certificate ou certificado público), copie o exemplo e suba:

```bash
cp deploy/env/edge.env.example deploy/env/edge.env
docker compose --env-file deploy/env/edge.env -f deploy/docker-compose.edge.yml up -d
```

As variáveis `APP_NODE_1` e `APP_NODE_2` apontam para os dois nós Coolify via WireGuard. A porta de estatísticas `8404` fica vinculada a `127.0.0.1` por padrão.

## Observabilidade

Crie, fora do repositório, o arquivo de targets a partir de `infra/prometheus/targets/openfinance.example.json`, um arquivo contendo somente o token de métricas e a configuração real do Alertmanager. Em seguida:

```bash
cp deploy/env/observability.env.example deploy/env/observability.env
docker compose --env-file deploy/env/observability.env -f deploy/docker-compose.observability.yml up -d
```

Prometheus, Alertmanager e Grafana escutam apenas em `127.0.0.1` por padrão. Publique-os somente atrás de autenticação/VPN.

## Ordem segura de release

1. CI aprova testes e publica as imagens com tag por SHA.
2. Execute `release.sh` uma vez.
3. Atualize workers, depois as réplicas app uma por vez.
4. Verifique saúde, lag das filas e erros antes de prosseguir.
5. Atualize o scheduler por último.

