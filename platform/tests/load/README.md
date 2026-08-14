# Teste de carga de webhooks

Use uma conexão Sicredi exclusiva de staging e execute:

```bash
WEBHOOK_URL=https://staging.example.com/api/v1/webhooks/banks/CONNECTION_UUID \
BANK_WEBHOOK_TOKEN=secret-configurado-na-conexao \
k6 run tests/load/bank-webhook.js
```

O perfil sustenta 200 eventos/s por 10 minutos e depois gera uma rajada de 500 eventos/s por um minuto. O teste falha se o ACK p95 ultrapassar 500 ms ou se a taxa de erro chegar a 0,1%. Depois da execução, valide que inbox, normalização, reconciliação e outbox drenaram sem diferença entre eventos únicos e movimentos persistidos.
