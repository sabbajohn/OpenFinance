# # UnarrangedAccountOverdraftContractedFee

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**fee_name** | **string** | Denominação da Tarifa pactuada |
**fee_code** | **string** | Sigla identificadora da tarifa pactuada |
**fee_charge_type** | [**\OpenAPI\Client\Model\EnumContractFeeChargeType**](EnumContractFeeChargeType.md) |  |
**fee_charge** | [**\OpenAPI\Client\Model\EnumContractFeeCharge**](EnumContractFeeCharge.md) |  |
**fee_amount** | **float** | Valor monetário da tarifa pactuada no contrato.   [Restrição] Preenchimento obrigatório quando a forma de cobrança for diferente de Percentual. | [optional]
**fee_rate** | **float** | É o valor da tarifa em percentual pactuada no contrato.  [Restrição] Preenchimento obrigatório quando a forma de cobrança for Percentual. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
