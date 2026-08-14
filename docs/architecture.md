# Arquitetura do OpenFinance Platform

O sistema é um monólito modular stateless. PostgreSQL é a autoridade sobre o estado, inbox, outbox, decisões e o spool temporário de payloads. Redis/Horizon executa trabalho reconstruível; MinIO retém o bruto compactado e criptografado.

## Caminho crítico

1. Webhook ou polling persiste o evento no `inbox_events` e o corpo criptografado em `raw_payloads`.
2. O HTTP responde `202` após o commit PostgreSQL.
3. Workers normalizam lançamentos com a chave `provider + connection + resource + external_id`; sem ID, usam fingerprint `v1`.
4. A conciliação cria candidatos e evidências. Automação exige identificador, valor, direção, moeda, conta mapeada e um único candidato.
5. Uma decisão bloqueia o caso com `expected_version`. O outbox gera entrega HMAC ao ERP.
6. O SimplesLaravel executa a baixa por `ConciliacaoBancariaService`, que reutiliza `LiquidarTituloFinanceiroService`, e confirma os IDs.

## Topologia de produção

- Cloudflare Load Balancer aponta para dois HAProxy em VPS/provedores distintos.
- Os edges alcançam duas ou mais réplicas Coolify por WireGuard.
- Três nós PostgreSQL usam Patroni/etcd, quorum síncrono, HAProxy/PgBouncer, WAL contínuo e pgBackRest.
- Redis primário/réplica com Sentinel. O sweeper recompõe outbox e entregas.
- MinIO começa em dois nós; falha de um pausa escritas e usa o spool PostgreSQL. Antes da meta de volume, migrar para quatro domínios de armazenamento.

## SLOs e alertas

- Disponibilidade mensal: 99,9%; RTO: 5 minutos; RPO: zero para decisões confirmadas.
- Alertar por ACK p95 > 500 ms, atraso de fila/sync, outbox pendente, spool crescente, certificado a 30 dias, rate limit, conflito, duplicidade e queda na taxa de automação.
- Logs estruturados carregam IDs/correlação; documento, nome, corpo e credenciais nunca entram em logs.
