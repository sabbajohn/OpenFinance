# OpenFinance Platform

Hub bancário SaaS multiempresa para integrar bancos e ERPs com processamento durável, conciliação auditável, Pix e boletos.

## Estrutura

- `platform/`: Laravel 13, Inertia 3, Vue 3/TypeScript, Tailwind 4 e shadcn-vue.
- `packages/financial-core`: contratos e DTOs financeiros independentes.
- `packages/bank-sicredi`: adapter Sicredi OAuth2/mTLS por produto.
- `packages/bank-bradesco`: adapter Bradesco Pix e Cobrança OAuth2/mTLS.
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

### Teste das APIs Sicredi

Em **Conexões bancárias**, escolha Cobrança para boleto normal/híbrido ou Pix. A Cobrança usa OAuth2 Password, `x-api-key` e os códigos do beneficiário; Pix usa Client ID, Client secret e certificado mTLS. **Testar autenticação** valida apenas as credenciais, sem criar cobranças.

Os endpoints iniciais ficam em `platform/.env` e podem ser ajustados para a versão liberada na adesão:

```dotenv
SICREDI_BOLETO_SANDBOX_BASE_URL=https://api-parceiro.sicredi.com.br/sb/cobranca/boleto/v1/
SICREDI_BOLETO_SANDBOX_TOKEN_URL=https://api-parceiro.sicredi.com.br/sb/auth/openapi/token
SICREDI_BOLETO_PRODUCTION_BASE_URL=https://api-parceiro.sicredi.com.br/cobranca/boleto/v1/
SICREDI_BOLETO_PRODUCTION_TOKEN_URL=https://api-parceiro.sicredi.com.br/auth/openapi/token
SICREDI_PIX_HOMOLOGATION_BASE_URL=https://api-pix-h.sicredi.com.br/api/v2/
SICREDI_PIX_HOMOLOGATION_TOKEN_URL=https://api-pix-h.sicredi.com.br/oauth/token
SICREDI_PIX_PRODUCTION_BASE_URL=https://api-pix.sicredi.com.br/api/v2/
SICREDI_PIX_PRODUCTION_TOKEN_URL=https://api-pix.sicredi.com.br/oauth/token
```

As credenciais e os arquivos PEM são criptografados com a `APP_KEY`, materializados com permissão `0600` somente durante a chamada e apagados ao final. Consulte o [Portal do Desenvolvedor Sicredi](https://developer.sicredi.com.br) para adesão, certificados e escopos habilitados.

### Teste das APIs Bradesco

Na mesma tela de **Conexões bancárias**, selecione Bradesco e informe as credenciais e o certificado associados à aplicação criada no [Portal Bradesco Developers](https://developers.bradesco.com.br). O recorte implementado cobre Pix e os produtos **Cobrança v1.7.2** (boleto normal) e **Cobrança com QR Code v1.8.3** (boleto híbrido). Cada produto deve ser habilitado somente após a assinatura do contrato correspondente no banco. Saldos e extratos permanecem como a próxima etapa.

Os endpoints públicos iniciais podem ser substituídos pelas URLs entregues no onboarding:

```dotenv
BRADESCO_BOLETO_HOMOLOGATION_BASE_URL=https://openapisandbox.prebanco.com.br/
BRADESCO_BOLETO_HOMOLOGATION_TOKEN_URL=https://openapisandbox.prebanco.com.br/auth/server-mtls/v2/token
BRADESCO_BOLETO_PRODUCTION_BASE_URL=https://openapi.bradesco.com.br/
BRADESCO_BOLETO_PRODUCTION_TOKEN_URL=https://openapi.bradesco.com.br/auth/server-mtls/v2/token
BRADESCO_PIX_HOMOLOGATION_BASE_URL=https://openapisandbox.prebanco.com.br/
BRADESCO_PIX_HOMOLOGATION_TOKEN_URL=https://openapisandbox.prebanco.com.br/auth/server/oauth/token
BRADESCO_PIX_PRODUCTION_BASE_URL=https://qrpix.bradesco.com.br/
BRADESCO_PIX_PRODUCTION_TOKEN_URL=https://qrpix.bradesco.com.br/auth/server/oauth/token
```

O callback Pix do Bradesco deve chegar pelo edge com autenticação mTLS validada; o edge injeta o segredo interno consumido pelo endpoint `/api/v1/webhooks/banks/{connection}/pix`. A URL completa aparece no cartão da conexão para ser cadastrada no produto Pix.

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
