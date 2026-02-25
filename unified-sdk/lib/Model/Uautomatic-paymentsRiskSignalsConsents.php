<?php
/**
 * RiskSignalsConsents
 *
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

namespace OpenAPI\Client\Model;

use \ArrayAccess;
use \OpenAPI\Client\ObjectSerializer;

/**
 * RiskSignalsConsents Class Doc Comment
 *
 * @category Class
 * @description Sinais de risco para iniciação de pagamentos automáticos  [Restrição] Deve ser enviado quando o consentimento for para o produto Pix Automático (O objeto \&quot;/data/recurringConfiguration/automatic\&quot; usado no oneOf). Só estará presente após a primeira edição do consentimento de longa duração.
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Uautomatic-paymentsRiskSignalsConsents implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'RiskSignalsConsents';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'device_id' => 'string',
        'is_rooted_device' => 'bool',
        'screen_brightness' => 'float',
        'elapsed_time_since_boot' => 'int',
        'os_version' => 'string',
        'user_time_zone_offset' => 'string',
        'language' => 'string',
        'screen_dimensions' => '\OpenAPI\Client\Model\RiskSignalsPaymentsManualScreenDimensions',
        'account_tenure' => '\DateTime',
        'geolocation' => '\OpenAPI\Client\Model\RiskSignalsPaymentsManualGeolocation',
        'is_calling_progress' => 'bool',
        'is_dev_mode_enabled' => 'bool',
        'is_mock_gps' => 'bool',
        'is_emulated' => 'bool',
        'is_monkey_runner' => 'bool',
        'is_charging' => 'bool',
        'antenna_information' => 'string',
        'is_usb_connected' => 'bool',
        'integrity' => '\OpenAPI\Client\Model\RiskSignalsPaymentsManualIntegrity'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'device_id' => null,
        'is_rooted_device' => null,
        'screen_brightness' => 'double',
        'elapsed_time_since_boot' => 'int64',
        'os_version' => null,
        'user_time_zone_offset' => null,
        'language' => null,
        'screen_dimensions' => null,
        'account_tenure' => 'date',
        'geolocation' => null,
        'is_calling_progress' => null,
        'is_dev_mode_enabled' => null,
        'is_mock_gps' => null,
        'is_emulated' => null,
        'is_monkey_runner' => null,
        'is_charging' => null,
        'antenna_information' => null,
        'is_usb_connected' => null,
        'integrity' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'device_id' => false,
        'is_rooted_device' => false,
        'screen_brightness' => false,
        'elapsed_time_since_boot' => false,
        'os_version' => false,
        'user_time_zone_offset' => false,
        'language' => false,
        'screen_dimensions' => false,
        'account_tenure' => false,
        'geolocation' => false,
        'is_calling_progress' => false,
        'is_dev_mode_enabled' => false,
        'is_mock_gps' => false,
        'is_emulated' => false,
        'is_monkey_runner' => false,
        'is_charging' => false,
        'antenna_information' => false,
        'is_usb_connected' => false,
        'integrity' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'device_id' => 'deviceId',
        'is_rooted_device' => 'isRootedDevice',
        'screen_brightness' => 'screenBrightness',
        'elapsed_time_since_boot' => 'elapsedTimeSinceBoot',
        'os_version' => 'osVersion',
        'user_time_zone_offset' => 'userTimeZoneOffset',
        'language' => 'language',
        'screen_dimensions' => 'screenDimensions',
        'account_tenure' => 'accountTenure',
        'geolocation' => 'geolocation',
        'is_calling_progress' => 'isCallingProgress',
        'is_dev_mode_enabled' => 'isDevModeEnabled',
        'is_mock_gps' => 'isMockGPS',
        'is_emulated' => 'isEmulated',
        'is_monkey_runner' => 'isMonkeyRunner',
        'is_charging' => 'isCharging',
        'antenna_information' => 'antennaInformation',
        'is_usb_connected' => 'isUsbConnected',
        'integrity' => 'integrity'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'device_id' => 'setDeviceId',
        'is_rooted_device' => 'setIsRootedDevice',
        'screen_brightness' => 'setScreenBrightness',
        'elapsed_time_since_boot' => 'setElapsedTimeSinceBoot',
        'os_version' => 'setOsVersion',
        'user_time_zone_offset' => 'setUserTimeZoneOffset',
        'language' => 'setLanguage',
        'screen_dimensions' => 'setScreenDimensions',
        'account_tenure' => 'setAccountTenure',
        'geolocation' => 'setGeolocation',
        'is_calling_progress' => 'setIsCallingProgress',
        'is_dev_mode_enabled' => 'setIsDevModeEnabled',
        'is_mock_gps' => 'setIsMockGps',
        'is_emulated' => 'setIsEmulated',
        'is_monkey_runner' => 'setIsMonkeyRunner',
        'is_charging' => 'setIsCharging',
        'antenna_information' => 'setAntennaInformation',
        'is_usb_connected' => 'setIsUsbConnected',
        'integrity' => 'setIntegrity'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'device_id' => 'getDeviceId',
        'is_rooted_device' => 'getIsRootedDevice',
        'screen_brightness' => 'getScreenBrightness',
        'elapsed_time_since_boot' => 'getElapsedTimeSinceBoot',
        'os_version' => 'getOsVersion',
        'user_time_zone_offset' => 'getUserTimeZoneOffset',
        'language' => 'getLanguage',
        'screen_dimensions' => 'getScreenDimensions',
        'account_tenure' => 'getAccountTenure',
        'geolocation' => 'getGeolocation',
        'is_calling_progress' => 'getIsCallingProgress',
        'is_dev_mode_enabled' => 'getIsDevModeEnabled',
        'is_mock_gps' => 'getIsMockGps',
        'is_emulated' => 'getIsEmulated',
        'is_monkey_runner' => 'getIsMonkeyRunner',
        'is_charging' => 'getIsCharging',
        'antenna_information' => 'getAntennaInformation',
        'is_usb_connected' => 'getIsUsbConnected',
        'integrity' => 'getIntegrity'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[]|null $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(?array $data = null)
    {
        $this->setIfExists('device_id', $data ?? [], null);
        $this->setIfExists('is_rooted_device', $data ?? [], null);
        $this->setIfExists('screen_brightness', $data ?? [], null);
        $this->setIfExists('elapsed_time_since_boot', $data ?? [], null);
        $this->setIfExists('os_version', $data ?? [], null);
        $this->setIfExists('user_time_zone_offset', $data ?? [], null);
        $this->setIfExists('language', $data ?? [], null);
        $this->setIfExists('screen_dimensions', $data ?? [], null);
        $this->setIfExists('account_tenure', $data ?? [], null);
        $this->setIfExists('geolocation', $data ?? [], null);
        $this->setIfExists('is_calling_progress', $data ?? [], null);
        $this->setIfExists('is_dev_mode_enabled', $data ?? [], null);
        $this->setIfExists('is_mock_gps', $data ?? [], null);
        $this->setIfExists('is_emulated', $data ?? [], null);
        $this->setIfExists('is_monkey_runner', $data ?? [], null);
        $this->setIfExists('is_charging', $data ?? [], null);
        $this->setIfExists('antenna_information', $data ?? [], null);
        $this->setIfExists('is_usb_connected', $data ?? [], null);
        $this->setIfExists('integrity', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['device_id'] === null) {
            $invalidProperties[] = "'device_id' can't be null";
        }
        if ($this->container['is_rooted_device'] === null) {
            $invalidProperties[] = "'is_rooted_device' can't be null";
        }
        if ($this->container['screen_brightness'] === null) {
            $invalidProperties[] = "'screen_brightness' can't be null";
        }
        if ($this->container['elapsed_time_since_boot'] === null) {
            $invalidProperties[] = "'elapsed_time_since_boot' can't be null";
        }
        if ($this->container['os_version'] === null) {
            $invalidProperties[] = "'os_version' can't be null";
        }
        if ($this->container['user_time_zone_offset'] === null) {
            $invalidProperties[] = "'user_time_zone_offset' can't be null";
        }
        if ($this->container['language'] === null) {
            $invalidProperties[] = "'language' can't be null";
        }
        if ($this->container['screen_dimensions'] === null) {
            $invalidProperties[] = "'screen_dimensions' can't be null";
        }
        if ($this->container['account_tenure'] === null) {
            $invalidProperties[] = "'account_tenure' can't be null";
        }
        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", $this->container['account_tenure'])) {
            $invalidProperties[] = "invalid value for 'account_tenure', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.";
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets device_id
     *
     * @return string
     */
    public function getDeviceId()
    {
        return $this->container['device_id'];
    }

    /**
     * Sets device_id
     *
     * @param string $device_id ID único do dispositivo gerado pela plataforma.
     *
     * @return self
     */
    public function setDeviceId($device_id)
    {
        if (is_null($device_id)) {
            throw new \InvalidArgumentException('non-nullable device_id cannot be null');
        }
        $this->container['device_id'] = $device_id;

        return $this;
    }

    /**
     * Gets is_rooted_device
     *
     * @return bool
     */
    public function getIsRootedDevice()
    {
        return $this->container['is_rooted_device'];
    }

    /**
     * Sets is_rooted_device
     *
     * @param bool $is_rooted_device Indica se o dispositivo atualmente está com permissão de “root”.
     *
     * @return self
     */
    public function setIsRootedDevice($is_rooted_device)
    {
        if (is_null($is_rooted_device)) {
            throw new \InvalidArgumentException('non-nullable is_rooted_device cannot be null');
        }
        $this->container['is_rooted_device'] = $is_rooted_device;

        return $this;
    }

    /**
     * Gets screen_brightness
     *
     * @return float
     */
    public function getScreenBrightness()
    {
        return $this->container['screen_brightness'];
    }

    /**
     * Sets screen_brightness
     *
     * @param float $screen_brightness Indica o nível de brilho da tela do dispositivo.   Em dispositivos Android o valor é um inteiro, entre 0 e 255, inclusive;   Em dispositivos iOS o valor é um ponto flutuante entre 0.0 e 1.0.
     *
     * @return self
     */
    public function setScreenBrightness($screen_brightness)
    {
        if (is_null($screen_brightness)) {
            throw new \InvalidArgumentException('non-nullable screen_brightness cannot be null');
        }
        $this->container['screen_brightness'] = $screen_brightness;

        return $this;
    }

    /**
     * Gets elapsed_time_since_boot
     *
     * @return int
     */
    public function getElapsedTimeSinceBoot()
    {
        return $this->container['elapsed_time_since_boot'];
    }

    /**
     * Sets elapsed_time_since_boot
     *
     * @param int $elapsed_time_since_boot Indica por quanto tempo (em milissegundos) o dispositivo está ligado.
     *
     * @return self
     */
    public function setElapsedTimeSinceBoot($elapsed_time_since_boot)
    {
        if (is_null($elapsed_time_since_boot)) {
            throw new \InvalidArgumentException('non-nullable elapsed_time_since_boot cannot be null');
        }
        $this->container['elapsed_time_since_boot'] = $elapsed_time_since_boot;

        return $this;
    }

    /**
     * Gets os_version
     *
     * @return string
     */
    public function getOsVersion()
    {
        return $this->container['os_version'];
    }

    /**
     * Sets os_version
     *
     * @param string $os_version Versão do sistema operacional.
     *
     * @return self
     */
    public function setOsVersion($os_version)
    {
        if (is_null($os_version)) {
            throw new \InvalidArgumentException('non-nullable os_version cannot be null');
        }
        $this->container['os_version'] = $os_version;

        return $this;
    }

    /**
     * Gets user_time_zone_offset
     *
     * @return string
     */
    public function getUserTimeZoneOffset()
    {
        return $this->container['user_time_zone_offset'];
    }

    /**
     * Sets user_time_zone_offset
     *
     * @param string $user_time_zone_offset Indica a configuração de fuso horário do dispositivo do usuário, com o formato UTC offset: ±hh[:mm]
     *
     * @return self
     */
    public function setUserTimeZoneOffset($user_time_zone_offset)
    {
        if (is_null($user_time_zone_offset)) {
            throw new \InvalidArgumentException('non-nullable user_time_zone_offset cannot be null');
        }
        $this->container['user_time_zone_offset'] = $user_time_zone_offset;

        return $this;
    }

    /**
     * Gets language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->container['language'];
    }

    /**
     * Sets language
     *
     * @param string $language Indica o idioma do dispositivo no formato ISO 639-1.
     *
     * @return self
     */
    public function setLanguage($language)
    {
        if (is_null($language)) {
            throw new \InvalidArgumentException('non-nullable language cannot be null');
        }
        $this->container['language'] = $language;

        return $this;
    }

    /**
     * Gets screen_dimensions
     *
     * @return \OpenAPI\Client\Model\RiskSignalsPaymentsManualScreenDimensions
     */
    public function getScreenDimensions()
    {
        return $this->container['screen_dimensions'];
    }

    /**
     * Sets screen_dimensions
     *
     * @param \OpenAPI\Client\Model\RiskSignalsPaymentsManualScreenDimensions $screen_dimensions screen_dimensions
     *
     * @return self
     */
    public function setScreenDimensions($screen_dimensions)
    {
        if (is_null($screen_dimensions)) {
            throw new \InvalidArgumentException('non-nullable screen_dimensions cannot be null');
        }
        $this->container['screen_dimensions'] = $screen_dimensions;

        return $this;
    }

    /**
     * Gets account_tenure
     *
     * @return \DateTime
     */
    public function getAccountTenure()
    {
        return $this->container['account_tenure'];
    }

    /**
     * Sets account_tenure
     *
     * @param \DateTime $account_tenure Data de cadastro do cliente na iniciadora.
     *
     * @return self
     */
    public function setAccountTenure($account_tenure)
    {
        if (is_null($account_tenure)) {
            throw new \InvalidArgumentException('non-nullable account_tenure cannot be null');
        }

        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", ObjectSerializer::toString($account_tenure)))) {
            throw new \InvalidArgumentException("invalid value for \$account_tenure when calling RiskSignalsConsents., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.");
        }

        $this->container['account_tenure'] = $account_tenure;

        return $this;
    }

    /**
     * Gets geolocation
     *
     * @return \OpenAPI\Client\Model\RiskSignalsPaymentsManualGeolocation|null
     */
    public function getGeolocation()
    {
        return $this->container['geolocation'];
    }

    /**
     * Sets geolocation
     *
     * @param \OpenAPI\Client\Model\RiskSignalsPaymentsManualGeolocation|null $geolocation geolocation
     *
     * @return self
     */
    public function setGeolocation($geolocation)
    {
        if (is_null($geolocation)) {
            throw new \InvalidArgumentException('non-nullable geolocation cannot be null');
        }
        $this->container['geolocation'] = $geolocation;

        return $this;
    }

    /**
     * Gets is_calling_progress
     *
     * @return bool|null
     */
    public function getIsCallingProgress()
    {
        return $this->container['is_calling_progress'];
    }

    /**
     * Sets is_calling_progress
     *
     * @param bool|null $is_calling_progress Indica chamada ativa no momento do vínculo.  [Restrição] Caso o sinal de risco esteja disponível (cliente permitiu que fosse coletado), o mesmo deverá ser enviado
     *
     * @return self
     */
    public function setIsCallingProgress($is_calling_progress)
    {
        if (is_null($is_calling_progress)) {
            throw new \InvalidArgumentException('non-nullable is_calling_progress cannot be null');
        }
        $this->container['is_calling_progress'] = $is_calling_progress;

        return $this;
    }

    /**
     * Gets is_dev_mode_enabled
     *
     * @return bool|null
     */
    public function getIsDevModeEnabled()
    {
        return $this->container['is_dev_mode_enabled'];
    }

    /**
     * Sets is_dev_mode_enabled
     *
     * @param bool|null $is_dev_mode_enabled Indica se o dispositivo está em modo de desenvolvedor.
     *
     * @return self
     */
    public function setIsDevModeEnabled($is_dev_mode_enabled)
    {
        if (is_null($is_dev_mode_enabled)) {
            throw new \InvalidArgumentException('non-nullable is_dev_mode_enabled cannot be null');
        }
        $this->container['is_dev_mode_enabled'] = $is_dev_mode_enabled;

        return $this;
    }

    /**
     * Gets is_mock_gps
     *
     * @return bool|null
     */
    public function getIsMockGps()
    {
        return $this->container['is_mock_gps'];
    }

    /**
     * Sets is_mock_gps
     *
     * @param bool|null $is_mock_gps Indica se o dispositivo está usando um GPS falso.
     *
     * @return self
     */
    public function setIsMockGps($is_mock_gps)
    {
        if (is_null($is_mock_gps)) {
            throw new \InvalidArgumentException('non-nullable is_mock_gps cannot be null');
        }
        $this->container['is_mock_gps'] = $is_mock_gps;

        return $this;
    }

    /**
     * Gets is_emulated
     *
     * @return bool|null
     */
    public function getIsEmulated()
    {
        return $this->container['is_emulated'];
    }

    /**
     * Sets is_emulated
     *
     * @param bool|null $is_emulated Indica se o dispositivo é emulado ou real.
     *
     * @return self
     */
    public function setIsEmulated($is_emulated)
    {
        if (is_null($is_emulated)) {
            throw new \InvalidArgumentException('non-nullable is_emulated cannot be null');
        }
        $this->container['is_emulated'] = $is_emulated;

        return $this;
    }

    /**
     * Gets is_monkey_runner
     *
     * @return bool|null
     */
    public function getIsMonkeyRunner()
    {
        return $this->container['is_monkey_runner'];
    }

    /**
     * Sets is_monkey_runner
     *
     * @param bool|null $is_monkey_runner Indica o uso do MonkeyRunner.
     *
     * @return self
     */
    public function setIsMonkeyRunner($is_monkey_runner)
    {
        if (is_null($is_monkey_runner)) {
            throw new \InvalidArgumentException('non-nullable is_monkey_runner cannot be null');
        }
        $this->container['is_monkey_runner'] = $is_monkey_runner;

        return $this;
    }

    /**
     * Gets is_charging
     *
     * @return bool|null
     */
    public function getIsCharging()
    {
        return $this->container['is_charging'];
    }

    /**
     * Sets is_charging
     *
     * @param bool|null $is_charging Indica se a bateria do dispositivo está sendo carregada.
     *
     * @return self
     */
    public function setIsCharging($is_charging)
    {
        if (is_null($is_charging)) {
            throw new \InvalidArgumentException('non-nullable is_charging cannot be null');
        }
        $this->container['is_charging'] = $is_charging;

        return $this;
    }

    /**
     * Gets antenna_information
     *
     * @return string|null
     */
    public function getAntennaInformation()
    {
        return $this->container['antenna_information'];
    }

    /**
     * Sets antenna_information
     *
     * @param string|null $antenna_information Indica em qual antena o dispositivo está conectado.
     *
     * @return self
     */
    public function setAntennaInformation($antenna_information)
    {
        if (is_null($antenna_information)) {
            throw new \InvalidArgumentException('non-nullable antenna_information cannot be null');
        }
        $this->container['antenna_information'] = $antenna_information;

        return $this;
    }

    /**
     * Gets is_usb_connected
     *
     * @return bool|null
     */
    public function getIsUsbConnected()
    {
        return $this->container['is_usb_connected'];
    }

    /**
     * Sets is_usb_connected
     *
     * @param bool|null $is_usb_connected Indica se o dispositivo está conectado a outro dispositivo via USB.
     *
     * @return self
     */
    public function setIsUsbConnected($is_usb_connected)
    {
        if (is_null($is_usb_connected)) {
            throw new \InvalidArgumentException('non-nullable is_usb_connected cannot be null');
        }
        $this->container['is_usb_connected'] = $is_usb_connected;

        return $this;
    }

    /**
     * Gets integrity
     *
     * @return \OpenAPI\Client\Model\RiskSignalsPaymentsManualIntegrity|null
     */
    public function getIntegrity()
    {
        return $this->container['integrity'];
    }

    /**
     * Sets integrity
     *
     * @param \OpenAPI\Client\Model\RiskSignalsPaymentsManualIntegrity|null $integrity integrity
     *
     * @return self
     */
    public function setIntegrity($integrity)
    {
        if (is_null($integrity)) {
            throw new \InvalidArgumentException('non-nullable integrity cannot be null');
        }
        $this->container['integrity'] = $integrity;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer|string $offset Offset
     *
     * @return boolean
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer|string $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer|string $offset Offset
     *
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}


