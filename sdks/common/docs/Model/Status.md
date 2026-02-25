# # Status

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**code** | **string** | Condição atual da Implementação das APIs do OFB: - OK - A implementação de todas as APIs foi concluída e está totalmente funcional  - UNAVAILABLE - Uma ou mais APIs estão indisponíveis  - PARTIAL_FAILURE - Um ou mais endpoints estão indisponíveis  - SCHEDULED_OUTAGE - Uma interrupção programada (anunciada pelo endpoint /outages) está em vigor   A hierarquia de &#x60;data/status/code&#x60;, em casos concomitantes, segue a ordem conforme os bullets acima (OK, UNAVAILABLE, PARTIAL_FAILURE, SCHEDULED_OUTAGE) |
**explanation** | **string** | Fornece uma explicação da interrupção atual que pode ser exibida para um cliente final. Será obrigatoriamente preenchido se code tiver algum valor que não seja OK |
**detection_time** | **string** | A data e hora em que a interrupção atual foi detectada. Será obrigatoriamente preenchido se a propriedade code for PARTIAL_FAILURE ou UNAVAILABLE | [optional]
**expected_resolution_time** | **string** | A data e hora em que o serviço completo deve continuar (se conhecido). Será obrigatoriamente preenchido se code tiver algum valor que não seja OK | [optional]
**update_time** | **string** | A data e hora em que esse status foi atualizado pela última vez pelo titular dos dados. | [optional]
**unavailable_endpoints** | **string[]** | Endpoints com indisponibilidade | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
