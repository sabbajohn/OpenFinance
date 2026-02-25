# # InvoiceFinancingsContract

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**contract_number** | **string** | Número do contrato dado pela instituição contratante. |
**ipoc_code** | **string** | Número padronizado do contrato - IPOC (Identificação Padronizada da Operação de Crédito). Segundo DOC 3040, composta por: - **CNPJ da instituição:** 8 (oito) posições iniciais; - **Modalidade da operação:** 4 (quatro) posições; - **Tipo do cliente:** 1 (uma) posição( 1 &#x3D; pessoa natural - CPF, 2&#x3D; pessoa jurídica – CNPJ, 3 &#x3D; pessoa física no exterior, 4 &#x3D; pessoa jurídica no exterior, 5 &#x3D; pessoa natural sem CPF e 6 &#x3D; pessoa jurídica sem CNPJ); - **Código do cliente:** O número de posições varia conforme o tipo do cliente:   1. Para clientes pessoa física com CPF (tipo de cliente &#x3D; 1), informar as 11 (onze) posições do CPF;   2. Para clientes pessoa jurídica com CNPJ (tipo de cliente &#x3D; 2), informar as 8 (oito) posições iniciais do CNPJ;   3. Para os demais clientes (tipos de cliente 3, 4, 5 e 6), informar 14 (catorze) posições com complemento de zeros à esquerda se a identificação tiver tamanho inferior; - **Código do contrato:** 1 (uma) até 40 (quarenta) posições, sem complemento de caracteres. |
**product_name** | **string** | Denominação/Identificação do nome da Modalidade da Operação de Crédito divulgado ao cliente |
**product_type** | [**\OpenAPI\Client\Model\EnumContractProductTypeInvoiceFinancings**](EnumContractProductTypeInvoiceFinancings.md) |  |
**product_sub_type** | [**\OpenAPI\Client\Model\EnumContractProductSubTypeInvoiceFinancings**](EnumContractProductSubTypeInvoiceFinancings.md) |  |
**contract_date** | **\DateTime** | Data de contratação da operação de crédito. Especificação RFC-3339 |
**disbursement_dates** | **\DateTime[]** | Lista que traz as Datas de Desembolso do valor contratado. | [optional]
**settlement_date** | **\DateTime** | Data de liquidação da operação. | [optional]
**contract_amount** | **float** | Valor contratado da operação. Expresso em valor monetário com no mínimo 2 casas e no máximo 4 casas decimais. Nos casos em que não houver este valor explícito no contrato do produto, enviar como 0.00. |
**currency** | **string** | Moeda referente ao valor da garantia, segundo modelo ISO-4217. p.ex. &#39;BRL&#39; Todos os valores monetários informados estão representados com a moeda vigente do Brasil | [optional]
**due_date** | **\DateTime** | Data de vencimento Final da operação. Especificação RFC-3339 | [optional]
**instalment_periodicity** | [**\OpenAPI\Client\Model\EnumContractInstalmentPeriodicity**](EnumContractInstalmentPeriodicity.md) |  |
**instalment_periodicity_additional_info** | **string** | Campo obrigatório para complementar a informação relativa à periodicidade de pagamento regular quando tiver a opção OUTROS. | [optional]
**first_instalment_due_date** | **\DateTime** | Data de vencimento primeira parcela do principal. | [optional]
**cet** | **float** | CET – Custo Efetivo Total deve ser expresso na forma de taxa percentual anual e incorpora todos os encargos e despesas incidentes nas operações de crédito (taxa de juro, mas também tarifas, tributos, seguros e outras despesas cobradas).  O preenchimento deve respeitar as 6 casas decimais, mesmo que venham preenchidas com zeros (representação de porcentagem p.ex: 0.150000. Este valor representa 15%. O valor 1 representa 100%).  Para o público PF (pessoa física) o campo é de envio obrigatório para contratos firmados a partir de 2008, conforme Resolução CMN 3.517. Para o público PJ (pessoa jurídica) o campo é de envio obrigatório para contratos firmados a partir de 2011, conforme Resolução CMN 3.909. O campo poderá ser preenchido com 0.00 em cenários nos quais a casa não tenha a informação de CET (Custo efetivo total) apenas para as exceções listadas abaixo: - Em contratos anteriores a 2008 (para o público PF); - Em contratos anteriores a 2011 (para o público PJ); - Público PJ de médio ou grande porte. |
**amortization_scheduled** | [**\OpenAPI\Client\Model\EnumContractAmortizationScheduled**](EnumContractAmortizationScheduled.md) |  |
**amortization_scheduled_additional_info** | **string** | Campo obrigatório para complementar a informação relativa à amortização quando selecionada a opção OUTROS. | [optional]
**interest_rates** | [**\OpenAPI\Client\Model\InvoiceFinancingsContractInterestRate[]**](InvoiceFinancingsContractInterestRate.md) | Objeto que traz o conjunto de informações necessárias para demonstrar a composição das taxas de juros remuneratórios da Modalidade de crédito.   Caso o contrato não possua taxas de juros, deve ser compartilhada uma lista vazia. Caso o contrato possua uma taxa de juros com valor 0, deve ser compartilhado um objeto com o valor 0 de forma explícita. |
**contracted_fees** | [**\OpenAPI\Client\Model\InvoiceFinancingsContractedFee[]**](InvoiceFinancingsContractedFee.md) | Lista que traz as informações das tarifas pactuadas no contrato. |
**contracted_finance_charges** | [**\OpenAPI\Client\Model\InvoiceFinancingsFinanceCharge[]**](InvoiceFinancingsFinanceCharge.md) | Lista que traz os encargos pactuados no contrato |
**has_insurance_contracted** | **bool** | Campo que identifica se existe seguro contratado para o Direito Creditório Descontado, onde seguro contratado é true e não contratado é false. O não envio do campo significa que a instituição não oferece este produto. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
