# # CreditCardAccountsBillsTransactions

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**transaction_id** | **string** | Código ou identificador único prestado pela instituição que mantém a conta para representar a transação individual.  É esperado que o &#x60;transactionId&#x60; seja único, imutável e estável. |
**identification_number** | **string** | Número de identificação do cartão: corresponde aos 4 últimos dígitos do cartão para PF, ou então, preencher com um identificador para PJ, com as caracteristicas definidas para os IDs no Open Finance. |
**transaction_name** | **string** | Literal usada na instituição financeira para identificar a transação. A informação apresentada precisa ser a mesma utilizada nos canais eletrônicos da instituição (extrato e fatura). |
**bill_id** | **string** | Informação que identifica a fatura onde consta a transação informada. | [optional]
**credit_debit_type** | [**\OpenAPI\Client\Model\EnumCreditDebitIndicator**](EnumCreditDebitIndicator.md) |  |
**transaction_type** | [**\OpenAPI\Client\Model\EnumCreditCardTransactionType**](EnumCreditCardTransactionType.md) |  |
**transactional_additional_info** | **string** | Campo livre, de preenchimento obrigatório quando selecionado tipo de transação \&quot;OUTROS\&quot; | [optional]
**payment_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsPaymentType**](EnumCreditCardAccountsPaymentType.md) |  | [optional]
**fee_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountFee**](EnumCreditCardAccountFee.md) |  | [optional]
**fee_type_additional_info** | **string** | Campo livre, de preenchimento obrigatório quando selecionada tipo de tarifa \&quot;OUTRA\&quot; | [optional]
**other_credits_type** | [**\OpenAPI\Client\Model\EnumCreditCardAccountsOtherCreditType**](EnumCreditCardAccountsOtherCreditType.md) |  | [optional]
**other_credits_additional_info** | **string** | Campo livre para preenchimento de dados adicionais de outros tipos de crédito contratados no cartão.  [Restrição] Preenchimento obrigatório quando selecionado no campo outros tipos de crédito \&quot;OUTROS\&quot;. | [optional]
**charge_identificator** | **float** | Número da parcela que está sendo informada.  [Restrição] Preenchimento obrigatório se Tipo de Pagamento (paymentType) selecionada for &#39;A_PRAZO&#39;. | [optional]
**charge_number** | **float** | Quantidade de parcelas.    [Restrição] O campo deve ser preenchido quando houverem parcelas relacionadas a transação. | [optional]
**brazilian_amount** | [**\OpenAPI\Client\Model\CreditCardAccountsTransactionBrazilianAmount**](CreditCardAccountsTransactionBrazilianAmount.md) |  |
**amount** | [**\OpenAPI\Client\Model\CreditCardAccountsTransactionAmount**](CreditCardAccountsTransactionAmount.md) |  |
**transaction_date_time** | **\DateTime** | Data e hora da transação disponível para os clientes nos canais digitais da instituição. Neste momento, é obrigatório preencher com dados reais com precisão de data, hora e minuto, mesmo que a instituição não exiba para o cliente nesse nível de granularidade, em algumas situações. Dessa forma, os segundos e milissegundos podem ser preenchidos com zero (0), por exemplo: 2024-01-29T11:15:00.000Z. |
**bill_post_date** | **\DateTime** | Data em que a transação foi inserida na fatura |
**payee_mcc** | **float** | O MCC ou o código da categoria do estabelecimento comercial. Os MCCs são agrupados segundo suas similaridades. O MCC é usado para classificar o negócio pelo tipo fornecido de bens ou serviços. Os MCCs são atribuídos por tipo de comerciante (por exemplo, um para hotéis, um para lojas de materiais de escritório, etc.) ou por nome de comerciante (por exemplo, 3000 para a United Airlines). | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
