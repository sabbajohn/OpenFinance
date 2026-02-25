# Open Finance Brasil - SDK PHP Unificado

Este é o SDK PHP unificado para todas as APIs do Open Finance Brasil, gerado automaticamente a partir das especificações OpenAPI oficiais.

## Instalação

```bash
composer install
```

## Uso

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

use OpenFinanceBrasil\Configuration;
use OpenFinanceBrasil\Api\AccountsApi;

// Configuração
$config = Configuration::getDefaultConfiguration()
    ->setHost('https://api.banco.com.br/open-banking');

// Exemplo de uso - API de Contas
$accountsApi = new AccountsApi(null, $config);

try {
    $result = $accountsApi->getAccounts();
    print_r($result);
} catch (Exception $e) {
    echo 'Erro: ', $e->getMessage(), PHP_EOL;
}
?>
```

## APIs Disponíveis

### APIs de Dados
- UaccountsAccountsApi
- Uacquiring-servicesBusinessAcquiringServicesApi
- Uacquiring-servicesPersonalAcquiringServicesApi
- UadminMetricsApi
- Uautomatic-paymentsRecurringConsentsApi
- Uautomatic-paymentsRecurringPaymentsApi
- Ubank-fixed-incomesBalancesApi
- Ubank-fixed-incomesProductIdentificationApi
- Ubank-fixed-incomesProductListApi
- Ubank-fixed-incomesTransactionsApi
- Ubank-fixed-incomesTransactionsCurrentApi
- Ucapitalization-bondsCapitalizationBondsApi
- UchannelsChannelsApi
- UcommonDiscoveryApi
- UconsentsConsentsApi
- Ucredit-cardsCreditCardApi
- Ucredit-fixed-incomesBalancesApi
- Ucredit-fixed-incomesProductIdentificationApi
- Ucredit-fixed-incomesProductListApi
- Ucredit-fixed-incomesTransactionsApi
- Ucredit-fixed-incomesTransactionsCurrentApi
- Ucredit-portabilityAccountDataApi
- Ucredit-portabilityConcurrencyManagementApi
- Ucredit-portabilityCreditPortabilityApi
- Ucredit-portabilityPaymentsApi
- UcustomersCustomersApi
- UenrollmentsConsentimentoApi
- UenrollmentsVnculoDeDispositivoApi
- UexchangeExchangeOnlineRateApi
- UexchangeExchangeVETValueApi
- UexchangesEventsApi
- UexchangesOperationDetailsApi
- UexchangesProductListApi
- UfinancingsFinancingsApi
- UfundsBalancesApi
- UfundsProductIdentificationApi
- UfundsProductListApi
- UfundsTransactionsApi
- UfundsTransactionsCurrentApi
- UinsurancesSegurosApi
- UinvestmentsBankFixedIncomesApi
- UinvestmentsCreditFixedIncomesApi
- UinvestmentsFundsApi
- UinvestmentsTreasureTitlesApi
- UinvestmentsVariableIncomesApi
- Uinvoice-financingsInvoiceFinancingsApi
- UloansLoansApi
- Uopendata-accountsAccountsApi
- Uopendata-creditcardsCreditCardsApi
- Uopendata-financingsFinancingsApi
- Uopendata-invoicefinancingsInvoiceFinancingsApi
- Uopendata-loansLoansApi
- Uopendata-unarrangedUnarrangedAccountOverdraftApi
- UparticipantsOrganisationsExportApi
- UpaymentsPagamentosApi
- UpensionRiskCoveragesApi
- UpensionSurvivalCoveragesApi
- Uproducts-servicesAccountsApi
- Uproducts-servicesCreditCardsApi
- Uproducts-servicesFinancingsApi
- Uproducts-servicesInvoiceFinancingsApi
- Uproducts-servicesLoansApi
- Uproducts-servicesUnarrangedAccountOverdraftApi
- Utreasure-titlesBalancesApi
- Utreasure-titlesProductIdentificationApi
- Utreasure-titlesProductListApi
- Utreasure-titlesTransactionsApi
- Utreasure-titlesTransactionsCurrentApi
- Uunarranged-accounts-overdraftUnarrangedAccountsOverdraftApi
- Uvariable-incomesBalancesApi
- Uvariable-incomesBrokerNoteDetailsApi
- Uvariable-incomesProductIdentificationApi
- Uvariable-incomesProductListApi
- Uvariable-incomesTransactionsApi
- Uvariable-incomesTransactionsCurrentApi
- UwebhookAutomaticPaymentsConsentsAndPixPaymentsApi
- UwebhookNoRedirectEnrollmentIdNotificationApi
- UwebhookPaymentsConsentsAndPixPaymentsApi

### Modelos de Dados
Este SDK inclui 1372 modelos de dados para representar as estruturas das APIs.


## Estrutura

- `lib/Api/` - Classes das APIs
- `lib/Model/` - Modelos de dados
- `test/` - Testes unitários
- `docs/` - Documentação

## Executar Testes

```bash
./vendor/bin/phpunit
```

## Licença

MIT License

## Contribuição

Este SDK é gerado automaticamente. Para contribuições, consulte o repositório oficial:
https://github.com/OpenBanking-Brasil/openapi
