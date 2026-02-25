# # AccountTransactionsData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**transaction_id** | **string** | Código ou identificador único prestado pela instituição que mantém a conta para representar a transação individual.  O ideal é que o &#x60;transactionId&#x60; seja imutável.  No entanto, o &#x60;transactionId&#x60; deve obedecer, no mínimo, as regras de imutabilidade propostas conforme tabela “Data de imutabilidade por tipo de transação” presente nas orientações desta API. |
**completed_authorised_payment_type** | [**\OpenAPI\Client\Model\EnumCompletedAuthorisedPaymentIndicator**](EnumCompletedAuthorisedPaymentIndicator.md) |  |
**credit_debit_type** | [**\OpenAPI\Client\Model\EnumCreditDebitIndicator**](EnumCreditDebitIndicator.md) |  |
**transaction_name** | **string** | Literal usada na instituição financeira para identificar a transação.  A informação apresentada precisa ser a mesma utilizada nos canais digitais da instituição (assim como o histórico de transações apresentado na tela do aplicativo ou do navegador).  Caso a instituição possua mais de um canal digital, a informação compartilhada deve ser a do canal que apresenta a descrição mais completa possível da transação.  Em casos onde a descrição da transação é apresentada com múltiplas linhas, todas as linhas devem ser enviadas (concatenadas) neste atributo, não sendo obrigatória a concatenação das informações já enviadas em outros atributos (ex: valor, data) do mesmo endpoint.  Adicionalmente, o Banco Central pode determinar o formato de compartilhamento a ser adotado por uma instituição participante específica. |
**type** | [**\OpenAPI\Client\Model\EnumTransactionTypes**](EnumTransactionTypes.md) |  |
**transaction_amount** | [**\OpenAPI\Client\Model\AccountTransactionsDataAmount**](AccountTransactionsDataAmount.md) |  |
**transaction_date_time** | **string** | Data e hora original da transação. |
**partie_cnpj_cpf** | **string** | Identificação da pessoa envolvida na transação: pagador ou recebedor (Preencher com o CPF ou CNPJ, sem formatação). Com a IN BCB nº 371, a partir de 02/05/23, o envio das informações de identificação de contraparte tornou-se obrigatória para transações de pagamento. Para maiores detalhes, favor consultar a página &#x60;Orientações - Contas&#x60;.  [Restrição] Quando o \&quot;type“ for preenchido com valor FOLHA_PAGAMENTO e a transmissora for a responsável pelo pagamento de salário (banco-folha), o partieCnpjCpf informado deve ser do empregador relacionado. | [optional]
**partie_person_type** | [**\OpenAPI\Client\Model\EnumPartiePersonType**](EnumPartiePersonType.md) |  | [optional]
**partie_compe_code** | **string** | Código identificador atribuído pelo Banco Central do Brasil às instituições participantes do STR (Sistema de Transferência de reservas) referente à pessoa envolvida na transação. O número-código substituiu o antigo código COMPE. Todos os participantes do STR, exceto as Infraestruturas do Mercado Financeiro (IMF) e a Secretaria do Tesouro Nacional, possuem um número-código independentemente de participarem da Centralizadora da Compensação de Cheques (Compe). | [optional]
**partie_branch_code** | **string** | Código da Agência detentora da conta da pessoa envolvida na transação. (Agência é a dependência destinada ao atendimento aos clientes, ao público em geral e aos associados de cooperativas de crédito, no exercício de atividades da instituição, não podendo ser móvel ou transitória) | [optional]
**partie_number** | **string** | Número da conta da pessoa envolvida na transação | [optional]
**partie_check_digit** | **string** | Dígito da conta da pessoa envolvida na transação | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
