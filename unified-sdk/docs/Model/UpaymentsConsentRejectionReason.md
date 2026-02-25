# # ConsentRejectionReason

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | [**\OpenAPI\Client\Model\EnumConsentRejectionReasonType**](EnumConsentRejectionReasonType.md) |  |
**detail** | **string** | Contém informações adicionais ao consentimento rejeitado. - VALOR_INVALIDO: O valor enviado não é válido para o QR Code informado; - NAO_INFORMADO: Não informada pela detentora de conta; - FALHA_INFRAESTRUTURA: [Descrição de qual falha na infraestrutura inviabilizou o processamento]. - TEMPO_EXPIRADO_AUTORIZACAO: Consentimento expirou antes que o usuário pudesse confirmá-lo. - TEMPO_EXPIRADO_CONSUMO: O usuário não finalizou o fluxo de pagamento e o consentimento expirou; - REJEITADO_USUARIO: O usuário rejeitou a autorização do consentimento  - CONTAS_ORIGEM_DESTINO_IGUAIS: A conta selecionada é igual à conta destino e não permite realizar esse pagamento. - CONTA_NAO_PERMITE_PAGAMENTO: A conta selecionada é do tipo [salario/investimento/liquidação/outros] e não permite realizar esse pagamento. - SALDO_INSUFICIENTE: A conta selecionada não possui saldo suficiente para realizar o pagamento. - VALOR_ACIMA_LIMITE: O valor ultrapassa o limite estabelecido [na instituição/no arranjo/outro] para permitir a realização de transações pelo cliente. - QRCODE_INVALIDO: O QRCode utilizado para a iniciação de pagamento não é válido.   [Restrição] Caso consentimento rejeitado de versões nas quais não havia o campo rejectionReason retornar o seguinte detail: Motivo de rejeição inexistente em versões anteriores. |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
