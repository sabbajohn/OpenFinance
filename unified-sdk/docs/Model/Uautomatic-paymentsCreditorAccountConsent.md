# # CreditorAccountConsent

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**ispb** | **string** | Deve ser preenchido com o ISPB (Identificador do Sistema de Pagamentos Brasileiros) do participante do SPI (Sistema de pagamentos instantâneos) somente com números. |
**issuer** | **string** | Código da Agência emissora da conta sem dígito.  (Agência é a dependência destinada ao atendimento aos clientes, ao público em geral e aos associados de cooperativas de crédito,  no exercício de atividades da instituição, não podendo ser móvel ou transitória).  [Restrição] Preenchimento obrigatório para os seguintes tipos de conta: CACC (CONTA_DEPOSITO_A_VISTA) e SVGS (CONTA_POUPANCA). | [optional]
**number** | **string** | Deve ser preenchido com o número da conta transacional do usuário pagador, com dígito verificador (se este existir),  se houver valor alfanumérico, este deve ser convertido para 0. |
**account_type** | [**\OpenAPI\Client\Model\EnumAccountTypeConsents**](EnumAccountTypeConsents.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
