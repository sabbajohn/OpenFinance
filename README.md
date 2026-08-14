# OpenFinance Platform

Hub bancário SaaS multiempresa para integrar bancos e ERPs com processamento durável, conciliação auditável, Pix e boletos.

## Estrutura

- `platform/`: Laravel 13, Inertia 3, Vue 3/TypeScript, Tailwind 4 e shadcn-vue.
- `packages/financial-core`: contratos e DTOs financeiros independentes.
- `packages/bank-sicredi`: adapter Sicredi OAuth2/mTLS por produto.
- `packages/php-sdk`: cliente oficial para ERPs e verificador HMAC.
- `docs/openapi/platform-v1.yaml`: contrato público versionado.
- `infra/`: exemplos de edge HAProxy e PgBouncer.

O `unified-sdk` legado permanece isolado e não é dependência da plataforma.

## Ambiente local

Com Docker e Docker Compose v2 instalados, o ambiente de desenvolvimento completo sobe com um comando:

```bash
./deploy/bin/dev-up.sh
```

O script cria `platform/.env`, gera uma `APP_KEY`, constrói as imagens, inicia os serviços, executa as migrações/seeds e valida a API. Os dados ficam em volumes Docker e são preservados ao desligar:

```bash
./deploy/bin/dev-down.sh
```

Serviços locais:

- painel: `http://localhost:8080`;
- Mailpit: `http://localhost:8025`;
- MinIO: `http://localhost:9001`;
- login: `admin@openfinance.local` / `password`.

O seed também cria duas contas Sicredi simuladas, com saldos, para exercitar dashboard e listagem de contas sem credenciais bancárias. A conexão permanece como `draft` para não disparar polling real. O login seed é exclusivo para desenvolvimento. No primeiro acesso, o painel exige ativação do 2FA antes de liberar operações.

Para desenvolvimento sem Docker, entre em `platform/`, execute `composer install`, `npm install`, configure `.env`, migre e use `composer dev`.

O perfil opcional de observabilidade sobe Prometheus, Alertmanager e Grafana:

```bash
WITH_MONITORING=1 ./deploy/bin/dev-up.sh
```

## Processos de produção

As imagens imutáveis e os stacks de staging, aplicação, workers, scheduler, edge HAProxy e observabilidade ficam em [`deploy/`](deploy/README.md). Suba ao menos duas réplicas web, um scheduler e supervisores Horizon especializados nas filas `webhooks-critical`, `bank-sync`, `normalization`, `reconciliation`, `erp-delivery` e `maintenance`.

O primeiro token ERP é emitido sem persistir o texto puro:

```bash
php artisan openfinance:issue-api-client ORGANIZATION_UUID "SimplesLaravel" --company=COMPANY_UUID --scopes=erp:write --scopes=banking:read --scopes=reconciliation:write --scopes=receivables:write
```

Consulte [a arquitetura](docs/architecture.md), [o OpenAPI](docs/openapi/platform-v1.yaml), [a integração SimplesLaravel](docs/simpleslaravel.md) e [o runbook de failover](docs/runbooks/failover.md).
