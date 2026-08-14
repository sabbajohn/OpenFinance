# Integração com o SimplesLaravel

O adaptador já foi incorporado ao projeto `SimplesLaravel`. Depois de publicar e migrar os dois sistemas:

1. crie no hub uma integração ERP, um cliente de API restrito à empresa e um endpoint apontando para `https://erp.example.com/api/integrations/openfinance/events/{connection}`;
2. cadastre em `openfinance_connections` os UUIDs da organização, empresa e integração ERP, a URL do hub, o token opaco e o segredo HMAC recebido uma única vez;
3. associe cada `ContaFinanceira` a uma conta do hub em `openfinance_account_mappings`;
4. mantenha workers do SimplesLaravel consumindo a fila `integrations` e o scheduler ativo.

Os observers gravam alterações de contas e títulos no outbox local. O publicador envia lotes idempotentes ao hub. Na volta, o controller valida HMAC, janela de cinco minutos, delivery ID e organização/empresa antes de persistir o inbox. A baixa passa pelo `ConciliacaoBancariaService`, que usa o `LiquidarTituloFinanceiroService` já existente, e só depois confirma ao hub os IDs locais de liquidação.

Tokens e segredos usam casts `encrypted`, não aparecem na serialização e devem ser fornecidos por secret manager. O texto puro só deve existir durante o cadastro/rotação.
