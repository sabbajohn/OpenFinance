<?php
/**
 * AutomaticAutomatic
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
 * AutomaticAutomatic Class Doc Comment
 *
 * @category Class
 * @description Definição da configuração de recorrência para pagamentos automáticos
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class AutomaticAutomatic implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Automatic_automatic';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'contract_id' => 'string',
        'fixed_amount' => 'string',
        'maximum_variable_amount' => 'string',
        'interval' => 'string',
        'contract_debtor' => '\OpenAPI\Client\Model\ContractDebtor',
        'first_payment' => '\OpenAPI\Client\Model\FirstPayment',
        'minimum_variable_amount' => 'string',
        'is_retry_accepted' => 'bool',
        'use_overdraft_limit' => 'bool',
        'reference_start_date' => '\DateTime'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'contract_id' => null,
        'fixed_amount' => null,
        'maximum_variable_amount' => null,
        'interval' => null,
        'contract_debtor' => null,
        'first_payment' => null,
        'minimum_variable_amount' => null,
        'is_retry_accepted' => null,
        'use_overdraft_limit' => null,
        'reference_start_date' => 'date'
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'contract_id' => false,
        'fixed_amount' => false,
        'maximum_variable_amount' => false,
        'interval' => false,
        'contract_debtor' => false,
        'first_payment' => false,
        'minimum_variable_amount' => false,
        'is_retry_accepted' => false,
        'use_overdraft_limit' => false,
        'reference_start_date' => false
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
        'contract_id' => 'contractId',
        'fixed_amount' => 'fixedAmount',
        'maximum_variable_amount' => 'maximumVariableAmount',
        'interval' => 'interval',
        'contract_debtor' => 'contractDebtor',
        'first_payment' => 'firstPayment',
        'minimum_variable_amount' => 'minimumVariableAmount',
        'is_retry_accepted' => 'isRetryAccepted',
        'use_overdraft_limit' => 'useOverdraftLimit',
        'reference_start_date' => 'referenceStartDate'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'contract_id' => 'setContractId',
        'fixed_amount' => 'setFixedAmount',
        'maximum_variable_amount' => 'setMaximumVariableAmount',
        'interval' => 'setInterval',
        'contract_debtor' => 'setContractDebtor',
        'first_payment' => 'setFirstPayment',
        'minimum_variable_amount' => 'setMinimumVariableAmount',
        'is_retry_accepted' => 'setIsRetryAccepted',
        'use_overdraft_limit' => 'setUseOverdraftLimit',
        'reference_start_date' => 'setReferenceStartDate'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'contract_id' => 'getContractId',
        'fixed_amount' => 'getFixedAmount',
        'maximum_variable_amount' => 'getMaximumVariableAmount',
        'interval' => 'getInterval',
        'contract_debtor' => 'getContractDebtor',
        'first_payment' => 'getFirstPayment',
        'minimum_variable_amount' => 'getMinimumVariableAmount',
        'is_retry_accepted' => 'getIsRetryAccepted',
        'use_overdraft_limit' => 'getUseOverdraftLimit',
        'reference_start_date' => 'getReferenceStartDate'
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

    public const INTERVAL_SEMANAL = 'SEMANAL';
    public const INTERVAL_MENSAL = 'MENSAL';
    public const INTERVAL_ANUAL = 'ANUAL';
    public const INTERVAL_SEMESTRAL = 'SEMESTRAL';
    public const INTERVAL_TRIMESTRAL = 'TRIMESTRAL';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getIntervalAllowableValues()
    {
        return [
            self::INTERVAL_SEMANAL,
            self::INTERVAL_MENSAL,
            self::INTERVAL_ANUAL,
            self::INTERVAL_SEMESTRAL,
            self::INTERVAL_TRIMESTRAL,
        ];
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
        $this->setIfExists('contract_id', $data ?? [], null);
        $this->setIfExists('fixed_amount', $data ?? [], null);
        $this->setIfExists('maximum_variable_amount', $data ?? [], null);
        $this->setIfExists('interval', $data ?? [], null);
        $this->setIfExists('contract_debtor', $data ?? [], null);
        $this->setIfExists('first_payment', $data ?? [], null);
        $this->setIfExists('minimum_variable_amount', $data ?? [], null);
        $this->setIfExists('is_retry_accepted', $data ?? [], null);
        $this->setIfExists('use_overdraft_limit', $data ?? [], true);
        $this->setIfExists('reference_start_date', $data ?? [], null);
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

        if ($this->container['contract_id'] === null) {
            $invalidProperties[] = "'contract_id' can't be null";
        }
        if ((mb_strlen($this->container['contract_id']) > 35)) {
            $invalidProperties[] = "invalid value for 'contract_id', the character length must be smaller than or equal to 35.";
        }

        if ((mb_strlen($this->container['contract_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'contract_id', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[a-zA-Z0-9]{1,35}$/", $this->container['contract_id'])) {
            $invalidProperties[] = "invalid value for 'contract_id', must be conform to the pattern /^[a-zA-Z0-9]{1,35}$/.";
        }

        if (!is_null($this->container['fixed_amount']) && (mb_strlen($this->container['fixed_amount']) > 19)) {
            $invalidProperties[] = "invalid value for 'fixed_amount', the character length must be smaller than or equal to 19.";
        }

        if (!is_null($this->container['fixed_amount']) && (mb_strlen($this->container['fixed_amount']) < 4)) {
            $invalidProperties[] = "invalid value for 'fixed_amount', the character length must be bigger than or equal to 4.";
        }

        if (!is_null($this->container['fixed_amount']) && !preg_match("/^((\\d{1,16}\\.\\d{2}))$/", $this->container['fixed_amount'])) {
            $invalidProperties[] = "invalid value for 'fixed_amount', must be conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.";
        }

        if (!is_null($this->container['maximum_variable_amount']) && (mb_strlen($this->container['maximum_variable_amount']) > 19)) {
            $invalidProperties[] = "invalid value for 'maximum_variable_amount', the character length must be smaller than or equal to 19.";
        }

        if (!is_null($this->container['maximum_variable_amount']) && (mb_strlen($this->container['maximum_variable_amount']) < 4)) {
            $invalidProperties[] = "invalid value for 'maximum_variable_amount', the character length must be bigger than or equal to 4.";
        }

        if (!is_null($this->container['maximum_variable_amount']) && !preg_match("/^((\\d{1,16}\\.\\d{2}))$/", $this->container['maximum_variable_amount'])) {
            $invalidProperties[] = "invalid value for 'maximum_variable_amount', must be conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.";
        }

        if ($this->container['interval'] === null) {
            $invalidProperties[] = "'interval' can't be null";
        }
        $allowedValues = $this->getIntervalAllowableValues();
        if (!is_null($this->container['interval']) && !in_array($this->container['interval'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'interval', must be one of '%s'",
                $this->container['interval'],
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['contract_debtor'] === null) {
            $invalidProperties[] = "'contract_debtor' can't be null";
        }
        if (!is_null($this->container['minimum_variable_amount']) && (mb_strlen($this->container['minimum_variable_amount']) > 19)) {
            $invalidProperties[] = "invalid value for 'minimum_variable_amount', the character length must be smaller than or equal to 19.";
        }

        if (!is_null($this->container['minimum_variable_amount']) && (mb_strlen($this->container['minimum_variable_amount']) < 4)) {
            $invalidProperties[] = "invalid value for 'minimum_variable_amount', the character length must be bigger than or equal to 4.";
        }

        if (!is_null($this->container['minimum_variable_amount']) && !preg_match("/^((\\d{1,16}\\.\\d{2}))$/", $this->container['minimum_variable_amount'])) {
            $invalidProperties[] = "invalid value for 'minimum_variable_amount', must be conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.";
        }

        if ($this->container['is_retry_accepted'] === null) {
            $invalidProperties[] = "'is_retry_accepted' can't be null";
        }
        if ($this->container['use_overdraft_limit'] === null) {
            $invalidProperties[] = "'use_overdraft_limit' can't be null";
        }
        if ($this->container['reference_start_date'] === null) {
            $invalidProperties[] = "'reference_start_date' can't be null";
        }
        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", $this->container['reference_start_date'])) {
            $invalidProperties[] = "invalid value for 'reference_start_date', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.";
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
     * Gets contract_id
     *
     * @return string
     */
    public function getContractId()
    {
        return $this->container['contract_id'];
    }

    /**
     * Sets contract_id
     *
     * @param string $contract_id Identificador do contrato de transação
     *
     * @return self
     */
    public function setContractId($contract_id)
    {
        if (is_null($contract_id)) {
            throw new \InvalidArgumentException('non-nullable contract_id cannot be null');
        }
        if ((mb_strlen($contract_id) > 35)) {
            throw new \InvalidArgumentException('invalid length for $contract_id when calling AutomaticAutomatic., must be smaller than or equal to 35.');
        }
        if ((mb_strlen($contract_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $contract_id when calling AutomaticAutomatic., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9]{1,35}$/", ObjectSerializer::toString($contract_id)))) {
            throw new \InvalidArgumentException("invalid value for \$contract_id when calling AutomaticAutomatic., must conform to the pattern /^[a-zA-Z0-9]{1,35}$/.");
        }

        $this->container['contract_id'] = $contract_id;

        return $this;
    }

    /**
     * Gets fixed_amount
     *
     * @return string|null
     */
    public function getFixedAmount()
    {
        return $this->container['fixed_amount'];
    }

    /**
     * Sets fixed_amount
     *
     * @param string|null $fixed_amount Valor fixo de cobrança, caso preenchido, representa um consentimento para pagamentos de valores fixos, ou não sujeitos a alteração durante a vigência do consentimento.   [Restrição] Excludente com o campo “/data/recurringConfiguration/automatic/maximumVariableAmount”
     *
     * @return self
     */
    public function setFixedAmount($fixed_amount)
    {
        if (is_null($fixed_amount)) {
            throw new \InvalidArgumentException('non-nullable fixed_amount cannot be null');
        }
        if ((mb_strlen($fixed_amount) > 19)) {
            throw new \InvalidArgumentException('invalid length for $fixed_amount when calling AutomaticAutomatic., must be smaller than or equal to 19.');
        }
        if ((mb_strlen($fixed_amount) < 4)) {
            throw new \InvalidArgumentException('invalid length for $fixed_amount when calling AutomaticAutomatic., must be bigger than or equal to 4.');
        }
        if ((!preg_match("/^((\\d{1,16}\\.\\d{2}))$/", ObjectSerializer::toString($fixed_amount)))) {
            throw new \InvalidArgumentException("invalid value for \$fixed_amount when calling AutomaticAutomatic., must conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.");
        }

        $this->container['fixed_amount'] = $fixed_amount;

        return $this;
    }

    /**
     * Gets maximum_variable_amount
     *
     * @return string|null
     */
    public function getMaximumVariableAmount()
    {
        return $this->container['maximum_variable_amount'];
    }

    /**
     * Sets maximum_variable_amount
     *
     * @param string|null $maximum_variable_amount Valor máximo permitido por cobrança, caso preenchido, representa um consentimento para pagamentos de valores variáveis.   [Restrição] Excludente com o campo “/data/recurringConfiguration/automatic/fixedAmount”
     *
     * @return self
     */
    public function setMaximumVariableAmount($maximum_variable_amount)
    {
        if (is_null($maximum_variable_amount)) {
            throw new \InvalidArgumentException('non-nullable maximum_variable_amount cannot be null');
        }
        if ((mb_strlen($maximum_variable_amount) > 19)) {
            throw new \InvalidArgumentException('invalid length for $maximum_variable_amount when calling AutomaticAutomatic., must be smaller than or equal to 19.');
        }
        if ((mb_strlen($maximum_variable_amount) < 4)) {
            throw new \InvalidArgumentException('invalid length for $maximum_variable_amount when calling AutomaticAutomatic., must be bigger than or equal to 4.');
        }
        if ((!preg_match("/^((\\d{1,16}\\.\\d{2}))$/", ObjectSerializer::toString($maximum_variable_amount)))) {
            throw new \InvalidArgumentException("invalid value for \$maximum_variable_amount when calling AutomaticAutomatic., must conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.");
        }

        $this->container['maximum_variable_amount'] = $maximum_variable_amount;

        return $this;
    }

    /**
     * Gets interval
     *
     * @return string
     */
    public function getInterval()
    {
        return $this->container['interval'];
    }

    /**
     * Sets interval
     *
     * @param string $interval Define a periodicidade permitida para realização de transações - SEMANAL - MENSAL - ANUAL - SEMESTRAL - TRIMESTRAL
     *
     * @return self
     */
    public function setInterval($interval)
    {
        if (is_null($interval)) {
            throw new \InvalidArgumentException('non-nullable interval cannot be null');
        }
        $allowedValues = $this->getIntervalAllowableValues();
        if (!in_array($interval, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'interval', must be one of '%s'",
                    $interval,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['interval'] = $interval;

        return $this;
    }

    /**
     * Gets contract_debtor
     *
     * @return \OpenAPI\Client\Model\ContractDebtor
     */
    public function getContractDebtor()
    {
        return $this->container['contract_debtor'];
    }

    /**
     * Sets contract_debtor
     *
     * @param \OpenAPI\Client\Model\ContractDebtor $contract_debtor contract_debtor
     *
     * @return self
     */
    public function setContractDebtor($contract_debtor)
    {
        if (is_null($contract_debtor)) {
            throw new \InvalidArgumentException('non-nullable contract_debtor cannot be null');
        }
        $this->container['contract_debtor'] = $contract_debtor;

        return $this;
    }

    /**
     * Gets first_payment
     *
     * @return \OpenAPI\Client\Model\FirstPayment|null
     */
    public function getFirstPayment()
    {
        return $this->container['first_payment'];
    }

    /**
     * Sets first_payment
     *
     * @param \OpenAPI\Client\Model\FirstPayment|null $first_payment first_payment
     *
     * @return self
     */
    public function setFirstPayment($first_payment)
    {
        if (is_null($first_payment)) {
            throw new \InvalidArgumentException('non-nullable first_payment cannot be null');
        }
        $this->container['first_payment'] = $first_payment;

        return $this;
    }

    /**
     * Gets minimum_variable_amount
     *
     * @return string|null
     */
    public function getMinimumVariableAmount()
    {
        return $this->container['minimum_variable_amount'];
    }

    /**
     * Sets minimum_variable_amount
     *
     * @param string|null $minimum_variable_amount Valor definido pelo usuário recebedor.  Se o usuário pagador atribuir um valor máximo para os pagamentos daquela autorização (campo “maximumVariableAmount”), ele não poderá ser inferior ao piso definido pelo usuário recebedor. Não pode ser preenchido nas autorizações de valor fixo, ou seja, com campo “/data/recurringConfiguration/automatic/fixedAmount”.  Não representa um valor mínimo de cobrança para o pagamento.
     *
     * @return self
     */
    public function setMinimumVariableAmount($minimum_variable_amount)
    {
        if (is_null($minimum_variable_amount)) {
            throw new \InvalidArgumentException('non-nullable minimum_variable_amount cannot be null');
        }
        if ((mb_strlen($minimum_variable_amount) > 19)) {
            throw new \InvalidArgumentException('invalid length for $minimum_variable_amount when calling AutomaticAutomatic., must be smaller than or equal to 19.');
        }
        if ((mb_strlen($minimum_variable_amount) < 4)) {
            throw new \InvalidArgumentException('invalid length for $minimum_variable_amount when calling AutomaticAutomatic., must be bigger than or equal to 4.');
        }
        if ((!preg_match("/^((\\d{1,16}\\.\\d{2}))$/", ObjectSerializer::toString($minimum_variable_amount)))) {
            throw new \InvalidArgumentException("invalid value for \$minimum_variable_amount when calling AutomaticAutomatic., must conform to the pattern /^((\\d{1,16}\\.\\d{2}))$/.");
        }

        $this->container['minimum_variable_amount'] = $minimum_variable_amount;

        return $this;
    }

    /**
     * Gets is_retry_accepted
     *
     * @return bool
     */
    public function getIsRetryAccepted()
    {
        return $this->container['is_retry_accepted'];
    }

    /**
     * Sets is_retry_accepted
     *
     * @param bool $is_retry_accepted Indica se é permitido pelo cliente recebedor fazer tentativas de pagamento (extradia), conforme as regras estabelecidas no arranjo Pix.
     *
     * @return self
     */
    public function setIsRetryAccepted($is_retry_accepted)
    {
        if (is_null($is_retry_accepted)) {
            throw new \InvalidArgumentException('non-nullable is_retry_accepted cannot be null');
        }
        $this->container['is_retry_accepted'] = $is_retry_accepted;

        return $this;
    }

    /**
     * Gets use_overdraft_limit
     *
     * @return bool
     */
    public function getUseOverdraftLimit()
    {
        return $this->container['use_overdraft_limit'];
    }

    /**
     * Sets use_overdraft_limit
     *
     * @param bool $use_overdraft_limit Indica se o usuário pagador autorizou a utilização de limite pré-aprovado (cheque especial) na sua conta para realização de pagamentos, caso o cliente possua o produto.
     *
     * @return self
     */
    public function setUseOverdraftLimit($use_overdraft_limit)
    {
        if (is_null($use_overdraft_limit)) {
            throw new \InvalidArgumentException('non-nullable use_overdraft_limit cannot be null');
        }
        $this->container['use_overdraft_limit'] = $use_overdraft_limit;

        return $this;
    }

    /**
     * Gets reference_start_date
     *
     * @return \DateTime
     */
    public function getReferenceStartDate()
    {
        return $this->container['reference_start_date'];
    }

    /**
     * Sets reference_start_date
     *
     * @param \DateTime $reference_start_date Representa a data prevista para a primeira ocorrência de um pagamento associado a recorrência.  Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339), sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setReferenceStartDate($reference_start_date)
    {
        if (is_null($reference_start_date)) {
            throw new \InvalidArgumentException('non-nullable reference_start_date cannot be null');
        }

        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", ObjectSerializer::toString($reference_start_date)))) {
            throw new \InvalidArgumentException("invalid value for \$reference_start_date when calling AutomaticAutomatic., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.");
        }

        $this->container['reference_start_date'] = $reference_start_date;

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


