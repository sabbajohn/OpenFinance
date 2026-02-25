<?php
/**
 * RecurringPaymentsApi
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Automatic Payments - Open Finance Brasil
 *
 * API de Iniciação de Pagamentos automáticos, responsável por viabilizar as operações de iniciação de pagamentos automáticos (Pix automático e Transferências Inteligentes) para o Open Finance Brasil.  Para cada uma das formas de pagamento previstas é necessário obter prévio consentimento do cliente através dos endpoints dedicados ao consentimento nesta API.  # Orientações - `CONTA`, referente às instituições detentoras de conta participantes do Open Finance Brasil; - `PAGTO`, referente às instituições iniciadoras de pagamento participantes do Open Finance Brasil.  Os tokens utilizados para consumo nos endpoints de consentimentos devem possuir o scope recurring-payments e os endpoints de pagamentos recorrentes devem possuir os scopes openid e recurring-payments.  Esta API não requer a implementação de permissions para sua utilização.  Todas as requisições e respostas devem ser assinadas seguindo o protocolo estabelecido na sessão Assinaturas do guia de segurança.  ## Orientações gerais sobre os consentimentos de pagamentos automáticos - Duração e reutilização do consentimento: A utilização das credenciais geradas a partir de uma autorização de um consentimento recorrente deve durar até que o consentimento recorrente atinja o fim do seu ciclo de vida, conforme detalhado na sua [máquina de estados](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/198410647).  - Credenciais: As credenciais (authorization_code) geradas na autorização do consentimento devem ser utilizadas para criação dos pagamentos subsequentes utilizando o mecanismo de refresh, caso necessário. Maiores informações através do link [[PT] Open Finance Brasil Financial-grade API Security Profile 1.0 Implementers Draft 3 - Área do Desenvolvedor -Open Finance Brasil - Área do Desenvolvedor (atlassian.net)](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/82051180/PT+Open+Finance+Brasil+Financial-grade+API+Security+Profile+1.0+Implementers+Draft+3#7.2.2.-Servidor-de-autorização)  ## Regras do arranjo Pix A implementação e o uso da API de Pagamentos Automáticos (Pix) devem seguir as regras do arranjo Pix do Banco Central, que podem ser encontradas no link abaixo:   [Banco Central do Brasil](https://www.bcb.gov.br/estabilidadefinanceira/pix?modalAberto=regulamentacao_pix)  ## Assinatura de payloads No contexto da API de Pagamentos Automáticos, os payloads de mensagem que trafegam tanto por parte da instituição iniciadora de transação de pagamento quanto por parte da instituição detentora de conta devem estar assinados.  Para o processo de assinatura destes payloads, as instituições devem seguir as especificações de segurança publicadas no Portal do desenvolvedor.  ## Controle de acesso - Os endpoints de consulta de pagamentos GET /pix/recurring-payments/{recurringPaymentId} e GET /pix/recurring-payments devem suportar acesso a partir de access_token emitido por meio de um grant_type do tipo client credentials, como opção do uso do token vinculado ao consentimento (hybrid flow). - Para evitar vazamento de informação, a detentora deve validar que o pagamento consultado pertence ao ClientId que o criou e, caso haja divergências, retorne um erro HTTP 400.  ## Aprovações de múltipla alçada  Todas as aprovações devem ser realizadas até a data/hora limite suportada pela detentora e em tempo hábil para realizar o primeiro pagamento.  ## Validações da edição do consentimento recorrente para o produto Pix Automático  - Para permitir a edição dos campos de um consentimento na iniciadora sem que se faça necessário o redirecionamento para o ambiente da detentora de conta, é necessário o envio de indicadores de risco.  Esta medida visa proporcionar à detentora de conta as informações necessárias para decidir sobre os ajustes no consentimento de forma segura. - Além disso, para permitir o correto entendimento da transação pela detentora, o endpoint dedicado a isso (PATCH /recurring-consents/{recurringConsentId}) possui características de obrigatoriedade de campos, na sua requisição, diferentes do endpoint de criação de consentimentos. Durante a edição do consentimento, a instituição iniciadora deverá informar todos os campos que foram marcados como obrigatórios e também os campos que não deseja alterar(enviando o valor atual do campo), também podendo altera-los, se assim desejar. - Caso a requisição seja para tornar o prazo de expiração do consentimento e/ou o valor máximo do pagamento variável indeterminados, estes devem ser omitidos da requisição, e todos os outros campos devem ser informados. - Caso o prazo de expiração e/ou o valor máximo do pagamento variável já sejam indeterminados, estes não devem ser informados, mantendo seu valor atual. - Detalhes dos cenários e campos que podem ser editados podem ser encontrados na página de [Edição do consentimento - [SV] Pagamentos Automáticos](https://openfinancebrasil.atlassian.net/wiki/spaces/OF/pages/718405977) - Se a edição do consentimento for realizada via iniciador (chamando o endpoint PATCH /recurring-consents/{recurringConsentId}) o detentor deverá validar se o usuário solicitante possui poderes plenos sobre o consentimento, isso significa que a alteração realizada por ele não precisará passar por outros aprovadores.  ## Validações para pagamentos recorrentes  - Caso o usuário pagador tenha agendado recorrências para os dias 29, 30 ou 31 de cada mês e o dia previsto não exista no respectivo mês, o iniciador deve enviar a ordem de pagamento para liquidação com o endToEndId representando o dia seguinte à data prevista para a liquidação.  Se identificado pelo detentor que a data enviada no endToEndId corresponde a um dia inexistente, ele deve rejeitar o pagamento - Caso a data de liquidação do pagamento seja superior ao prazo de expiração do consentimento, a solicitação deve ser rejeitada com o motivo FORA_PRAZO_PERMITIDO e, no detalhe, uma descrição da causa. - Para permitir a segunda janela de liquidação para os pagamentos já agendados no detentor, os consentimentos de Pix Automático (\"automatic\" selecionado no oneOf \"/data/recurringConfiguration/\"), precisam obrigatoriamente ter o horário de expiração do consentimento definido para 23:59:59h (UTC).  ## Validações  Durante a jornada de iniciação de pagamento, diferentes validações são necessárias pela instituição detentora de conta e devem ocorrer conforme a seguir:  1. **Validações na criação do consentimento de longa duração (_POST /recurring-consents_)**     1.1 **Orientações Iniciais**       &ensp;1.1.1 Não devem ser retornadas na resposta deste endpoint informações associadas ao usuário/cliente (ex. insuficiência de saldo, conta inexistente/bloqueada).       &ensp;1.1.2 Não devem ser realizadas validações de informações sobre o usuário/cliente durante a criação do consentimento.     1.2 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token, jwt, assinatura)**       &ensp;1.2.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);       &ensp;1.2.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);       &ensp;1.2.3 Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);       &ensp;1.2.4 Validação de Claims (exceto data);         &emsp;1.2.4.1 Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 – (INVALID_CLIENT);         &emsp;1.2.4.2 Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).     1.3 **Casos de erro sintáticos e semânticos, previstos com retorno HTTP Code 422 - Unprocessable Entity (detalhamento adicional na documentação técnica da API):**        &ensp;1.3.1 **Sintáticos**         &emsp;1.3.1.1 Envio de campos obrigatórios: Valida se todos os campos obrigatórios foram informados (PARAMETRO_NAO_INFORMADO);         &emsp;1.3.1.2 Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).       &ensp;1.3.2 **Semânticos**         &emsp;1.3.2.1 Data de pagamento: Valida se a data de pagamento enviada é válida para a forma de pagamento selecionada (DATA_PAGAMENTO_INVALIDA);         &emsp;1.3.2.2 Detalhes do pagamento: Valida se determinado parâmetro informado obedece às regras de negócio (DETALHE_PAGAMENTO_INVALIDO);         &emsp;1.3.2.3 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);         &emsp;1.3.2.4 Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);         &emsp;1.3.2.5 Funcionalidade não habilitada: A detentora de conta não oferece o serviço nessa modalidade (FUNCIONALIDADE_NAO_HABILITADA).    2. **Demais validações executadas durante o processamento assíncrono do consentimento pela detentora poderão ser consultados pela iniciadora através do endpoint GET /recurring-consents/{recurringConsentId} previstos com retorno HTTP Code 200 – OK com status REJECTED e rejectionReason conforme abaixo:**     2.1 **Validações durante o processamento assíncrono do consentimento**       &ensp;2.1.1 Falha de infraestrutura: Ocorreu algum erro interno na detentora durante processamento da criação do consentimento (FALHA_INFRAESTRUTURA);       &ensp;2.1.2 Tempo de autorização expirado: O usuário não confirmou o consentimento e o mesmo expirou (TEMPO_EXPIRADO_AUTORIZACAO);       &ensp;2.1.3 Rejeitado pelo usuário: O usuário explicitamente rejeitou a autorização do consentimento (REJEITADO_USUARIO);       &ensp;2.1.4 Mesma conta origem/destino: A conta indicada pelo usuário para recebimento é a mesma selecionada para o pagamento (CONTAS_ORIGEM_DESTINO_IGUAIS);       &ensp;2.1.5 Tipo de conta inválida: A conta indicada não permite operações de pagamento (CONTA_NAO_PERMITE_PAGAMENTO);       &ensp;2.1.6 Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento (SALDO_INSUFICIENTE);       &ensp;2.1.7 Limites da transação: Valida se o valor ultrapassa o limite estabelecido [na instituição/no arranjo/outro] para permitir a realização de transações pelo cliente (VALOR_ACIMA_LIMITE);       &ensp;2.1.8 Autenticação divergente: O usuário autenticado no ambiente da detentora não é o mesmo usuário autenticado no ambiente da iniciadora e que criou o consentimento. (AUTENTICACAO _DIVERGENTE);    3. **Demais validações executadas durante o processamento assíncrono do consentimento pela detentora, poderão ser consultados pela iniciadora através dos endpoints GET /recurring-consents/{recurringConsentId} previstos com retorno HTTP Code 200 - OK com status REVOKED e revocationReason conforme abaixo (detalhamento adicional na documentação técnica da API).**     3.1 **Demais validações durante o processamento assíncrono:**       &ensp;3.1.1 Nao informado: Validações não explicitamente informadas (ex. suspeita de fraude) (NAO_INFORMADO);       &ensp;3.1.2 Revogado pelo recebedor: O usuário recebedor solicitou explicitamente ao iniciador a revogação do consentimento (ex: término de contrato) (REVOGADO_RECEBEDOR);       &ensp;3.1.3 Revogado pelo pagador: O usuário pagador solicitou explicitamente a revogação do consentimento (REVOGADO_USUARIO).    4. **Validações na criação do pagamento - Síncrono (_POST /pix/recurring-payments_)**     4.1 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token, jwt, assinatura)**       &ensp;4.1.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);       &ensp;4.1.2 Validação de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED);       &ensp;4.1.3 Validação de assinatura da mensagem: Valida se assinatura das mensagens enviadas está correta – HTTP Code 400 (BAD_SIGNATURE);       &ensp;4.1.4 Validação de Claims (exceto data);         &emsp;4.1.4.1 Valida se dados (aud, iss, iat e jti) são válidos - HTTP status code 403 – (INVALID_CLIENT);         &emsp;4.1.4.2 Valida reuso de jti - HTTP Code 403 (INVALID_CLIENT).       &ensp;4.1.5 Detalhe tentativa inválida: Valida se os parâmetros informados condizem com a tentativa original de pagamento (DETALHE_TENTATIVA_INVALIDO).     4.2 **Casos de erro sintáticos e semânticos, previstos com retorno HTTP Code 422 - Unprocessable Entity (detalhamento adicional na documentação técnica da API):**       &ensp;4.2.1 Sintáticos         &emsp;4.2.1.1 Envio de campos obrigatórios: Valida se todos os campos obrigatórios são informados (PARAMETRO_NAO_INFORMADO);         &emsp;4.2.1.2 Formatação de parâmetros: Valida se parâmetros informados obedecem a formatação especificada (PARAMETRO_INVALIDO).       &ensp;4.2.2 Semânticos         &emsp;4.2.2.1 Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento (SALDO_INSUFICIENTE);         &emsp;4.2.2.2 Limites da transação: Valida se o valor ultrapassa o limite estabelecido [na instituição (conta ou canal)/no arranjo] para permitir a realização de transações pelo cliente (VALOR_ACIMA_LIMITE);         &emsp;4.2.2.3 Valor informado: Valida se valor enviado é válido para o consentimento associado ao pagamento (VALOR_INVALIDO);         &emsp;4.2.2.4 Status Consentimento: Valida se o consentimento encontra-se em um dos estados finais “CONSUMED”, “REVOKED” ou “REJECTED\" (CONSENTIMENTO_INVALIDO);          &emsp;4.2.2.5 Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);         &emsp;4.2.2.6 Divergência entre pagamento e consentimento: Valida se dados do pagamento são diferentes dos dados do consentimento (PAGAMENTO_DIVERGENTE_CONSENTIMENTO)         &emsp;4.2.2.7 Recusado pela detentora: Valida se pagamento foi recusado pela detentora (PAGAMENTO_RECUSADO_DETENTORA), com a descrição do motivo de recusa;         &emsp;4.2.2.8 Detalhes do pagamento: Valida se determinado parâmetro informado obedece as regras de negócio (DETALHE_PAGAMENTO_INVALIDO);         &emsp;4.2.2.9 Pagamento recusado no Sistema de Pagamentos Instantâneos (SPI) (PAGAMENTO_RECUSADO_SPI);         &emsp;4.2.2.10 Idempotência: Valida se há divergência entre chave de idempotência e informações enviadas (ERRO_IDEMPOTENCIA);         &emsp;4.2.2.11 Limite valor excedido por período: Foi atingido o valor limite permitido pelo usuário por um determinado período de tempo no consentimento do pagamento (LIMITE_PERIODO_VALOR_EXCEDIDO);         &emsp;4.2.2.12 Limite quantidade excedida por período: A quantidade de cobranças atingiu o limite determinado pelo usuário na criação do consentimento (LIMITE_PERIODO_QUANTIDADE_EXCEDIDO);          &emsp;4.2.2.13 Consentimento pendente de autorização: Consentimento em “PARTIALLY_ACCEPTED” aguardando aprovação de múltiplas alçadas (CONSENTIMENTO_PENDENTE_AUTORIZACAO);         &emsp;4.2.2.14 Limite global excedido: O consentimento encontra-se autorizado e o valor solicitado para cobrança ultrapassa a faixa de limite global parametrizado pelo usuário durante a criação do consentimento (LIMITE_VALOR_TOTAL_CONSENTIMENTO_EXCEDIDO);       &emsp;4.2.2.15 Limite de transação excedido: O consentimento encontra-se autorizado e o valor solicitado para cobrança ultrapassa a faixa de limite de valor por transação parametrizado pelo usuário na criação do consentimento (LIMITE_VALOR_TRANSACAO_CONSENTIMENTO_EXCEDIDO);         &emsp;4.2.2.16 Limite de retentativas atingido: Valida se todas as tentativas de liquidação permitidas já foram realizadas (LIMITE_TENTATIVA_EXCEDIDO);         &emsp;4.2.2.17 Fora do prazo permitido: A tentativa de agendamento foi realizada fora do horário ou período permitido e não pode ser aceita pela instituição detentora (FORA_PRAZO_PERMITIDO).  5. **Validações na consulta do pagamento (_GET /pix/recurring-payments/{recurringPaymentId}_ e _GET /pix/recurring-payments_)**     5.1 **Casos de erro relacionados às permissões de segurança para acesso à API (ex. certificado, access_token)**       &ensp;5.1.1 Validação de Certificado: Valida utilização de certificado correto durante processo de DCR - HTTP Code 401 (INVALID_CLIENT);       &ensp;5.1.2 Validações de Access_Token: Verifica se Access_Token utilizado está correto - HTTP Code 401 (UNAUTHORIZED).  6. **Demais validações executadas durante o processamento assíncrono do pagamento pela detentora, poderão ser consultados pela iniciadora através dos endpoints _GET /pix/recurring-payments/{recurringPaymentId}_ e _GET /pix/recurring-payments_ previstos com retorno HTTP Code 200 - OK com status RJCT (Rejected) e rejectionReason conforme abaixo (detalhamento adicional na documentação técnica da API):**     6.1 **Demais validações durante o processamento assíncrono:**       &ensp;6.1.1 - Saldo do usuário: Valida se a conta selecionada possui saldo suficiente para realizar o pagamento (SALDO_INSUFICIENTE);       &ensp;6.1.2 - Limites da transação: Valida se o valor ultrapassa o limite estabelecido [na instituição (conta ou canal)/no arranjo] para permitir a realização de transações pelo cliente (VALOR_ACIMA_LIMITE);       &ensp;6.1.3 - Valor informado: Valida se valor enviado é válido para o consentimento do pagamento (VALOR_INVALIDO);       &ensp;6.1.4 - Demais validações não explicitamente informadas (ex. suspeita de fraude): (NAO_INFORMADO);       &ensp;6.1.5 - Divergência entre pagamento e consentimento: Valida se dados do pagamento são diferentes dos dados do consentimento (PAGAMENTO_DIVERGENTE_CONSENTIMENTO);       &ensp;6.1.6 - Recusado pela detentora: Valida se pagamento foi recusado pela detentora (PAGAMENTO_RECUSADO_DETENTORA), com a descrição do motivo de recusa;       &ensp;6.1.7 - Pagamento recusado no Sistema de Pagamentos Instantâneos (SPI) (PAGAMENTO_RECUSADO_SPI);       &ensp;6.1.8 - Erro de infraestrutura na consulta ao SPI: Ocorreu uma falha de infraestrutura durante a consulta ao SPI(FALHA_INFRAESTRUTURA_SPI);       &ensp;6.1.9 - Erro de infraestrutura na consulta ao ICP: Ocorreu uma falha de infraestrutura durante a consulta ao ICP (FALHA_INFRAESTRUTURA_ICP);       &ensp;6.1.10 - Erro de infraestrutura na comunicação com o PSP do recebedor: Ocorreu uma falha de infraestrutura durante a comunicação com o PSP do recebedor (FALHA_INFRAESTRUTURA_PSP_RECEBEDOR);       &ensp;6.1.11 - Erro de infraestrutura interno na detentora: Ocorreu uma falha de infraestrutura interna na detentora durante o processamento do pagamento (FALHA_INFRAESTRUTURA_DETENTORA);       &ensp;6.1.12 - Status Consentimento: Valida se o consentimento encontra-se em um dos estados finais “CONSUMED”, “REVOKED” ou “REJECTED\" (CONSENTIMENTO_INVALIDO);       &ensp;6.1.13 - Limite valor excedido por período: Foi atingido o valor limite permitido pelo usuário por um determinado período de tempo no consentimento do pagamento (LIMITE_PERIODO_VALOR_EXCEDIDO);       &ensp;6.1.14 - Limite quantidade excedida por período: A quantidade de cobranças atingiu o limite determinado pelo usuário na criação do consentimento (LIMITE_PERIODO_QUANTIDADE_EXCEDIDO);       &ensp;6.1.15 - Titularidade Inconsistente: Conta atualmente não associada ao CPF/CNPJ do consentimento de longa duração. Caso a liquidação seja negada pelo PSP Recebedor com erro BE01, cabe a detentora de conta mudar o status do pagamento para RJCT com essa reason (TITULARIDADE_INCONSISTENTE);       &ensp;6.1.16 - Consentimento revogado: O pagamento estava associado a um consentimento que foi revogado (CONSENTIMENTO_REVOGADO);       &ensp;6.1.17 - Limite global excedido: O consentimento encontrasse autorizado e o valor solicitado para cobrança ultrapassa a faixa de limite global parametrizado pelo usuário durante a criação do consentimento (LIMITE_VALOR_TOTAL_CONSENTIMENTO_EXCEDIDO);       &ensp;6.1.18 - Limite de transação excedido: O consentimento encontra-se autorizado e o valor solicitado para cobrança ultrapassa a faixa de limite de valor por transação parametrizado pelo usuário na criação do consentimento (LIMITE_VALOR_TRANSACAO_CONSENTIMENTO_EXCEDIDO);       &ensp;6.1.19 Limite de retentativas atingido: Valida se todas as tentativas de liquidação permitidas já foram realizadas (LIMITE_TENTATIVA_EXCEDIDO);       &ensp;6.1.20 Fora do prazo permitido: A tentativa de agendamento foi realizada fora do horário ou período permitido e não pode ser aceita pela instituição detentora (FORA_PRAZO_PERMITIDO);       &ensp;6.1.21 Detalhe tentativa inválida: Valida se os parâmetros informados condizem com a tentativa original de pagamento (DETALHE_TENTATIVA_INVALIDO);       &ensp;6.1.22 Detalhes do pagamento: Valida se determinado parâmetro informado obedece as regras de negócio (DETALHE_PAGAMENTO_INVALIDO).  ## Validações antifraude da Transferências Inteligentes  - Afim de garantir a mesma titularidade e aumentar a segurança das transações do produto Transferências Inteligentes, as validações abaixo poderão ser realizadas pela detetora de conta e pela iniciadora, quando localinstrument for igual a DICT ou INIC. A detentora PODE usar a API Consultar Vinculo (DICT API) do arranjo Pix e validar no momento de transação ao menos os atributos abaixo mencionados:   - se o valor dos atributos de fraude abaixo são iguais a 0, de modo a evitar que contas criadas especificamente para uso indevido da Transferências Inteligentes impactem o ecossistema      - OwnerStatistics.Spi.FraudMarkers.ApplicationFrauds.d90     - OwnerStatistics.Spi.FraudMarkers.MuleAccounts.d90     - OwnerStatistics.Spi.FraudMarkers.ScammerAccounts.d90     - OwnerStatistics.Spi.FraudMarkers.OtherFrauds.d90     - OwnerStatistics.Spi.FraudMarkers.UnknownFrauds.d90  ## Limites transacionais e crédito pré-aprovado para Transferências inteligentes  - Existem três tipos de limites para o produto Transferências inteligentes   - Crédito pré-aprovado (cheque especial): Caso o cliente possua o produto, poderá utilizá-lo durante as transações associadas ao produto Transferências inteligentes.   - Limite do Pix atrelado à conta do cliente: Limite de transações definido individualmente para cada conta do cliente, conforme regras de dias e horários do arranjo Pix.   - Limites do consentimento: Configurado ou não pelo cliente em momento de criação do consentimento, podendo ser dependente ou não de um período. - O cálculo do limite periódico disponível ao cliente deve ocorrer da seguinte maneira, considerando os cenários, exemplos e o horário de Brasília:   - Limite Diário (Ex.: R$ 100,00): Este limite controla as transferências realizadas dentro de um único dia, considerando o período das 00:00h até as 23:59h. Por exemplo, se um usuário transferir R$ 50,00 às 10:00h, ele ainda terá R$ 50,00 disponíveis para transferências até a meia-noite do mesmo dia;   - Limite Semanal (Ex.: R$ 1.000,00): O limite semanal abrange o período de uma semana inteira, começando às 00:00h de domingo e terminando às 23:59h do sábado. Por exemplo, se um usuário transferir R$ 200,00 na terça-feira e R$ 500,00 na quinta-feira, ele ainda poderá transferir até R$ 300,00 até o final do sábado;   - Limite Mensal (Ex.: R$ 10.000,00): Este limite mensal é calculado do primeiro ao último dia de cada mês. Por exemplo, em um mês, se o usuário transferir R$ 2.000,00 na primeira semana e R$ 3.000,00 na segunda semana, ele ainda terá R$ 5.000,00 disponíveis para transferências pelo restante do mês;   - Limite Anual (Ex.: R$ 50.000,00): O limite anual conta do primeiro dia de janeiro ao último dia de dezembro. Por exemplo, se um usuário transferir R$ 10.000,00 até março, mais R$ 15.000,00 até junho e mais R$ 20.000,00 até setembro, ele só poderá transferir outros R$ 5.000,00 até o final do ano;   - Esses limites ajudam a gerenciar as transferências de fundos, garantindo que não excedam os montantes estabelecidos para cada período. Cada limite é independente e é recalculado conforme sua respectiva janela de tempo se reinicia. Todos os pagamentos com status diferente de RJCT ou CANC devem ser considerados para o cálculo dos limites do consentimento.  ## Sobre a quantidade de consentimentos que podem ser criados Não há limitações relacionadas a quantidade de consentimentos que podem ser criados entre uma mesma ITP, conta de débito e titularidade. Fica a cargo da instituição iniciadora solicitar, sempre que julgar necessário para atendimento do usuário, a criação de um novo consentimento.  ## Sobre o cancelamento de novas tentativas de pagamento para o Pix Automático Não é permitido ao usuário pagador o cancelamento de uma nova tentativa de pagamento, realizada pelo recebedor, quando autorizado no consentimento (campo “/data/recurringConfiguration/automatic/isRetryAccepted” como “True”) pelo usuário pagador ou quando o Iniciador precisar enviar um novo endToEndId.  Aplica-se tanto para a tentativa intradia quanto para a tentativa em dias subsequentes. É permitido ao recebedor o cancelamento das novas tentativas em dias subsequentes. Aplicam-se as regras de cancelamento da tentativa original, conforme previsto na descrição do endpoint PATCH /pix/recurringPayments Pagamentos que são novas tentativas, intradia ou em dias subsequentes, podem ser identificados pela presença do campo “/data/originalRecurringPaymentId” no recurso.
 *
 * The version of the OpenAPI document: 2.0.0
 * Contact: gt-interfaces@openbankingbr.org
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.17.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\FormDataProcessor;
use OpenAPI\Client\HeaderSelector;
use OpenAPI\Client\ObjectSerializer;

/**
 * RecurringPaymentsApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class RecurringPaymentsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'automaticPaymentsGetPixRecurringPayments' => [
            'application/json',
        ],
        'automaticPaymentsGetPixRecurringPaymentsPaymentId' => [
            'application/json',
        ],
        'automaticPaymentsPatchPixRecurringPaymentsPaymentId' => [
            'application/jwt',
        ],
        'automaticPaymentsPostPixRecurringPayments' => [
            'application/jwt',
        ],
    ];

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?Configuration $config = null,
        ?HeaderSelector $selector = null,
        int $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: Configuration::getDefaultConfiguration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPayments
     *
     * Busca informações de transações de pagamentos associadas a um consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_consent_id O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string|null $start_date Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $end_date Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $original_recurring_payment_id Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseRecurringPixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function automaticPaymentsGetPixRecurringPayments($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $start_date = null, $end_date = null, $original_recurring_payment_id = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPayments'][0])
    {
        list($response) = $this->automaticPaymentsGetPixRecurringPaymentsWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $start_date, $end_date, $original_recurring_payment_id, $contentType);
        return $response;
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsWithHttpInfo
     *
     * Busca informações de transações de pagamentos associadas a um consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_consent_id O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string|null $start_date Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $end_date Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $original_recurring_payment_id Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseRecurringPixPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function automaticPaymentsGetPixRecurringPaymentsWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $start_date = null, $end_date = null, $original_recurring_payment_id = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPayments'][0])
    {
        $request = $this->automaticPaymentsGetPixRecurringPaymentsRequest($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $start_date, $end_date, $original_recurring_payment_id, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseRecurringPixPayment',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseRecurringPixPayment',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseRecurringPixPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsAsync
     *
     * Busca informações de transações de pagamentos associadas a um consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_consent_id O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string|null $start_date Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $end_date Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $original_recurring_payment_id Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsGetPixRecurringPaymentsAsync($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $start_date = null, $end_date = null, $original_recurring_payment_id = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPayments'][0])
    {
        return $this->automaticPaymentsGetPixRecurringPaymentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $start_date, $end_date, $original_recurring_payment_id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsAsyncWithHttpInfo
     *
     * Busca informações de transações de pagamentos associadas a um consentimento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_consent_id O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string|null $start_date Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $end_date Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $original_recurring_payment_id Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsGetPixRecurringPaymentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $start_date = null, $end_date = null, $original_recurring_payment_id = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPayments'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseRecurringPixPayment';
        $request = $this->automaticPaymentsGetPixRecurringPaymentsRequest($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $start_date, $end_date, $original_recurring_payment_id, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'automaticPaymentsGetPixRecurringPayments'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_consent_id O &#x60;recurringConsentId&#x60; é o identificador único do consentimento de longa duração e deverá ser um URN - Uniform Resource Name.   Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource  Identifier - URI - que é atribuído sob o URI scheme \&quot;urn\&quot; e um namespace URN específico, com a intenção de que o URN  seja um identificador de recurso persistente e independe da localização.   Considerando a string urn:bancoex:C1DD33123 como exemplo para &#x60;recurringConsentId&#x60; temos: - o namespace(urn) - o identificador associado ao namespace da instituição detentora (bancoex). - o identificador específico dentro do namespace (C1DD33123).   Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141). (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string|null $start_date Data inicial de corte da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $end_date Data final de corte para recuperação da ocorrência do pagamento ligada ao consentimento de longa duração. (optional)
     * @param  string|null $original_recurring_payment_id Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  Código ou identificador único criado pela instituição detentora da conta para representar a iniciação de pagamento.  Caso informado, devem ser retornados todos os pagamentos associados ao identificador informado, sendo eles o pagamento original (dono do identificador) e as novas tentativas que enviaram o identificador na sua requisição, indicando que representam nova tentativa. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function automaticPaymentsGetPixRecurringPaymentsRequest($authorization, $x_fapi_interaction_id, $recurring_consent_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, $start_date = null, $end_date = null, $original_recurring_payment_id = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPayments'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling automaticPaymentsGetPixRecurringPayments'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling automaticPaymentsGetPixRecurringPayments'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'recurring_consent_id' is set
        if ($recurring_consent_id === null || (is_array($recurring_consent_id) && count($recurring_consent_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $recurring_consent_id when calling automaticPaymentsGetPixRecurringPayments'
            );
        }
        if (strlen($recurring_consent_id) > 256) {
            throw new \InvalidArgumentException('invalid length for "$recurring_consent_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 256.');
        }
        if (strlen($recurring_consent_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$recurring_consent_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $recurring_consent_id)) {
            throw new \InvalidArgumentException("invalid value for \"recurring_consent_id\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($start_date !== null && strlen($start_date) > 10) {
            throw new \InvalidArgumentException('invalid length for "$start_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 10.');
        }
        if ($start_date !== null && strlen($start_date) < 10) {
            throw new \InvalidArgumentException('invalid length for "$start_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 10.');
        }
        if ($start_date !== null && !preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", $start_date)) {
            throw new \InvalidArgumentException("invalid value for \"start_date\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.");
        }
        
        if ($end_date !== null && strlen($end_date) > 10) {
            throw new \InvalidArgumentException('invalid length for "$end_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 10.');
        }
        if ($end_date !== null && strlen($end_date) < 10) {
            throw new \InvalidArgumentException('invalid length for "$end_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 10.');
        }
        if ($end_date !== null && !preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", $end_date)) {
            throw new \InvalidArgumentException("invalid value for \"end_date\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.");
        }
        
        if ($original_recurring_payment_id !== null && strlen($original_recurring_payment_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$original_recurring_payment_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be smaller than or equal to 100.');
        }
        if ($original_recurring_payment_id !== null && strlen($original_recurring_payment_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$original_recurring_payment_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if ($original_recurring_payment_id !== null && !preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $original_recurring_payment_id)) {
            throw new \InvalidArgumentException("invalid value for \"original_recurring_payment_id\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPayments, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        

        $resourcePath = '/pix/recurring-payments';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $recurring_consent_id,
            'recurringConsentId', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $start_date,
            'startDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $end_date,
            'endDate', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $original_recurring_payment_id,
            'originalRecurringPaymentId', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);

        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsPaymentId
     *
     * Busca informações de uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function automaticPaymentsGetPixRecurringPaymentsPaymentId($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'][0])
    {
        list($response) = $this->automaticPaymentsGetPixRecurringPaymentsPaymentIdWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);
        return $response;
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsPaymentIdWithHttpInfo
     *
     * Busca informações de uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function automaticPaymentsGetPixRecurringPaymentsPaymentIdWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'][0])
    {
        $request = $this->automaticPaymentsGetPixRecurringPaymentsPaymentIdRequest($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsPaymentIdAsync
     *
     * Busca informações de uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsGetPixRecurringPaymentsPaymentIdAsync($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'][0])
    {
        return $this->automaticPaymentsGetPixRecurringPaymentsPaymentIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation automaticPaymentsGetPixRecurringPaymentsPaymentIdAsyncWithHttpInfo
     *
     * Busca informações de uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsGetPixRecurringPaymentsPaymentIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdRead';
        $request = $this->automaticPaymentsGetPixRecurringPaymentsPaymentIdRequest($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'automaticPaymentsGetPixRecurringPaymentsPaymentId'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function automaticPaymentsGetPixRecurringPaymentsPaymentIdRequest($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsGetPixRecurringPaymentsPaymentId'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling automaticPaymentsGetPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling automaticPaymentsGetPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'recurring_payment_id' is set
        if ($recurring_payment_id === null || (is_array($recurring_payment_id) && count($recurring_payment_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $recurring_payment_id when calling automaticPaymentsGetPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($recurring_payment_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$recurring_payment_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if (strlen($recurring_payment_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$recurring_payment_id" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $recurring_payment_id)) {
            throw new \InvalidArgumentException("invalid value for \"recurring_payment_id\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling RecurringPaymentsApi.automaticPaymentsGetPixRecurringPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/recurring-payments/{recurringPaymentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }

        // path params
        if ($recurring_payment_id !== null) {
            $resourcePath = str_replace(
                '{' . 'recurringPaymentId' . '}',
                ObjectSerializer::toPathValue($recurring_payment_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation automaticPaymentsPatchPixRecurringPaymentsPaymentId
     *
     * Cancelamento de solicitação de pagamento automático.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Atualização do Pagamento Recorrente. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreateRecurringPaymentsPaymentId|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function automaticPaymentsPatchPixRecurringPaymentsPaymentId($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'][0])
    {
        list($response) = $this->automaticPaymentsPatchPixRecurringPaymentsPaymentIdWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);
        return $response;
    }

    /**
     * Operation automaticPaymentsPatchPixRecurringPaymentsPaymentIdWithHttpInfo
     *
     * Cancelamento de solicitação de pagamento automático.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Atualização do Pagamento Recorrente. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreateRecurringPaymentsPaymentId|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function automaticPaymentsPatchPixRecurringPaymentsPaymentIdWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'][0])
    {
        $request = $this->automaticPaymentsPatchPixRecurringPaymentsPaymentIdRequest($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreateRecurringPaymentsPaymentId',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreateRecurringPaymentsPaymentId',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation automaticPaymentsPatchPixRecurringPaymentsPaymentIdAsync
     *
     * Cancelamento de solicitação de pagamento automático.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Atualização do Pagamento Recorrente. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsPatchPixRecurringPaymentsPaymentIdAsync($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'][0])
    {
        return $this->automaticPaymentsPatchPixRecurringPaymentsPaymentIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation automaticPaymentsPatchPixRecurringPaymentsPaymentIdAsyncWithHttpInfo
     *
     * Cancelamento de solicitação de pagamento automático.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Atualização do Pagamento Recorrente. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsPatchPixRecurringPaymentsPaymentIdAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPatch';
        $request = $this->automaticPaymentsPatchPixRecurringPaymentsPaymentIdRequest($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'automaticPaymentsPatchPixRecurringPaymentsPaymentId'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $recurring_payment_id Identificador da operação de pagamento. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\PatchPixPayment $patch_pix_payment Atualização do Pagamento Recorrente. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function automaticPaymentsPatchPixRecurringPaymentsPaymentIdRequest($authorization, $x_fapi_interaction_id, $recurring_payment_id, $x_idempotency_key, $patch_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPatchPixRecurringPaymentsPaymentId'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling automaticPaymentsPatchPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling automaticPaymentsPatchPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'recurring_payment_id' is set
        if ($recurring_payment_id === null || (is_array($recurring_payment_id) && count($recurring_payment_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $recurring_payment_id when calling automaticPaymentsPatchPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($recurring_payment_id) > 100) {
            throw new \InvalidArgumentException('invalid length for "$recurring_payment_id" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if (strlen($recurring_payment_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$recurring_payment_id" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $recurring_payment_id)) {
            throw new \InvalidArgumentException("invalid value for \"recurring_payment_id\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }
        
        // verify the required parameter 'x_idempotency_key' is set
        if ($x_idempotency_key === null || (is_array($x_idempotency_key) && count($x_idempotency_key) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_idempotency_key when calling automaticPaymentsPatchPixRecurringPaymentsPaymentId'
            );
        }
        if (strlen($x_idempotency_key) > 40) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 40.');
        }
        if (strlen($x_idempotency_key) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^(?!\\s)(.*)(\\S)$/", $x_idempotency_key)) {
            throw new \InvalidArgumentException("invalid value for \"x_idempotency_key\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /^(?!\\s)(.*)(\\S)$/.");
        }
        
        // verify the required parameter 'patch_pix_payment' is set
        if ($patch_pix_payment === null || (is_array($patch_pix_payment) && count($patch_pix_payment) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $patch_pix_payment when calling automaticPaymentsPatchPixRecurringPaymentsPaymentId'
            );
        }

        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling RecurringPaymentsApi.automaticPaymentsPatchPixRecurringPaymentsPaymentId, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/recurring-payments/{recurringPaymentId}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_idempotency_key !== null) {
            $headerParams['x-idempotency-key'] = ObjectSerializer::toHeaderValue($x_idempotency_key);
        }

        // path params
        if ($recurring_payment_id !== null) {
            $resourcePath = str_replace(
                '{' . 'recurringPaymentId' . '}',
                ObjectSerializer::toPathValue($recurring_payment_id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($patch_pix_payment)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($patch_pix_payment));
            } else {
                $httpBody = $patch_pix_payment;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'PATCH',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation automaticPaymentsPostPixRecurringPayments
     *
     * Cria uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreateRecurringPixPayment $create_recurring_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPostPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixRecurringPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError
     */
    public function automaticPaymentsPostPixRecurringPayments($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPostPixRecurringPayments'][0])
    {
        list($response) = $this->automaticPaymentsPostPixRecurringPaymentsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);
        return $response;
    }

    /**
     * Operation automaticPaymentsPostPixRecurringPaymentsWithHttpInfo
     *
     * Cria uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreateRecurringPixPayment $create_recurring_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPostPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\Model422ResponseErrorCreatePixRecurringPayment|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError|\OpenAPI\Client\Model\ResponseError, HTTP status code, HTTP response headers (array of strings)
     */
    public function automaticPaymentsPostPixRecurringPaymentsWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPostPixRecurringPayments'][0])
    {
        $request = $this->automaticPaymentsPostPixRecurringPaymentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    null,
                    null
                );
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 201:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 405:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 406:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 422:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixRecurringPayment',
                        $request,
                        $response,
                    );
                case 500:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 504:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                case 529:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
                default:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\ResponseError',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 405:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 406:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 422:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Model422ResponseErrorCreatePixRecurringPayment',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 504:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 529:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                default:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\ResponseError',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation automaticPaymentsPostPixRecurringPaymentsAsync
     *
     * Cria uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreateRecurringPixPayment $create_recurring_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPostPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsPostPixRecurringPaymentsAsync($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPostPixRecurringPayments'][0])
    {
        return $this->automaticPaymentsPostPixRecurringPaymentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation automaticPaymentsPostPixRecurringPaymentsAsyncWithHttpInfo
     *
     * Cria uma transação de pagamento.
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreateRecurringPixPayment $create_recurring_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPostPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function automaticPaymentsPostPixRecurringPaymentsAsyncWithHttpInfo($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPostPixRecurringPayments'][0])
    {
        $returnType = '\OpenAPI\Client\Model\ResponseRecurringPaymentsIdPost';
        $request = $this->automaticPaymentsPostPixRecurringPaymentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent, $x_fapi_auth_date, $x_fapi_customer_ip_address, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'automaticPaymentsPostPixRecurringPayments'
     *
     * @param  string $authorization Cabeçalho HTTP padrão. Permite que as credenciais sejam fornecidas dependendo do tipo de recurso solicitado (required)
     * @param  string $x_fapi_interaction_id Um UUID [RFC4122](https://tools.ietf.org/html/rfc4122) usado como um ID de correlação entre request e response. Campo de geração e envio obrigatório pela iniciadora (client) e o seu valor deve ser “espelhado” pela detentora (server) no cabeçalho de resposta. Caso não seja recebido ou se for recebido um valor inválido, a detentora deve gerar um x-fapi-interaction-id e retorná-lo na resposta com o HTTP status code 400. A iniciadora deve acatar o valor recebido da detentora.. (required)
     * @param  string $x_idempotency_key Cabeçalho HTTP personalizado. Identificador de solicitação exclusivo para suportar a idempotência. (required)
     * @param  \OpenAPI\Client\Model\CreateRecurringPixPayment $create_recurring_pix_payment Payload para criação da iniciação do pagamento Pix. (required)
     * @param  string|null $x_customer_user_agent Indica o user-agent que o usuário utiliza. (optional)
     * @param  string|null $x_fapi_auth_date Data em que o usuário logou pela última vez com o iniciador. Representada de acordo com a [RFC7231](https://tools.ietf.org/html/rfc7231).Exemplo: Sun, 10 Sep 2017 19:43:31 UTC (optional)
     * @param  string|null $x_fapi_customer_ip_address O endereço IP do usuário se estiver atualmente logado com o iniciador. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['automaticPaymentsPostPixRecurringPayments'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function automaticPaymentsPostPixRecurringPaymentsRequest($authorization, $x_fapi_interaction_id, $x_idempotency_key, $create_recurring_pix_payment, $x_customer_user_agent = null, $x_fapi_auth_date = null, $x_fapi_customer_ip_address = null, string $contentType = self::contentTypes['automaticPaymentsPostPixRecurringPayments'][0])
    {

        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling automaticPaymentsPostPixRecurringPayments'
            );
        }
        if (strlen($authorization) > 2048) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be smaller than or equal to 2048.');
        }
        if (strlen($authorization) < 1) {
            throw new \InvalidArgumentException('invalid length for "$authorization" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/[\\w\\W\\s]*/", $authorization)) {
            throw new \InvalidArgumentException("invalid value for \"authorization\" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        // verify the required parameter 'x_fapi_interaction_id' is set
        if ($x_fapi_interaction_id === null || (is_array($x_fapi_interaction_id) && count($x_fapi_interaction_id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_fapi_interaction_id when calling automaticPaymentsPostPixRecurringPayments'
            );
        }
        if (strlen($x_fapi_interaction_id) > 36) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be smaller than or equal to 36.');
        }
        if (strlen($x_fapi_interaction_id) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_interaction_id" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/", $x_fapi_interaction_id)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_interaction_id\" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must conform to the pattern /^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/.");
        }
        
        // verify the required parameter 'x_idempotency_key' is set
        if ($x_idempotency_key === null || (is_array($x_idempotency_key) && count($x_idempotency_key) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $x_idempotency_key when calling automaticPaymentsPostPixRecurringPayments'
            );
        }
        if (strlen($x_idempotency_key) > 40) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be smaller than or equal to 40.');
        }
        if (strlen($x_idempotency_key) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_idempotency_key" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if (!preg_match("/^(?!\\s)(.*)(\\S)$/", $x_idempotency_key)) {
            throw new \InvalidArgumentException("invalid value for \"x_idempotency_key\" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must conform to the pattern /^(?!\\s)(.*)(\\S)$/.");
        }
        
        // verify the required parameter 'create_recurring_pix_payment' is set
        if ($create_recurring_pix_payment === null || (is_array($create_recurring_pix_payment) && count($create_recurring_pix_payment) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $create_recurring_pix_payment when calling automaticPaymentsPostPixRecurringPayments'
            );
        }

        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be smaller than or equal to 100.');
        }
        if ($x_customer_user_agent !== null && strlen($x_customer_user_agent) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_customer_user_agent" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if ($x_customer_user_agent !== null && !preg_match("/[\\w\\W\\s]*/", $x_customer_user_agent)) {
            throw new \InvalidArgumentException("invalid value for \"x_customer_user_agent\" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) > 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be smaller than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && strlen($x_fapi_auth_date) < 29) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_auth_date" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be bigger than or equal to 29.');
        }
        if ($x_fapi_auth_date !== null && !preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/", $x_fapi_auth_date)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_auth_date\" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must conform to the pattern /^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \\d{2} (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \\d{4} \\d{2}:\\d{2}:\\d{2} (GMT|UTC)$/.");
        }
        
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) > 100) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be smaller than or equal to 100.');
        }
        if ($x_fapi_customer_ip_address !== null && strlen($x_fapi_customer_ip_address) < 1) {
            throw new \InvalidArgumentException('invalid length for "$x_fapi_customer_ip_address" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must be bigger than or equal to 1.');
        }
        if ($x_fapi_customer_ip_address !== null && !preg_match("/[\\w\\W\\s]*/", $x_fapi_customer_ip_address)) {
            throw new \InvalidArgumentException("invalid value for \"x_fapi_customer_ip_address\" when calling RecurringPaymentsApi.automaticPaymentsPostPixRecurringPayments, must conform to the pattern /[\\w\\W\\s]*/.");
        }
        

        $resourcePath = '/pix/recurring-payments';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;


        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }
        // header params
        if ($x_customer_user_agent !== null) {
            $headerParams['x-customer-user-agent'] = ObjectSerializer::toHeaderValue($x_customer_user_agent);
        }
        // header params
        if ($x_fapi_auth_date !== null) {
            $headerParams['x-fapi-auth-date'] = ObjectSerializer::toHeaderValue($x_fapi_auth_date);
        }
        // header params
        if ($x_fapi_customer_ip_address !== null) {
            $headerParams['x-fapi-customer-ip-address'] = ObjectSerializer::toHeaderValue($x_fapi_customer_ip_address);
        }
        // header params
        if ($x_fapi_interaction_id !== null) {
            $headerParams['x-fapi-interaction-id'] = ObjectSerializer::toHeaderValue($x_fapi_interaction_id);
        }
        // header params
        if ($x_idempotency_key !== null) {
            $headerParams['x-idempotency-key'] = ObjectSerializer::toHeaderValue($x_idempotency_key);
        }



        $headers = $this->headerSelector->selectHeaders(
            ['application/jwt', 'application/json; charset=utf-8', 'application/json', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($create_recurring_pix_payment)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($create_recurring_pix_payment));
            } else {
                $httpBody = $create_recurring_pix_payment;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }

        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }
        // this endpoint requires OAuth (access token)
        if (!empty($this->config->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->config->getAccessToken();
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        if ($this->config->getCertFile()) {
            $options[RequestOptions::CERT] = $this->config->getCertFile();
        }

        if ($this->config->getKeyFile()) {
            $options[RequestOptions::SSL_KEY] = $this->config->getKeyFile();
        }

        return $options;
    }

    private function handleResponseWithDataType(
        string $dataType,
        RequestInterface $request,
        ResponseInterface $response
    ): array {
        if ($dataType === '\SplFileObject') {
            $content = $response->getBody(); //stream goes to serializer
        } else {
            $content = (string) $response->getBody();
            if ($dataType !== 'string') {
                try {
                    $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    throw new ApiException(
                        sprintf(
                            'Error JSON decoding server response (%s)',
                            $request->getUri()
                        ),
                        $response->getStatusCode(),
                        $response->getHeaders(),
                        $content
                    );
                }
            }
        }

        return [
            ObjectSerializer::deserialize($content, $dataType, []),
            $response->getStatusCode(),
            $response->getHeaders()
        ];
    }

    private function responseWithinRangeCode(
        string $rangeCode,
        int $statusCode
    ): bool {
        $left = (int) ($rangeCode[0].'00');
        $right = (int) ($rangeCode[0].'99');

        return $statusCode >= $left && $statusCode <= $right;
    }
}
