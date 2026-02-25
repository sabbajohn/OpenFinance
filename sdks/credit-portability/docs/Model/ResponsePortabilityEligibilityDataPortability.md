# # ResponsePortabilityEligibilityDataPortability

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**is_eligible** | **bool** | Sinaliza se as características do contrato é elegível para pedido de portabilidade de crédito via OFB (sem considerar a disponibilidade da portabilidade de crédito) |
**ineligible** | [**\OpenAPI\Client\Model\ResponsePortabilityEligibilityDataPortabilityIneligible**](ResponsePortabilityEligibilityDataPortabilityIneligible.md) |  | [optional]
**status** | **string** | Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo &#x60;isEligible&#x60; for igual a &#x60;TRUE&#x60; | [optional]
**status_update_date_time** | **string** | Data e hora em que o contrato teve o status atualizado. Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format).  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo &#x60;isEligible&#x60; for igual a &#x60;TRUE&#x60; | [optional]
**channel** | **string** | Informação sobre a disponibilidade ou não de um contrato para a portabilidade de crédito  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo &#x60;status&#x60; for igual a &#x60;EM_ANDAMENTO&#x60; | [optional]
**company_name** | **string** | Nome da Instituição Proponente responsável pelo pedido de portabilidade de credito anterior a atual consulta p.ex.Empresa A.  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo &#x60;status&#x60; for igual a &#x60;EM_ANDAMENTO&#x60; | [optional]
**company_cnpj** | **string** | Número completo do CNPJ da instituição O CNPJ corresponde ao número de inscrição no Cadastro de Pessoa Jurídica. Deve-se ter apenas números do CNPJ, sem máscara  [RESTRIÇÃO] Campo de preenchimento obrigatório quando o campo &#x60;status&#x60; for igual a &#x60;EM_ANDAMENTO&#x60; e o campo &#x60;channel&#x60; for igual a &#x60;OFB&#x60;. | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
