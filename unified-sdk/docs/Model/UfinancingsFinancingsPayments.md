# # FinancingsPayments

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**paid_instalments** | **float** | Quantidade total de parcelas pagas do contrato referente à Modalidade de Crédito informada.  [Restrição] Obrigatório para modalidades que possuam parcelas. | [optional]
**contract_outstanding_balance** | **float** | Valor necessário para o cliente liquidar a dívida, ou seja, este campo deve ser preenchido com o saldo devedor atualizado descrito no DDC (Documento Descritivo de Crédito). |
**releases** | [**\OpenAPI\Client\Model\FinancingsReleases[]**](FinancingsReleases.md) | Lista dos pagamentos realizados no período |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
