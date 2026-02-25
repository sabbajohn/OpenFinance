<?php
/**
 * HeaderSelector
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Consents - Open Finance Brasil
 *
 * API que trata da criação, consulta, renovação e revogação de consentimentos para o Open Finance Brasil Dados cadastrais e transacionais - customer-data.   Não possui segregação entre pessoa natural e pessoa jurídica.      # Orientações importantes A API Consents trata exclusivamente dos consentimentos para Dados Cadastrais e Transacionais do Open Finance Brasil. - A API consents é composta de endpoints que permitem:   - Pedido de criação do consentimento pela receptora: `POST /consents`   - Devolução do pedido de criação pela transmissora: `GET /consents/{consentId}`   - Pedido de renovação de consentimento do cliente pela receptora: `POST /consents/{consentId}/extends`   - Devolução de lista com histórico de renovações efetuadas: `GET /consents/{consentId}/extensions`   - Pedido de revogação do consentimento: `DELETE /consents/{consentId}` - Recomenda-se fortemente a leitura da seção [Orientações - [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/219480491) para maiores detalhes, regras e restrições referente aos endpoints da API Consents - As informações da instituição receptora não trafegam na API Consents – a autenticação da receptora se dá através do [DCR](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/17378307/Dynamic+Client+Registration). - Na chamada para a criação, consulta e revogação do consentimento deve-se utilizar um token gerado via `client_credentials`. Na chamada para renovação do consentimento deve-se utilizar um token gerado via `authorization_code`. - Após o `POST` de criação do consentimento, o `STATUS` devolvido na resposta deverá ser `AWAITING_AUTHORISATION`. - O `STATUS` será alterado para `AUTHORISED` somente após autenticação e confirmação por parte do usuário na instituição transmissora dos dados. - Caso não haja confirmação por parte do usuário na transmissora, o status do consentimento deve ser alterado de `AWAITING_AUTHORISATION` para `REJECTED` após 60 minutos. - Todas as datas trafegadas nesta API seguem o padrão da [RFC3339](https://tools.ietf.org/html/rfc3339) e formato \"zulu\". - A descrição do fluxo de consentimento encontra-se disponível no [Portal do desenvolvedor](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/17369300/Dados+Cadastrais+e+Transacionais#Fluxo-b%C3%A1sico-de-consentimento). - O arquivo com o mapeamento completo entre `Roles`, `scopes` e `permissions` está disponibilizado no Portal do desenvolvedor, no mesmo item acima - descrição do fluxo de consentimento. - A receptora deve enviar obrigatoriamente, no pedido de criação de consentimento, todas as permissions dos agrupamentos de dados as quais ela deseja consentimento, conforme tabela abaixo:    ```   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   | ROLE  | CATEGORIA DE DADOS   | AGRUPAMENTO                   | PERMISSIONS                                              | SCOPE OAUTH 2.0               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_PERSONAL_IDENTIFICATIONS_READ                  | customers                     |   |       | Cadastro             | Dados Cadastrais PF           |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_PERSONAL_ADITTIONALINFO_READ                   | customers                     |   |       | Cadastro             | Informações complementares PF |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   | DADOS |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_BUSINESS_IDENTIFICATIONS_READ                  | customers                     |   |       | Cadastro             | Dados Cadastrais PJ           |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CUSTOMERS_BUSINESS_ADITTIONALINFO_READ                   | customers                     |   |       | Cadastro             | Informações complementares PJ |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | ACCOUNTS_READ                                            |                               |   |       |                      |                               |----------------------------------------------------------| accounts                      |   |       | Contas               | Saldos                        | ACCOUNTS_BALANCES_READ                                   |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | ACCOUNTS_READ                                            |                               |   |       |                      |                               |----------------------------------------------------------| accounts                      |   | DADOS | Contas               | Limites                       | ACCOUNTS_OVERDRAFT_LIMITS_READ                           |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | ACCOUNTS_READ                                            |                               |   |       |                      |                               |----------------------------------------------------------| accounts                      |   |       | Contas               | Extratos                      | ACCOUNTS_TRANSACTIONS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------| credit-cards-accounts         |   |       | Cartão de Crédito    | Limites                       | CREDIT_CARDS_ACCOUNTS_LIMITS_READ                        |                               |   |       |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------| credit-cards-accounts         |   |       | Cartão de Crédito    | Transações                    | CREDIT_CARDS_ACCOUNTS_TRANSACTIONS_READ                  |                               |   | DADOS |                      |                               |----------------------------------------------------------| resources                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |       |----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_READ                               |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_BILLS_READ                         | credit-cards-accounts         |   |       | Cartão de Crédito    | Faturas                       |----------------------------------------------------------|                               |   |       |                      |                               | CREDIT_CARDS_ACCOUNTS_BILLS_TRANSACTIONS_READ            | resources                     |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | LOANS_READ                                               |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | LOANS_WARRANTIES_READ                                    |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | LOANS_SCHEDULED_INSTALMENTS_READ                         |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | LOANS_PAYMENTS_READ                                      |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_READ                                          |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_WARRANTIES_READ                               |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_SCHEDULED_INSTALMENTS_READ                    | loans                         |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | FINANCINGS_PAYMENTS_READ                                 | financings                    |   |       |                      |                               |----------------------------------------------------------|                               |   | DADOS | Operações de Crédito | Dados do Contrato             | UNARRANGED_ACCOUNTS_OVERDRAFT_READ                       | unarranged-accounts-overdraft |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_WARRANTIES_READ            | invoice-financings            |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_SCHEDULED_INSTALMENTS_READ | resources                     |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | UNARRANGED_ACCOUNTS_OVERDRAFT_PAYMENTS_READ              |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_READ                                  |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_WARRANTIES_READ                       |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_SCHEDULED_INSTALMENTS_READ            |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | INVOICE_FINANCINGS_PAYMENTS_READ                         |                               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | BANK_FIXED_INCOMES_READ                                  | bank-fixed-incomes            |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | CREDIT_FIXED_INCOMES_READ                                | credit-fixed-incomes          |   |       |                      |                               |----------------------------------------------------------|                               |   | DADOS | Investimento         | Dados da Operação             | FUNDS_READ                                               | variable-incomes              |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | VARIABLE_INCOMES_READ                                    | treasure-titles               |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | TREASURE_TITLES_READ                                     | funds                         |   |       |                      |                               |----------------------------------------------------------|                               |   |       |                      |                               | RESOURCES_READ                                           | resources                     |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|   |       |                      |                               | EXCHANGES_READ                                           |                               |   | DADOS | Câmbio               | Dados da Operação             |----------------------------------------------------------| exchanges                     |   |       |                      |                               | RESOURCES_READ                                           |                               |   |-------|----------------------|-------------------------------|----------------------------------------------------------|-------------------------------|      ``` - A instituição transmissora deve validar o preenchimento correto dos agrupamentos acima no momento da geração do consentimento. - Caso a instiuição receptora envie permissões não existentes nos agrupamentos especificados na tabela, a transmissora deve rejeitar o pedido da receptora dando retorno HTTP Status Code 400. - A transmissora deve retornar, da lista de permissions requisitadas, apenas o subconjunto de permissions por ela suportada, removendo da lista as permissions de produtos não suportados e retornando HTTP Status Code 201. A única exceção a este comportamento são os casos de produtos agrupados, como Operações de Crédito, Investimentos e Câmbio, para os quais todas as permissões do agrupamento devem ser mantidas. Caso não restem permissões funcionais, a instituição transmissora deve retornar o erro HTTP Code \"422 Unprocessable Entity\". - A renovação de consentimento não pode ser efetuada em situações determinadas. É esperado status 401 ou 403 para situações em que o erro for tratado na camada de segurança. Para erros tratados em camada de negócio, a transmissora deve retornar 422 conforme mensagens especificadas na página [Orientações – [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/DraftOF/pages/232915037) - Caso o método `DELETE` seja chamado para um consentimento que já se encontra no `STATUS REJECTED` deve se retornar o STATUS CODE 422. - Pedidos de renovação de consentimento somente podem alterar a data de validade (conforme as regras definidas em [Orientações – [DC] Consentimento](https://openfinancebrasil.atlassian.net/wiki/spaces/DraftOF/pages/232915037)) e a finalidade do consentimento, e aplica-se somente a consentimentos ativos (status `AUTHORISED`). - No caso de criação ou renovação de consentimentos com prazo indeterminado, a receptora não deve enviar o atributo expirationDateTime. Para prazos determinados o campo deve ser enviado. - A renovação de consentimento (`POST /consents/{consentId}/extends`) deve ser possível por apenas um cliente logado.  Isso implica que qualquer usuário (`loggedUser`) com permissão para o consentimento Pessoa Jurídica deve ser capaz de finalizar o fluxo de renovação sem redirecionamento. Para consentimentos Pessoa Natural apenas o `loggedUser` criador do consentimento consegue renovar sem redirecionamento.
 *
 * The version of the OpenAPI document: 3.3.0
 * Contact: gt-interfaces@openbankingbr.org
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.17.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client;

/**
 * HeaderSelector Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class HeaderSelector
{
    /**
     * @param string[] $accept
     * @param string   $contentType
     * @param bool     $isMultipart
     * @return string[]
     */
    public function selectHeaders(array $accept, string $contentType, bool $isMultipart): array
    {
        $headers = [];

        $accept = $this->selectAcceptHeader($accept);
        if ($accept !== null) {
            $headers['Accept'] = $accept;
        }

        if (!$isMultipart) {
            if($contentType === '') {
                $contentType = 'application/json';
            }

            $headers['Content-Type'] = $contentType;
        }

        return $headers;
    }

    /**
     * Return the header 'Accept' based on an array of Accept provided.
     *
     * @param string[] $accept Array of header
     *
     * @return null|string Accept (e.g. application/json)
     */
    private function selectAcceptHeader(array $accept): ?string
    {
        # filter out empty entries
        $accept = array_filter($accept);

        if (count($accept) === 0) {
            return null;
        }

        # If there's only one Accept header, just use it
        if (count($accept) === 1) {
            return reset($accept);
        }

        # If none of the available Accept headers is of type "json", then just use all them
        $headersWithJson = $this->selectJsonMimeList($accept);
        if (count($headersWithJson) === 0) {
            return implode(',', $accept);
        }

        # If we got here, then we need add quality values (weight), as described in IETF RFC 9110, Items 12.4.2/12.5.1,
        # to give the highest priority to json-like headers - recalculating the existing ones, if needed
        return $this->getAcceptHeaderWithAdjustedWeight($accept, $headersWithJson);
    }

    /**
    * Detects whether a string contains a valid JSON mime type
    *
    * @param string $searchString
    * @return bool
    */
    public function isJsonMime(string $searchString): bool
    {
        return preg_match('~^application/(json|[\w!#$&.+-^_]+\+json)\s*(;|$)~', $searchString) === 1;
    }
    
    /**
    * Select all items from a list containing a JSON mime type
    *
    * @param array $mimeList
    * @return array
    */
    private function selectJsonMimeList(array $mimeList): array {
        $jsonMimeList = [];
        foreach ($mimeList as $mime) {
            if($this->isJsonMime($mime)) {
                $jsonMimeList[] = $mime;
            }
        }
        return $jsonMimeList;
    }


    /**
    * Create an Accept header string from the given "Accept" headers array, recalculating all weights
    *
    * @param string[] $accept            Array of Accept Headers
    * @param string[] $headersWithJson   Array of Accept Headers of type "json"
    *
    * @return string "Accept" Header (e.g. "application/json, text/html; q=0.9")
    */
    private function getAcceptHeaderWithAdjustedWeight(array $accept, array $headersWithJson): string
    {
        $processedHeaders = [
            'withApplicationJson' => [],
            'withJson' => [],
            'withoutJson' => [],
        ];

        foreach ($accept as $header) {

            $headerData = $this->getHeaderAndWeight($header);

            if (stripos($headerData['header'], 'application/json') === 0) {
                $processedHeaders['withApplicationJson'][] = $headerData;
            } elseif (in_array($header, $headersWithJson, true)) {
                $processedHeaders['withJson'][] = $headerData;
            } else {
                $processedHeaders['withoutJson'][] = $headerData;
            }
        }

        $acceptHeaders = [];
        $currentWeight = 1000;

        $hasMoreThan28Headers = count($accept) > 28;

        foreach($processedHeaders as $headers) {
            if (count($headers) > 0) {
                $acceptHeaders[] = $this->adjustWeight($headers, $currentWeight, $hasMoreThan28Headers);
            }
        }

        $acceptHeaders = array_merge(...$acceptHeaders);

        return implode(',', $acceptHeaders);
    }

    /**
     * Given an Accept header, returns an associative array splitting the header and its weight
     *
     * @param string $header "Accept" Header
     *
     * @return array with the header and its weight
     */
    private function getHeaderAndWeight(string $header): array
    {
        # matches headers with weight, splitting the header and the weight in $outputArray
        if (preg_match('/(.*);\s*q=(1(?:\.0+)?|0\.\d+)$/', $header, $outputArray) === 1) {
            $headerData = [
                'header' => $outputArray[1],
                'weight' => (int)($outputArray[2] * 1000),
            ];
        } else {
            $headerData = [
                'header' => trim($header),
                'weight' => 1000,
            ];
        }

        return $headerData;
    }

    /**
     * @param array[] $headers
     * @param float   $currentWeight
     * @param bool    $hasMoreThan28Headers
     * @return string[] array of adjusted "Accept" headers
     */
    private function adjustWeight(array $headers, float &$currentWeight, bool $hasMoreThan28Headers): array
    {
        usort($headers, function (array $a, array $b) {
            return $b['weight'] - $a['weight'];
        });

        $acceptHeaders = [];
        foreach ($headers as $index => $header) {
            if($index > 0 && $headers[$index - 1]['weight'] > $header['weight'])
            {
                $currentWeight = $this->getNextWeight($currentWeight, $hasMoreThan28Headers);
            }

            $weight = $currentWeight;

            $acceptHeaders[] = $this->buildAcceptHeader($header['header'], $weight);
        }

        $currentWeight = $this->getNextWeight($currentWeight, $hasMoreThan28Headers);

        return $acceptHeaders;
    }

    /**
     * @param string $header
     * @param int    $weight
     * @return string
     */
    private function buildAcceptHeader(string $header, int $weight): string
    {
        if($weight === 1000) {
            return $header;
        }

        return trim($header, '; ') . ';q=' . rtrim(sprintf('%0.3f', $weight / 1000), '0');
    }

    /**
     * Calculate the next weight, based on the current one.
     *
     * If there are less than 28 "Accept" headers, the weights will be decreased by 1 on its highest significant digit, using the
     * following formula:
     *
     *    next weight = current weight - 10 ^ (floor(log(current weight - 1)))
     *
     *    ( current weight minus ( 10 raised to the power of ( floor of (log to the base 10 of ( current weight minus 1 ) ) ) ) )
     *
     * Starting from 1000, this generates the following series:
     *
     * 1000, 900, 800, 700, 600, 500, 400, 300, 200, 100, 90, 80, 70, 60, 50, 40, 30, 20, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1
     *
     * The resulting quality codes are closer to the average "normal" usage of them (like "q=0.9", "q=0.8" and so on), but it only works
     * if there is a maximum of 28 "Accept" headers. If we have more than that (which is extremely unlikely), then we fall back to a 1-by-1
     * decrement rule, which will result in quality codes like "q=0.999", "q=0.998" etc.
     *
     * @param int  $currentWeight varying from 1 to 1000 (will be divided by 1000 to build the quality value)
     * @param bool $hasMoreThan28Headers
     * @return int
     */
    public function getNextWeight(int $currentWeight, bool $hasMoreThan28Headers): int
    {
        if ($currentWeight <= 1) {
            return 1;
        }

        if ($hasMoreThan28Headers) {
            return $currentWeight - 1;
        }

        return $currentWeight - 10 ** floor( log10($currentWeight - 1) );
    }
}
