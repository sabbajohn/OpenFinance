<?php
/**
 * EnumTransactionTypes
 *
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Accounts - Open Finance Brasil
 *
 * API de contas de depósito à vista, contas de poupança e contas pré-pagas do Open Finance Brasil – Fase 2. API que retorna informações de contas de depósito à vista, contas de poupança e contas de pagamento pré-pagas mantidas nas instituições transmissoras por seus clientes, incluindo dados de identificação da conta, saldos, limites e transações.\\ Não possui segregação entre pessoa natural e pessoa jurídica.\\ Requer consentimento do cliente para todos os `endpoints`.  # Orientações A `Role`  do diretório de participantes relacionada à presente API é a `DADOS`.\\ Para todos os `endpoints` desta API é previsto o envio de um `token` através do header `Authorization`.\\ Este token deverá estar relacionado ao consentimento (`consentId`) mantido na instituição transmissora dos dados, o qual permitirá a pesquisa e retorno, na API em questão, dos  dados relacionados ao `consentId` específico relacionado.\\ Os dados serão devolvidos na consulta desde que o `consentId` relacionado corresponda a um consentimento válido e com o status `AUTHORISED`.\\ É também necessário que o recurso em questão (conta, contrato, etc) esteja disponível na instituição transmissora (ou seja, sem boqueios de qualquer natureza e com todas as autorizações/consentimentos já autorizados).\\ Além disso as `permissions` necessárias deverão ter sido solicitadas quando da criação do consentimento relacionado (`consentId`).\\ Relacionamos a seguir as `permissions` necessárias para a consulta de dados em cada `endpoint` da presente API.  ## Permissions necessárias para a API Accounts  Para cada um dos paths desta API, além dos escopos (`scopes`) indicados existem `permissions` que deverão ser observadas:  ### `/accounts`   - permissions:     - GET: **ACCOUNTS_READ** ### `/accounts/{accountId}`   - permissions:     - GET: **ACCOUNTS_READ** ### `/accounts/{accountId}/balances`   - permissions:     - GET: **ACCOUNTS_BALANCES_READ** ### `/accounts/{accountId}/transactions`   - permissions:     - GET: **ACCOUNTS_TRANSACTIONS_READ** ### `/accounts/{accountId}/transactions-current`   - permissions:     - GET: **ACCOUNTS_TRANSACTIONS_READ** ### `/accounts/{accountId}/overdraft-limits`   - permissions:     - GET: **ACCOUNTS_OVERDRAFT_LIMITS_READ**  ## Data de imutabilidade por tipo de transação​ O identificador de transações de contas é de envio obrigatório no Open Finance Brasil. De acordo com o tipo da transação deve haver o envio de um identificador único, estável e imutável em D0 ou D+1, conforme tabela abaixo ``` |---------------------------------------|-------------------------|-----------------------| | Tipo de Transação                     | Data da Obrigatoriedade | Data da Imutabilidade | |---------------------------------------|-------------------------|-----------------------| | TED                                   | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | PIX                                   | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | TRANSFERENCIA MESMA INSTITUIÇÃO (TEF) | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | TARIFA SERVIÇOS AVULSOS               | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | FOLHA DE PAGAMENTO                    | DO                      | DO                    | |---------------------------------------|-------------------------|-----------------------| | DOC                                   | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | BOLETO                                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | CONVÊNIO ARRECADAÇÃO                  | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | PACOTE TARIFA SERVIÇOS                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | DEPÓSITO                              | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | SAQUE                                 | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | CARTÃO                                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | ENCARGOS JUROS CHEQUE ESPECIAL        | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | RENDIMENTO APLICAÇÃO FINANCEIRA       | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | PORTABILIDADE SALÁRIO                 | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | RESGATE APLICAÇÃO FINANCEIRA          | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | OPERAÇÃO DE CRÉDITO                   | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| | OUTROS                                | DO                      | D+1                   | |---------------------------------------|-------------------------|-----------------------| ```  Para consultar as regras aplicáveis ao comportamento do transacionID de acordo com o status da transação, consultar a página [Orientações - Contas](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/193658890)
 *
 * The version of the OpenAPI document: 2.4.2
 * Contact: gt-interfaces@openbankingbr.org
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.17.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client\Model;
use \OpenAPI\Client\ObjectSerializer;

/**
 * EnumTransactionTypes Class Doc Comment
 *
 * @category Class
 * @description O campo deve classificar a transação em um dos tipos descritos.  O transmissor deve classificar as transações disponíveis associando-a a um dos itens do Enum listado neste campo.  A opção OUTROS só deve ser utilizada para os casos em que de fato a transação compartilhada não possa ser classificada como um dos itens deste Enum.  Por exemplo no caso de recebimento de pensão alimentícia.
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class EnumTransactionTypes
{
    /**
     * Possible values of this enum
     */
    public const TED = 'TED';

    public const DOC = 'DOC';

    public const PIX = 'PIX';

    public const TRANSFERENCIA_MESMA_INSTITUICAO = 'TRANSFERENCIA_MESMA_INSTITUICAO';

    public const BOLETO = 'BOLETO';

    public const CONVENIO_ARRECADACAO = 'CONVENIO_ARRECADACAO';

    public const PACOTE_TARIFA_SERVICOS = 'PACOTE_TARIFA_SERVICOS';

    public const TARIFA_SERVICOS_AVULSOS = 'TARIFA_SERVICOS_AVULSOS';

    public const FOLHA_PAGAMENTO = 'FOLHA_PAGAMENTO';

    public const DEPOSITO = 'DEPOSITO';

    public const SAQUE = 'SAQUE';

    public const CARTAO = 'CARTAO';

    public const ENCARGOS_JUROS_CHEQUE_ESPECIAL = 'ENCARGOS_JUROS_CHEQUE_ESPECIAL';

    public const RENDIMENTO_APLIC_FINANCEIRA = 'RENDIMENTO_APLIC_FINANCEIRA';

    public const PORTABILIDADE_SALARIO = 'PORTABILIDADE_SALARIO';

    public const RESGATE_APLIC_FINANCEIRA = 'RESGATE_APLIC_FINANCEIRA';

    public const OPERACAO_CREDITO = 'OPERACAO_CREDITO';

    public const OUTROS = 'OUTROS';

    /**
     * Gets allowable values of the enum
     * @return string[]
     */
    public static function getAllowableEnumValues()
    {
        return [
            self::TED,
            self::DOC,
            self::PIX,
            self::TRANSFERENCIA_MESMA_INSTITUICAO,
            self::BOLETO,
            self::CONVENIO_ARRECADACAO,
            self::PACOTE_TARIFA_SERVICOS,
            self::TARIFA_SERVICOS_AVULSOS,
            self::FOLHA_PAGAMENTO,
            self::DEPOSITO,
            self::SAQUE,
            self::CARTAO,
            self::ENCARGOS_JUROS_CHEQUE_ESPECIAL,
            self::RENDIMENTO_APLIC_FINANCEIRA,
            self::PORTABILIDADE_SALARIO,
            self::RESGATE_APLIC_FINANCEIRA,
            self::OPERACAO_CREDITO,
            self::OUTROS
        ];
    }
}


