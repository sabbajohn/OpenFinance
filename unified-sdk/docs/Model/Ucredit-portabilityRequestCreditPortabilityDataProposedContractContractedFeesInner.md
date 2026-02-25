# # RequestCreditPortabilityDataProposedContractContractedFeesInner

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**fee_name** | **string** | Denominação da Tarifa pactuada |
**fee_code** | **string** | Sigla identificadora da tarifa pactuada |
**fee_charge_type** | **string** | Tipo de cobrança para a tarifa pactuada no contrato. |
**fee_charge** | **string** | \&quot;Forma de cobrança relativa a tarifa pactuada no contrato. (Vide Enum) - Mínimo - Máximo - Fixo - Percentual\&quot; |
**fee_amount** | [**\OpenAPI\Client\Model\RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount**](RequestCreditPortabilityDataProposedContractContractedFeesInnerFeeAmount.md) |  | [optional]
**fee_rate** | **float** | É o valor da tarifa em percentual pactuada no contrato.  [Restrição] Preenchimento obrigatório quando a forma de cobrança for Percentual. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
