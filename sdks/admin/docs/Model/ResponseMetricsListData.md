# # ResponseMetricsListData

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**endpoint** | **string** | URL ou URI do endpoint |
**request_time** | **\DateTime** | Data e hora que as métricas foram requisitadas. |
**availability** | [**\OpenAPI\Client\Model\AvailabilityMetrics**](AvailabilityMetrics.md) |  |
**invocations** | [**\OpenAPI\Client\Model\InvocationMetrics**](InvocationMetrics.md) |  |
**average_response** | [**\OpenAPI\Client\Model\AverageMetrics**](AverageMetrics.md) |  |
**average_tps** | [**\OpenAPI\Client\Model\AverageTPSMetrics**](AverageTPSMetrics.md) |  |
**peak_tps** | [**\OpenAPI\Client\Model\PeakTPSMetrics**](PeakTPSMetrics.md) |  |
**errors** | [**\OpenAPI\Client\Model\ErrorMetrics**](ErrorMetrics.md) |  |
**rejections** | [**\OpenAPI\Client\Model\RejectionMetrics**](RejectionMetrics.md) |  |

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
