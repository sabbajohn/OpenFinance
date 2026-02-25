# # LoansPayments

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**paid_instalments** | **float** | Quantidade total de parcelas pagas do contrato referente à Modalidade de Crédito informada. | [optional]
**contract_outstanding_balance** | **float** | Valor necessário para o cliente liquidar a dívida, ou seja, este campo deve ser preenchido com o saldo devedor atualizado descrito no DDC (Documento Descritivo de Crédito).  O valor a liquidar atualizado do dia deve ser ajustado para compartilhamento pela Transmissora até às 10:00 do mesmo dia, especialmente para Crédito Pessoal Clean, escopo de Portabilidade de Crédito, pois este valor é utilizado pela Receptora em papel de Proponente para pagamento à Transmissora em papel de Credora. |
**last_updated_contract_outstanding_balance** | **\DateTime** | Data e hora da última atualização do valor do campo contractOutstandingBalance, conforme especificação RFC-3339, formato UTC.   [Restrição] O envio do campo será obrigatório para CPC (campo “productSubTypeCategory” preenchido com CREDITO_PESSOAL_CLEAN na identificação da operação, no endpoint /contracts/{contractId}). | [optional]
**total_remaining_amount** | **float** | Valor total que falta para o cliente liquidar o contrato, considerando o somatório total de todas as parcelas a vencer e vencidas, bem como todas as taxas, tarifas e encargos das parcelas. Nos casos de contrato com taxas pós-fixadas, considerar apenas valores pré-fixados, visto que o cálculo pós-fixado ocorre apenas em momento futuro, e que o valor está sujeito às variações de seu indexador.   [Restrição] O envio do campo será obrigatório para CPC (campo “productSubTypeCategory” preenchido com CREDITO_PESSOAL_CLEAN na identificação da operação, no endpoint /contracts/{contractId}). | [optional]
**releases** | [**\OpenAPI\Client\Model\LoansReleases[]**](LoansReleases.md) | Lista dos pagamentos realizados no período |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
