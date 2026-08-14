# Runbook de failover

Execute trimestralmente em staging com carga de fundo. Antes e depois, registre o último `event_ledger.id`, decisão confirmada e LSN do PostgreSQL.

## Matriz

| Falha | Resultado esperado | Validação |
|---|---|---|
| Web node | Cloudflare/HAProxy retira o nó | `/up` disponível, sem erro sustentado |
| Worker | Horizon redistribui; sweepers reparam | inbox/outbox zeram após retorno |
| Redis primário | Sentinel promove réplica | nenhum evento durável perdido |
| PostgreSQL líder | Patroni promove nó síncrono | decisão confirmada presente, RPO zero |
| HAProxy edge | Cloudflare usa o segundo edge | tráfego continua |
| Um MinIO | escritas passam ao spool PostgreSQL | ACK continua; spool drena após retorno |

O serviço deve recuperar em até cinco minutos. Interrompa o teste se o PostgreSQL perder quorum; não force promoção assíncrona para decisões financeiras.

## Evidências obrigatórias

Para cada cenário, registre horário de início/fim, alertas disparados, tempo até `/api/v1/health` estabilizar, profundidade máxima das seis filas e tempo de drenagem. Compare antes/depois:

- total e IDs distintos no inbox e no ledger;
- decisões `confirmed` e respectivos IDs de liquidação ERP;
- entregas ERP e chaves idempotentes;
- checksum dos payloads no spool/MinIO;
- LSN confirmado pelo quorum síncrono.

O ensaio só passa com RTO ≤ 5 minutos, nenhuma decisão confirmada ausente, nenhuma duplicidade canônica e reconciliação completa do backlog.
