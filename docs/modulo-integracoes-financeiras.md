# Módulo de integrações financeiras

Status: proposta inicial para validação.

## Objetivo

Criar uma camada PHP reutilizável para conectar aplicações da Sabba Sistemas a bancos,
iniciadores e provedores de pagamento, normalizando eventos financeiros antes de entregá-los
ao domínio de cada aplicação. O primeiro consumidor será o SimplesLaravel; o desenho também
deve servir a outros projetos sem dependência obrigatória de Laravel ou Eloquent.

O primeiro recorte cobre:

- importação automática de extratos e saldos;
- recebimentos e pagamentos por Pix;
- liquidação e retorno de boletos;
- transferências bancárias identificadas no extrato;
- webhooks, polling de contingência e reprocessamento;
- dados normalizados para sugestão e confirmação de conciliação.

Iniciação de pagamento e conciliação são capacidades diferentes. A API de Pagamentos do Open
Finance inicia e acompanha principalmente pagamentos Pix. A API de Contas fornece saldos e
transações consentidas, que alimentam a conciliação. Boletos dependem de APIs ou arquivos de
remessa/retorno oferecidos por bancos e provedores; por isso não devem ser modelados como se
fossem apenas mais um endpoint da API de Pagamentos.

## Decisões de arquitetura

### 1. Núcleo independente de framework

O núcleo não conhece Laravel, banco de dados, filas nem modelos do ERP. Ele contém contratos,
objetos de valor, normalizadores e regras determinísticas. Integrações de framework ficam em
pacotes adaptadores.

Estrutura proposta no monorepo:

```text
packages/
  financial-integrations/                 # contratos e modelo canônico
  financial-integrations-open-finance/    # Accounts, Payments e Webhook
  financial-integrations-laravel/         # service provider, filas e persistência opcional
```

Namespaces e pacotes Composer propostos:

```text
Sabba\FinancialIntegrations\              sabbasistemas/financial-integrations
Sabba\FinancialIntegrations\OpenFinance\ sabbasistemas/financial-integrations-open-finance
Sabba\FinancialIntegrations\Laravel\     sabbasistemas/financial-integrations-laravel
```

O núcleo deve aceitar PHP `^8.1` inicialmente, alinhado aos SDKs deste repositório e aos outros
pacotes internos encontrados. O adaptador Laravel pode ter uma matriz própria; o primeiro alvo
é Laravel 12 e PHP 8.4, usados pelo SimplesLaravel.

### 2. Portas por capacidade, não por banco

Os consumidores dependem destas interfaces, e cada provedor implementa somente as capacidades
que realmente oferece:

```php
interface AccountDataProvider
{
    public function accounts(ConnectionContext $context): iterable;
    public function balances(ConnectionContext $context): iterable;
    public function transactions(TransactionQuery $query): TransactionPage;
}

interface PixProvider
{
    public function createCharge(PixChargeRequest $request): PixCharge;
    public function getCharge(string $externalId): PixCharge;
    public function requestPayment(PixPaymentRequest $request): Payment;
}

interface BoletoProvider
{
    public function create(BoletoRequest $request): Boleto;
    public function get(string $externalId): Boleto;
    public function cancel(string $externalId): void;
}

interface WebhookProcessor
{
    public function verify(WebhookRequest $request): VerificationResult;
    public function normalize(WebhookRequest $request): iterable;
}
```

Um catálogo de capacidades evita chamar operações indisponíveis. Por exemplo, uma conexão pode
oferecer `account.transactions` e `pix.receipts`, mas não `boleto.create`.

### 3. Modelo canônico e dinheiro sem `float`

Os provedores convertem seus payloads para DTOs imutáveis. Valores monetários são representados
em centavos (`int`) ou por um objeto `Money`; nunca por `float`.

Contrato mínimo de uma transação normalizada:

| Campo | Uso |
| --- | --- |
| `connectionId` | conexão e credencial de origem |
| `accountId` | conta externa no provedor |
| `externalId` | identificador estável do movimento |
| `type` | `pix`, `boleto`, `transfer`, `fee`, `refund`, `other` |
| `direction` | `credit` ou `debit` |
| `status` | `pending`, `settled`, `reversed`, `failed` |
| `amount` e `currency` | valor absoluto em centavos e moeda ISO 4217 |
| `occurredAt` | instante efetivo com fuso horário |
| `description` | histórico normalizado |
| `counterparty` | nome e documento, quando disponíveis |
| `endToEndId` / `txId` | correlação Pix |
| `ourNumber` / `documentNumber` | correlação de boleto |
| `rawReference` | referência ao payload bruto auditável |

O payload bruto deve ser preservado pelo adaptador de persistência com criptografia e política
de retenção. Segredos, tokens, certificados e cabeçalhos de autorização nunca entram em logs ou
metadados de domínio.

### 4. Idempotência e ordem de eventos

A chave de idempotência deve ser composta por `provider + connectionId + resourceType +
externalId`. Quando o provedor não entregar ID estável, usa-se um hash versionado dos campos
canônicos relevantes. A regra precisa considerar que:

- webhooks podem repetir, atrasar ou chegar fora de ordem;
- uma transação pendente pode ser depois liquidada, rejeitada ou estornada;
- polling e webhook podem observar o mesmo recurso;
- reprocessar um lote não pode criar uma segunda baixa financeira.

Cada alteração carrega `occurredAt`, `observedAt`, versão do normalizador e, quando disponível,
versão ou sequência do recurso no provedor. O consumidor aplica transição monotônica de estado
e registra conflitos para revisão.

### 5. Conciliação explicável

O módulo produz sinais e candidatos; a aplicação consumidora continua responsável pela baixa e
pela decisão contábil. A pontuação inicial usa:

1. identificador exato: EndToEndId, TxId, nosso número ou documento;
2. valor, moeda e direção;
3. conta financeira e janela de datas;
4. CPF/CNPJ e nome da contraparte;
5. texto e referências do histórico;
6. tarifas, juros, descontos e estornos explícitos.

Uma conciliação automática somente será habilitada por política configurável, começando com
identificador exato, valor exato, direção compatível e ausência de outro candidato. Os demais
casos geram sugestão com pontuação, sinais e motivo para confirmação humana.

## Fluxo de dados

```text
Banco / Open Finance / PSP / arquivo CNAB
                  |
                  v
       adaptador do provedor
  autentica, verifica e normaliza
                  |
                  v
       transação/evento canônico
        + chave de idempotência
                  |
                  v
       adaptador da aplicação host
    persiste, procura candidatos e audita
                  |
                  v
        baixa e conciliação no ERP
```

O cursor de sincronização só avança após a persistência idempotente de toda a página. Falhas são
retentadas com backoff e jitter. Uma janela de sobreposição por data reduz o risco de perder
movimentos corrigidos tardiamente; a idempotência elimina as repetições esperadas.

## Encaixe no SimplesLaravel

O SimplesLaravel já possui `ImportacaoExtratoBancario`, `ItemExtratoBancario`,
`ConciliacaoBancaria`, retorno de boleto, webhook financeiro e
`ConciliacaoBancariaService`. Essa estrutura continua sendo a fonte oficial do ERP.

O primeiro adaptador deve:

- mapear `connectionId/accountId` para `ContaFinanceira`;
- transformar cada transação canônica em `ItemExtratoBancario`;
- manter os identificadores atuais (`pix:{endToEndId}` e equivalentes) durante a migração;
- chamar `LiquidarTituloFinanceiroService` e `ConciliacaoBancariaService`, sem criar um caminho
  paralelo de baixa;
- preservar a pontuação e os sinais já gravados em `conciliacoes_bancarias.metadados`;
- reaproveitar `ImportacaoRetornoBoleto` e `ItemRetornoBoleto` para retornos CNAB/API;
- trocar o webhook genérico síncrono por verificação específica do provedor, armazenamento do
  evento bruto e processamento em fila.

Antes desse adaptador, o SimplesLaravel precisa expor uma entrada pública para lote de movimentos
normalizados. Hoje a persistência de item está encapsulada em método privado do serviço de
conciliação. A nova entrada deve receber DTOs, não payloads de provedores.

### Pré-requisito nos SDKs deste repositório

O pacote de integração não deve depender do `unified-sdk` até que a consolidação seja corrigida e
tenha testes funcionais. O unificador atual renomeia declarações e arquivos, mas os fontes gerados
ainda usam namespaces e referências `OpenAPI\Client` de cada SDK de origem. Isso pode provocar
colisões e autoload incorreto mesmo depois da correção dos identificadores com hífen.

Para o MVP, a opção mais segura é gerar somente os clientes necessários (`accounts`, `payments` e
`webhook`) com namespaces distintos e cobri-los com testes de contrato. A consolidação completa
fica como tarefa separada e não bloqueia o núcleo financeiro.

Mapeamento inicial:

| Contrato do módulo | SimplesLaravel |
| --- | --- |
| `SyncBatch` | `ImportacaoExtratoBancario` |
| `BankTransaction` | `ItemExtratoBancario` |
| `BoletoSettlement` | `ItemRetornoBoleto` |
| `WebhookEnvelope` | `FinanceiroWebhookEvent` |
| `ReconciliationSuggestion` | candidato e `metadados` da conciliação |

## Segurança e operação

- OAuth 2.0, mTLS e certificados ficam atrás de interfaces de credenciais.
- Segredos devem vir de um cofre ou secret manager e ser identificados por referência.
- Webhooks exigem verificação própria do provedor, proteção contra replay e tolerância temporal.
- O endpoint confirma recebimento rápido e processa o evento em fila.
- Logs usam correlação, mas removem tokens, chaves, documentos e dados bancários sensíveis.
- Métricas mínimas: atraso de sincronização, itens importados/duplicados, falhas por provedor,
  webhooks pendentes, taxa de conciliação automática e divergências.
- O acesso direto ao ecossistema regulado depende do papel e credenciamento da organização.
  Quando isso não existir, os mesmos contratos devem suportar banco parceiro ou agregador.

## Entrega incremental

### Fase 0 — decisões e contratos

- escolher o primeiro provedor/banco e definir se o acesso será direto ou via agregador;
- inventariar autenticação, sandbox, certificados, webhooks e limites;
- gerar os SDKs necessários com namespaces distintos e validar autoload e chamadas de sandbox;
- fechar DTOs, estados, erros, capabilities e política de idempotência;
- criar testes de contrato com payloads anonimizados.

Saída: ADR aprovado, contrato canônico versionado e uma matriz provedor x capacidade.

### Fase 1 — extrato para conciliação

- criar o núcleo e o adaptador de dados de contas;
- sincronizar contas, saldos e transações com cursor;
- integrar a entrada normalizada ao SimplesLaravel;
- operar inicialmente em modo de sugestão, sem baixa automática;
- adicionar replay, observabilidade e testes de duplicidade.

Saída: Pix e transferências do extrato entram automaticamente como itens conciliáveis.

### Fase 2 — recebimentos Pix

- criar/consultar cobrança Pix quando a capability existir;
- normalizar TxId, EndToEndId, devoluções e estornos;
- receber webhooks verificados e usar polling como contingência;
- liberar conciliação automática somente para correspondência inequívoca.

Saída: cobrança, confirmação e conciliação Pix auditáveis de ponta a ponta.

### Fase 3 — boletos

- começar por retorno CNAB/API e reaproveitar a baixa existente;
- adicionar emissão, alteração e cancelamento apenas após escolher o banco/provedor;
- normalizar nosso número, documento, ocorrência, tarifa, juros e data de crédito;
- manter adapters por banco/layout com fixtures de contrato.

Saída: retorno automático e, na segunda etapa, ciclo de vida do boleto.

### Fase 4 — pagamentos e transferências de saída

- modelar intenção, aprovação, submissão, liquidação, rejeição e estorno;
- integrar com a agenda e os lotes de pagamento do SimplesLaravel;
- exigir chave de idempotência e segregação entre quem aprova e quem envia;
- conciliar a saída somente após confirmação bancária.

Saída: Pix e transferências de saída com trilha completa e sem baixa antecipada.

## Critérios de aceite do primeiro MVP

- reprocessar a mesma página ou webhook não duplica movimento nem liquidação;
- todos os movimentos preservam origem, conta, valor, moeda, direção e horário;
- EndToEndId e TxId permanecem pesquisáveis;
- nenhum segredo ou cabeçalho de autenticação é persistido em claro nos metadados;
- falha parcial retoma do último cursor confirmado;
- sugestão mostra os sinais usados na pontuação;
- conciliação automática pode ser desligada globalmente, por tenant, conta e provedor;
- testes de contrato cobrem sucesso, paginação, rate limit, timeout, webhook repetido, evento fora
  de ordem, estorno e payload inválido.

## Decisões pendentes antes da implementação

1. Qual será o primeiro banco, PSP ou agregador?
2. A primeira entrega precisa somente consultar/conciliar ou também iniciar pagamentos?
3. O pacote será publicado em Packagist privado, GitHub Packages ou repositório Composer Satis?
4. Como os certificados e segredos serão armazenados em produção?
5. Qual política permite conciliação automática por tenant e por valor?
6. Por quanto tempo payloads bancários brutos e documentos de contraparte serão retidos?

## Referências oficiais

- [Open Finance Brasil — API de Contas](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/163545295/Informa+es+Gerais+-+Contas+-+v2.1.0-rc.2)
- [Open Finance Brasil — escopo da API de Pagamentos](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/1734934557/Escopo+da+API+Pagamentos+-+v5.0.0-rc.1+-+SV+Pagamentos)
- [Open Finance Brasil — Webhook](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/146669587/Informa+es+Gerais+-+Webhook+-+v1.0.0-rc.2)
- [Banco Central — Pix Cobrança e API Pix](https://www.bcb.gov.br/estabilidadefinanceira/pix-cobranca)
- [Banco Central — regras gerais de boleto](https://www.bcb.gov.br/meubc/faqs/s/boleto)
