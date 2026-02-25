<?php
/**
 * ResponseRecurringPaymentsDataPatch
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
 * ResponseRecurringPaymentsDataPatch Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Uautomatic-paymentsResponseRecurringPaymentsDataPatch implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'ResponseRecurringPaymentsDataPatch';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'recurring_payment_id' => 'string',
        'recurring_consent_id' => 'string',
        'end_to_end_id' => 'string',
        'date' => 'string',
        'creation_date_time' => '\DateTime',
        'status_update_date_time' => '\DateTime',
        'status' => '\OpenAPI\Client\Model\EnumPaymentStatusType',
        'rejection_reason' => '\OpenAPI\Client\Model\RejectionReason',
        'cnpj_initiator' => 'string',
        'payment' => '\OpenAPI\Client\Model\PaymentPix',
        'remittance_information' => 'string',
        'creditor_account' => '\OpenAPI\Client\Model\CreditorAccount',
        'debtor_account' => '\OpenAPI\Client\Model\DebtorAccount',
        'cancellation' => '\OpenAPI\Client\Model\PixPaymentCancellation',
        'authorisation_flow' => 'string',
        'transaction_identification' => 'string',
        'document' => '\OpenAPI\Client\Model\CreateRecurringPixPaymentDataDocument',
        'proxy' => 'string',
        'local_instrument' => 'string',
        'original_recurring_payment_id' => 'string',
        'payment_reference' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'recurring_payment_id' => null,
        'recurring_consent_id' => null,
        'end_to_end_id' => null,
        'date' => null,
        'creation_date_time' => 'date-time',
        'status_update_date_time' => 'date-time',
        'status' => null,
        'rejection_reason' => null,
        'cnpj_initiator' => null,
        'payment' => null,
        'remittance_information' => null,
        'creditor_account' => null,
        'debtor_account' => null,
        'cancellation' => null,
        'authorisation_flow' => null,
        'transaction_identification' => null,
        'document' => null,
        'proxy' => null,
        'local_instrument' => null,
        'original_recurring_payment_id' => null,
        'payment_reference' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'recurring_payment_id' => false,
        'recurring_consent_id' => false,
        'end_to_end_id' => false,
        'date' => false,
        'creation_date_time' => false,
        'status_update_date_time' => false,
        'status' => false,
        'rejection_reason' => false,
        'cnpj_initiator' => false,
        'payment' => false,
        'remittance_information' => false,
        'creditor_account' => false,
        'debtor_account' => false,
        'cancellation' => false,
        'authorisation_flow' => false,
        'transaction_identification' => false,
        'document' => false,
        'proxy' => false,
        'local_instrument' => false,
        'original_recurring_payment_id' => false,
        'payment_reference' => false
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
        'recurring_payment_id' => 'recurringPaymentId',
        'recurring_consent_id' => 'recurringConsentId',
        'end_to_end_id' => 'endToEndId',
        'date' => 'date',
        'creation_date_time' => 'creationDateTime',
        'status_update_date_time' => 'statusUpdateDateTime',
        'status' => 'status',
        'rejection_reason' => 'rejectionReason',
        'cnpj_initiator' => 'cnpjInitiator',
        'payment' => 'payment',
        'remittance_information' => 'remittanceInformation',
        'creditor_account' => 'creditorAccount',
        'debtor_account' => 'debtorAccount',
        'cancellation' => 'cancellation',
        'authorisation_flow' => 'authorisationFlow',
        'transaction_identification' => 'transactionIdentification',
        'document' => 'document',
        'proxy' => 'proxy',
        'local_instrument' => 'localInstrument',
        'original_recurring_payment_id' => 'originalRecurringPaymentId',
        'payment_reference' => 'paymentReference'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'recurring_payment_id' => 'setRecurringPaymentId',
        'recurring_consent_id' => 'setRecurringConsentId',
        'end_to_end_id' => 'setEndToEndId',
        'date' => 'setDate',
        'creation_date_time' => 'setCreationDateTime',
        'status_update_date_time' => 'setStatusUpdateDateTime',
        'status' => 'setStatus',
        'rejection_reason' => 'setRejectionReason',
        'cnpj_initiator' => 'setCnpjInitiator',
        'payment' => 'setPayment',
        'remittance_information' => 'setRemittanceInformation',
        'creditor_account' => 'setCreditorAccount',
        'debtor_account' => 'setDebtorAccount',
        'cancellation' => 'setCancellation',
        'authorisation_flow' => 'setAuthorisationFlow',
        'transaction_identification' => 'setTransactionIdentification',
        'document' => 'setDocument',
        'proxy' => 'setProxy',
        'local_instrument' => 'setLocalInstrument',
        'original_recurring_payment_id' => 'setOriginalRecurringPaymentId',
        'payment_reference' => 'setPaymentReference'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'recurring_payment_id' => 'getRecurringPaymentId',
        'recurring_consent_id' => 'getRecurringConsentId',
        'end_to_end_id' => 'getEndToEndId',
        'date' => 'getDate',
        'creation_date_time' => 'getCreationDateTime',
        'status_update_date_time' => 'getStatusUpdateDateTime',
        'status' => 'getStatus',
        'rejection_reason' => 'getRejectionReason',
        'cnpj_initiator' => 'getCnpjInitiator',
        'payment' => 'getPayment',
        'remittance_information' => 'getRemittanceInformation',
        'creditor_account' => 'getCreditorAccount',
        'debtor_account' => 'getDebtorAccount',
        'cancellation' => 'getCancellation',
        'authorisation_flow' => 'getAuthorisationFlow',
        'transaction_identification' => 'getTransactionIdentification',
        'document' => 'getDocument',
        'proxy' => 'getProxy',
        'local_instrument' => 'getLocalInstrument',
        'original_recurring_payment_id' => 'getOriginalRecurringPaymentId',
        'payment_reference' => 'getPaymentReference'
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

    public const AUTHORISATION_FLOW_HYBRID_FLOW = 'HYBRID_FLOW';
    public const AUTHORISATION_FLOW_CIBA_FLOW = 'CIBA_FLOW';
    public const AUTHORISATION_FLOW_FIDO_FLOW = 'FIDO_FLOW';
    public const LOCAL_INSTRUMENT_MANU = 'MANU';
    public const LOCAL_INSTRUMENT_DICT = 'DICT';
    public const LOCAL_INSTRUMENT_INIC = 'INIC';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getAuthorisationFlowAllowableValues()
    {
        return [
            self::AUTHORISATION_FLOW_HYBRID_FLOW,
            self::AUTHORISATION_FLOW_CIBA_FLOW,
            self::AUTHORISATION_FLOW_FIDO_FLOW,
        ];
    }

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getLocalInstrumentAllowableValues()
    {
        return [
            self::LOCAL_INSTRUMENT_MANU,
            self::LOCAL_INSTRUMENT_DICT,
            self::LOCAL_INSTRUMENT_INIC,
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
        $this->setIfExists('recurring_payment_id', $data ?? [], null);
        $this->setIfExists('recurring_consent_id', $data ?? [], null);
        $this->setIfExists('end_to_end_id', $data ?? [], null);
        $this->setIfExists('date', $data ?? [], null);
        $this->setIfExists('creation_date_time', $data ?? [], null);
        $this->setIfExists('status_update_date_time', $data ?? [], null);
        $this->setIfExists('status', $data ?? [], null);
        $this->setIfExists('rejection_reason', $data ?? [], null);
        $this->setIfExists('cnpj_initiator', $data ?? [], null);
        $this->setIfExists('payment', $data ?? [], null);
        $this->setIfExists('remittance_information', $data ?? [], null);
        $this->setIfExists('creditor_account', $data ?? [], null);
        $this->setIfExists('debtor_account', $data ?? [], null);
        $this->setIfExists('cancellation', $data ?? [], null);
        $this->setIfExists('authorisation_flow', $data ?? [], null);
        $this->setIfExists('transaction_identification', $data ?? [], null);
        $this->setIfExists('document', $data ?? [], null);
        $this->setIfExists('proxy', $data ?? [], null);
        $this->setIfExists('local_instrument', $data ?? [], null);
        $this->setIfExists('original_recurring_payment_id', $data ?? [], null);
        $this->setIfExists('payment_reference', $data ?? [], null);
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

        if ($this->container['recurring_payment_id'] === null) {
            $invalidProperties[] = "'recurring_payment_id' can't be null";
        }
        if ((mb_strlen($this->container['recurring_payment_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'recurring_payment_id', the character length must be smaller than or equal to 100.";
        }

        if ((mb_strlen($this->container['recurring_payment_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'recurring_payment_id', the character length must be bigger than or equal to 1.";
        }

        if (!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $this->container['recurring_payment_id'])) {
            $invalidProperties[] = "invalid value for 'recurring_payment_id', must be conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.";
        }

        if (!is_null($this->container['recurring_consent_id']) && (mb_strlen($this->container['recurring_consent_id']) > 256)) {
            $invalidProperties[] = "invalid value for 'recurring_consent_id', the character length must be smaller than or equal to 256.";
        }

        if (!is_null($this->container['recurring_consent_id']) && !preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", $this->container['recurring_consent_id'])) {
            $invalidProperties[] = "invalid value for 'recurring_consent_id', must be conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.";
        }

        if ($this->container['end_to_end_id'] === null) {
            $invalidProperties[] = "'end_to_end_id' can't be null";
        }
        if ((mb_strlen($this->container['end_to_end_id']) > 32)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be smaller than or equal to 32.";
        }

        if ((mb_strlen($this->container['end_to_end_id']) < 32)) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', the character length must be bigger than or equal to 32.";
        }

        if (!preg_match("/^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/", $this->container['end_to_end_id'])) {
            $invalidProperties[] = "invalid value for 'end_to_end_id', must be conform to the pattern /^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/.";
        }

        if ($this->container['date'] === null) {
            $invalidProperties[] = "'date' can't be null";
        }
        if ((mb_strlen($this->container['date']) > 10)) {
            $invalidProperties[] = "invalid value for 'date', the character length must be smaller than or equal to 10.";
        }

        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", $this->container['date'])) {
            $invalidProperties[] = "invalid value for 'date', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.";
        }

        if ($this->container['creation_date_time'] === null) {
            $invalidProperties[] = "'creation_date_time' can't be null";
        }
        if ((mb_strlen($this->container['creation_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'creation_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['creation_date_time'])) {
            $invalidProperties[] = "invalid value for 'creation_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
        }

        if ($this->container['status_update_date_time'] === null) {
            $invalidProperties[] = "'status_update_date_time' can't be null";
        }
        if ((mb_strlen($this->container['status_update_date_time']) > 20)) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', the character length must be smaller than or equal to 20.";
        }

        if (!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", $this->container['status_update_date_time'])) {
            $invalidProperties[] = "invalid value for 'status_update_date_time', must be conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.";
        }

        if ($this->container['status'] === null) {
            $invalidProperties[] = "'status' can't be null";
        }
        if ($this->container['cnpj_initiator'] === null) {
            $invalidProperties[] = "'cnpj_initiator' can't be null";
        }
        if ((mb_strlen($this->container['cnpj_initiator']) > 14)) {
            $invalidProperties[] = "invalid value for 'cnpj_initiator', the character length must be smaller than or equal to 14.";
        }

        if (!preg_match("/^\\d{14}$/", $this->container['cnpj_initiator'])) {
            $invalidProperties[] = "invalid value for 'cnpj_initiator', must be conform to the pattern /^\\d{14}$/.";
        }

        if ($this->container['payment'] === null) {
            $invalidProperties[] = "'payment' can't be null";
        }
        if (!is_null($this->container['remittance_information']) && (mb_strlen($this->container['remittance_information']) > 140)) {
            $invalidProperties[] = "invalid value for 'remittance_information', the character length must be smaller than or equal to 140.";
        }

        if (!is_null($this->container['remittance_information']) && !preg_match("/[\\w\\W\\s]*/", $this->container['remittance_information'])) {
            $invalidProperties[] = "invalid value for 'remittance_information', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if ($this->container['creditor_account'] === null) {
            $invalidProperties[] = "'creditor_account' can't be null";
        }
        $allowedValues = $this->getAuthorisationFlowAllowableValues();
        if (!is_null($this->container['authorisation_flow']) && !in_array($this->container['authorisation_flow'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'authorisation_flow', must be one of '%s'",
                $this->container['authorisation_flow'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['transaction_identification']) && (mb_strlen($this->container['transaction_identification']) > 35)) {
            $invalidProperties[] = "invalid value for 'transaction_identification', the character length must be smaller than or equal to 35.";
        }

        if (!is_null($this->container['transaction_identification']) && !preg_match("/^[a-zA-Z0-9]{1,35}$/", $this->container['transaction_identification'])) {
            $invalidProperties[] = "invalid value for 'transaction_identification', must be conform to the pattern /^[a-zA-Z0-9]{1,35}$/.";
        }

        if ($this->container['document'] === null) {
            $invalidProperties[] = "'document' can't be null";
        }
        if (!is_null($this->container['proxy']) && !preg_match("/[\\w\\W\\s]*/", $this->container['proxy'])) {
            $invalidProperties[] = "invalid value for 'proxy', must be conform to the pattern /[\\w\\W\\s]*/.";
        }

        if ($this->container['local_instrument'] === null) {
            $invalidProperties[] = "'local_instrument' can't be null";
        }
        $allowedValues = $this->getLocalInstrumentAllowableValues();
        if (!is_null($this->container['local_instrument']) && !in_array($this->container['local_instrument'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'local_instrument', must be one of '%s'",
                $this->container['local_instrument'],
                implode("', '", $allowedValues)
            );
        }

        if (!is_null($this->container['original_recurring_payment_id']) && (mb_strlen($this->container['original_recurring_payment_id']) > 100)) {
            $invalidProperties[] = "invalid value for 'original_recurring_payment_id', the character length must be smaller than or equal to 100.";
        }

        if (!is_null($this->container['original_recurring_payment_id']) && (mb_strlen($this->container['original_recurring_payment_id']) < 1)) {
            $invalidProperties[] = "invalid value for 'original_recurring_payment_id', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['original_recurring_payment_id']) && !preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", $this->container['original_recurring_payment_id'])) {
            $invalidProperties[] = "invalid value for 'original_recurring_payment_id', must be conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.";
        }

        if (!is_null($this->container['payment_reference']) && (mb_strlen($this->container['payment_reference']) > 10)) {
            $invalidProperties[] = "invalid value for 'payment_reference', the character length must be smaller than or equal to 10.";
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
     * Gets recurring_payment_id
     *
     * @return string
     */
    public function getRecurringPaymentId()
    {
        return $this->container['recurring_payment_id'];
    }

    /**
     * Sets recurring_payment_id
     *
     * @param string $recurring_payment_id Código ou identificador único informado pela instituição detentora da conta para representar a iniciação de pagamento. O `recurringPaymentId` deve ser diferente do `endToEndId`.  Este é o identificador que deverá ser utilizado na consulta ao status da iniciação de pagamento efetuada.
     *
     * @return self
     */
    public function setRecurringPaymentId($recurring_payment_id)
    {
        if (is_null($recurring_payment_id)) {
            throw new \InvalidArgumentException('non-nullable recurring_payment_id cannot be null');
        }
        if ((mb_strlen($recurring_payment_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $recurring_payment_id when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($recurring_payment_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $recurring_payment_id when calling ResponseRecurringPaymentsDataPatch., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", ObjectSerializer::toString($recurring_payment_id)))) {
            throw new \InvalidArgumentException("invalid value for \$recurring_payment_id when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }

        $this->container['recurring_payment_id'] = $recurring_payment_id;

        return $this;
    }

    /**
     * Gets recurring_consent_id
     *
     * @return string|null
     */
    public function getRecurringConsentId()
    {
        return $this->container['recurring_consent_id'];
    }

    /**
     * Sets recurring_consent_id
     *
     * @param string|null $recurring_consent_id Identificador único do consentimento criado para a iniciação de pagamento solicitada. Deverá ser um URN - Uniform Resource Name. Um URN, conforme definido na [RFC8141](https://tools.ietf.org/html/rfc8141) é um Uniform Resource Identifier - URI - que é atribuído sob o URI scheme \"urn\" e um namespace URN específico, com a intenção de que o URN seja um identificador de recurso persistente e independente da localização. Considerando a string urn:bancoex:C1DD33123 como exemplo para `recurringConsentId` temos: - o namespace(urn) - o identificador associado ao namespace da instituição transmissora (bancoex) - o identificador específico dentro do namespace (C1DD33123). Informações mais detalhadas sobre a construção de namespaces devem ser consultadas na [RFC8141](https://tools.ietf.org/html/rfc8141).  [Restrição] Este campo é de preenchimento obrigatório quando o valor do campo authorisationFlow for igual a FIDO_FLOW.
     *
     * @return self
     */
    public function setRecurringConsentId($recurring_consent_id)
    {
        if (is_null($recurring_consent_id)) {
            throw new \InvalidArgumentException('non-nullable recurring_consent_id cannot be null');
        }
        if ((mb_strlen($recurring_consent_id) > 256)) {
            throw new \InvalidArgumentException('invalid length for $recurring_consent_id when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 256.');
        }
        if ((!preg_match("/^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/", ObjectSerializer::toString($recurring_consent_id)))) {
            throw new \InvalidArgumentException("invalid value for \$recurring_consent_id when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^urn:[a-zA-Z0-9][a-zA-Z0-9\\-]{0,31}:[a-zA-Z0-9()+,\\-.:=@;$_!*'%\/?#]+$/.");
        }

        $this->container['recurring_consent_id'] = $recurring_consent_id;

        return $this;
    }

    /**
     * Gets end_to_end_id
     *
     * @return string
     */
    public function getEndToEndId()
    {
        return $this->container['end_to_end_id'];
    }

    /**
     * Sets end_to_end_id
     *
     * @param string $end_to_end_id Trata-se de um identificador único, gerado na instituição iniciadora de pagamento e recebido na instituição detentora de conta, permeando toda a jornada do pagamento Pix.  [Restrição] A detentora deve obrigatoriamente retornar o campo com o mesmo valor recebido da iniciadora.  No caso de Pix Automático, a iniciadora deverá, no que tange á composição do endToEndId, utilizar a data para a qual o Pix está sendo agendado e horário fixo 15:00 UTC, que dará para a detentora a janela de efetivação de 00:00 e 23:59 do horário de Brasília, mesmo a janela sendo, para o detentor, até as 21h.
     *
     * @return self
     */
    public function setEndToEndId($end_to_end_id)
    {
        if (is_null($end_to_end_id)) {
            throw new \InvalidArgumentException('non-nullable end_to_end_id cannot be null');
        }
        if ((mb_strlen($end_to_end_id) > 32)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 32.');
        }
        if ((mb_strlen($end_to_end_id) < 32)) {
            throw new \InvalidArgumentException('invalid length for $end_to_end_id when calling ResponseRecurringPaymentsDataPatch., must be bigger than or equal to 32.');
        }
        if ((!preg_match("/^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/", ObjectSerializer::toString($end_to_end_id)))) {
            throw new \InvalidArgumentException("invalid value for \$end_to_end_id when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^([E])([0-9]{8})([0-9]{4})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(2[0-3]|[01][0-9])([0-5][0-9])([a-zA-Z0-9]{11})$/.");
        }

        $this->container['end_to_end_id'] = $end_to_end_id;

        return $this;
    }

    /**
     * Gets date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->container['date'];
    }

    /**
     * Sets date
     *
     * @param string $date Data em que o pagamento será realizado. Uma string com a utilização de timezone UTC-3 (UTC time format).
     *
     * @return self
     */
    public function setDate($date)
    {
        if (is_null($date)) {
            throw new \InvalidArgumentException('non-nullable date cannot be null');
        }
        if ((mb_strlen($date) > 10)) {
            throw new \InvalidArgumentException('invalid length for $date when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 10.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/", ObjectSerializer::toString($date)))) {
            throw new \InvalidArgumentException("invalid value for \$date when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])$/.");
        }

        $this->container['date'] = $date;

        return $this;
    }

    /**
     * Gets creation_date_time
     *
     * @return \DateTime
     */
    public function getCreationDateTime()
    {
        return $this->container['creation_date_time'];
    }

    /**
     * Sets creation_date_time
     *
     * @param \DateTime $creation_date_time Data e hora em que o pagamento foi criado.  Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339),  sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setCreationDateTime($creation_date_time)
    {
        if (is_null($creation_date_time)) {
            throw new \InvalidArgumentException('non-nullable creation_date_time cannot be null');
        }
        if ((mb_strlen($creation_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $creation_date_time when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($creation_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$creation_date_time when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['creation_date_time'] = $creation_date_time;

        return $this;
    }

    /**
     * Gets status_update_date_time
     *
     * @return \DateTime
     */
    public function getStatusUpdateDateTime()
    {
        return $this->container['status_update_date_time'];
    }

    /**
     * Sets status_update_date_time
     *
     * @param \DateTime $status_update_date_time Data e hora em que o pagamento teve o status atualizado.  Uma string com data e hora conforme especificação [RFC-3339](https://datatracker.ietf.org/doc/html/rfc3339),  sempre com a utilização de timezone UTC(UTC time format).
     *
     * @return self
     */
    public function setStatusUpdateDateTime($status_update_date_time)
    {
        if (is_null($status_update_date_time)) {
            throw new \InvalidArgumentException('non-nullable status_update_date_time cannot be null');
        }
        if ((mb_strlen($status_update_date_time) > 20)) {
            throw new \InvalidArgumentException('invalid length for $status_update_date_time when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 20.');
        }
        if ((!preg_match("/^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/", ObjectSerializer::toString($status_update_date_time)))) {
            throw new \InvalidArgumentException("invalid value for \$status_update_date_time when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^(\\d{4})-(1[0-2]|0?[1-9])-(3[01]|[12][0-9]|0?[1-9])T(?:[01]\\d|2[0123]):(?:[012345]\\d):(?:[012345]\\d)Z$/.");
        }

        $this->container['status_update_date_time'] = $status_update_date_time;

        return $this;
    }

    /**
     * Gets status
     *
     * @return \OpenAPI\Client\Model\EnumPaymentStatusType
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param \OpenAPI\Client\Model\EnumPaymentStatusType $status status
     *
     * @return self
     */
    public function setStatus($status)
    {
        if (is_null($status)) {
            throw new \InvalidArgumentException('non-nullable status cannot be null');
        }
        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets rejection_reason
     *
     * @return \OpenAPI\Client\Model\RejectionReason|null
     */
    public function getRejectionReason()
    {
        return $this->container['rejection_reason'];
    }

    /**
     * Sets rejection_reason
     *
     * @param \OpenAPI\Client\Model\RejectionReason|null $rejection_reason rejection_reason
     *
     * @return self
     */
    public function setRejectionReason($rejection_reason)
    {
        if (is_null($rejection_reason)) {
            throw new \InvalidArgumentException('non-nullable rejection_reason cannot be null');
        }
        $this->container['rejection_reason'] = $rejection_reason;

        return $this;
    }

    /**
     * Gets cnpj_initiator
     *
     * @return string
     */
    public function getCnpjInitiator()
    {
        return $this->container['cnpj_initiator'];
    }

    /**
     * Sets cnpj_initiator
     *
     * @param string $cnpj_initiator CNPJ do Iniciador de Pagamento devidamente habilitado para a prestação de Serviço de Iniciação no Pix.
     *
     * @return self
     */
    public function setCnpjInitiator($cnpj_initiator)
    {
        if (is_null($cnpj_initiator)) {
            throw new \InvalidArgumentException('non-nullable cnpj_initiator cannot be null');
        }
        if ((mb_strlen($cnpj_initiator) > 14)) {
            throw new \InvalidArgumentException('invalid length for $cnpj_initiator when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 14.');
        }
        if ((!preg_match("/^\\d{14}$/", ObjectSerializer::toString($cnpj_initiator)))) {
            throw new \InvalidArgumentException("invalid value for \$cnpj_initiator when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^\\d{14}$/.");
        }

        $this->container['cnpj_initiator'] = $cnpj_initiator;

        return $this;
    }

    /**
     * Gets payment
     *
     * @return \OpenAPI\Client\Model\PaymentPix
     */
    public function getPayment()
    {
        return $this->container['payment'];
    }

    /**
     * Sets payment
     *
     * @param \OpenAPI\Client\Model\PaymentPix $payment payment
     *
     * @return self
     */
    public function setPayment($payment)
    {
        if (is_null($payment)) {
            throw new \InvalidArgumentException('non-nullable payment cannot be null');
        }
        $this->container['payment'] = $payment;

        return $this;
    }

    /**
     * Gets remittance_information
     *
     * @return string|null
     */
    public function getRemittanceInformation()
    {
        return $this->container['remittance_information'];
    }

    /**
     * Sets remittance_information
     *
     * @param string|null $remittance_information Deve ser preenchido sempre que o usuário pagador inserir alguma informação adicional em um pagamento, a ser enviada ao recebedor.
     *
     * @return self
     */
    public function setRemittanceInformation($remittance_information)
    {
        if (is_null($remittance_information)) {
            throw new \InvalidArgumentException('non-nullable remittance_information cannot be null');
        }
        if ((mb_strlen($remittance_information) > 140)) {
            throw new \InvalidArgumentException('invalid length for $remittance_information when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 140.');
        }
        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($remittance_information)))) {
            throw new \InvalidArgumentException("invalid value for \$remittance_information when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['remittance_information'] = $remittance_information;

        return $this;
    }

    /**
     * Gets creditor_account
     *
     * @return \OpenAPI\Client\Model\CreditorAccount
     */
    public function getCreditorAccount()
    {
        return $this->container['creditor_account'];
    }

    /**
     * Sets creditor_account
     *
     * @param \OpenAPI\Client\Model\CreditorAccount $creditor_account creditor_account
     *
     * @return self
     */
    public function setCreditorAccount($creditor_account)
    {
        if (is_null($creditor_account)) {
            throw new \InvalidArgumentException('non-nullable creditor_account cannot be null');
        }
        $this->container['creditor_account'] = $creditor_account;

        return $this;
    }

    /**
     * Gets debtor_account
     *
     * @return \OpenAPI\Client\Model\DebtorAccount|null
     */
    public function getDebtorAccount()
    {
        return $this->container['debtor_account'];
    }

    /**
     * Sets debtor_account
     *
     * @param \OpenAPI\Client\Model\DebtorAccount|null $debtor_account debtor_account
     *
     * @return self
     */
    public function setDebtorAccount($debtor_account)
    {
        if (is_null($debtor_account)) {
            throw new \InvalidArgumentException('non-nullable debtor_account cannot be null');
        }
        $this->container['debtor_account'] = $debtor_account;

        return $this;
    }

    /**
     * Gets cancellation
     *
     * @return \OpenAPI\Client\Model\PixPaymentCancellation|null
     */
    public function getCancellation()
    {
        return $this->container['cancellation'];
    }

    /**
     * Sets cancellation
     *
     * @param \OpenAPI\Client\Model\PixPaymentCancellation|null $cancellation cancellation
     *
     * @return self
     */
    public function setCancellation($cancellation)
    {
        if (is_null($cancellation)) {
            throw new \InvalidArgumentException('non-nullable cancellation cannot be null');
        }
        $this->container['cancellation'] = $cancellation;

        return $this;
    }

    /**
     * Gets authorisation_flow
     *
     * @return string|null
     */
    public function getAuthorisationFlow()
    {
        return $this->container['authorisation_flow'];
    }

    /**
     * Sets authorisation_flow
     *
     * @param string|null $authorisation_flow Campo condicional utilizado para identificar o fluxo de autorização em que o pagamento foi solicitado.  [Restrição] Se CIBA ou FIDO, preenchimento obrigatório. Caso o campo não esteja presente no payload, subentende-se que o fluxo de autorização utilizado é o HYBRID_FLOW.
     *
     * @return self
     */
    public function setAuthorisationFlow($authorisation_flow)
    {
        if (is_null($authorisation_flow)) {
            throw new \InvalidArgumentException('non-nullable authorisation_flow cannot be null');
        }
        $allowedValues = $this->getAuthorisationFlowAllowableValues();
        if (!in_array($authorisation_flow, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'authorisation_flow', must be one of '%s'",
                    $authorisation_flow,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['authorisation_flow'] = $authorisation_flow;

        return $this;
    }

    /**
     * Gets transaction_identification
     *
     * @return string|null
     */
    public function getTransactionIdentification()
    {
        return $this->container['transaction_identification'];
    }

    /**
     * Sets transaction_identification
     *
     * @param string|null $transaction_identification Trata-se de um identificador de transação que deve ser retransmitido intacto pelo PSP do pagador ao gerar a ordem de pagamento.  Essa informação permitirá ao recebedor identificar e correlacionar a transferência, quando recebida, com a apresentação das instruções ao pagador.  Os caracteres permitidos no contexto do Pix para o campo txid (EMV 62-05) são:Letras minúsculas, de 'a' a 'z' Letras maiúsculas, de 'A' a 'z' Dígitos decimais, de '0' a '9'.  [Restrição] Preenchimento condicional de acordo com o conteúdo do campo “localInstrument”:  MANU - O campo transactionIdentification não deve ser preenchido;   DICT - O campo transactionIdentification não deve ser preenchido;   INIC - O campo transactionIdentification deve ser preenchido obrigatoriamente e deve conter até 25 caracteres alfanuméricos ([a-z|A-Z|0-9]).
     *
     * @return self
     */
    public function setTransactionIdentification($transaction_identification)
    {
        if (is_null($transaction_identification)) {
            throw new \InvalidArgumentException('non-nullable transaction_identification cannot be null');
        }
        if ((mb_strlen($transaction_identification) > 35)) {
            throw new \InvalidArgumentException('invalid length for $transaction_identification when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 35.');
        }
        if ((!preg_match("/^[a-zA-Z0-9]{1,35}$/", ObjectSerializer::toString($transaction_identification)))) {
            throw new \InvalidArgumentException("invalid value for \$transaction_identification when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^[a-zA-Z0-9]{1,35}$/.");
        }

        $this->container['transaction_identification'] = $transaction_identification;

        return $this;
    }

    /**
     * Gets document
     *
     * @return \OpenAPI\Client\Model\CreateRecurringPixPaymentDataDocument
     */
    public function getDocument()
    {
        return $this->container['document'];
    }

    /**
     * Sets document
     *
     * @param \OpenAPI\Client\Model\CreateRecurringPixPaymentDataDocument $document document
     *
     * @return self
     */
    public function setDocument($document)
    {
        if (is_null($document)) {
            throw new \InvalidArgumentException('non-nullable document cannot be null');
        }
        $this->container['document'] = $document;

        return $this;
    }

    /**
     * Gets proxy
     *
     * @return string|null
     */
    public function getProxy()
    {
        return $this->container['proxy'];
    }

    /**
     * Sets proxy
     *
     * @param string|null $proxy Chave cadastrada no DICT pertencente ao recebedor. Os tipos de chaves podem ser: telefone, e-mail, cpf/cnpj ou chave aleatória.  No caso de telefone celular deve ser informado no padrão E.1641. Para e-mail deve ter o formato xxxxxxxx@xxxxxxx.xxx(.xx) e no máximo 77 caracteres.  No caso de CPF deverá ser informado com 11 números, sem pontos ou traços. Para o caso de CNPJ deverá ser informado com 14 números, sem pontos ou traços.  No caso de chave aleatória deve ser informado o UUID gerado pelo DICT, conforme formato especificado na [RFC4122](https://tools.ietf.org/html/rfc4122).  Se informado, a detentora da conta deve validar o proxy no DICT quando localInstrument for igual a DICT e validar o campo creditorAccount.  Esta validação é opcional caso o localInstrument for igual a INIC.  [Restrição] Se localInstrument for igual a DICT, o campo proxy deve ser preenchido.  [Restrição] Caso o campo “/data/localInstrument” seja enviado como “MANU”, o campo “/data/proxy” não deve ser informado
     *
     * @return self
     */
    public function setProxy($proxy)
    {
        if (is_null($proxy)) {
            throw new \InvalidArgumentException('non-nullable proxy cannot be null');
        }

        if ((!preg_match("/[\\w\\W\\s]*/", ObjectSerializer::toString($proxy)))) {
            throw new \InvalidArgumentException("invalid value for \$proxy when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /[\\w\\W\\s]*/.");
        }

        $this->container['proxy'] = $proxy;

        return $this;
    }

    /**
     * Gets local_instrument
     *
     * @return string
     */
    public function getLocalInstrument()
    {
        return $this->container['local_instrument'];
    }

    /**
     * Sets local_instrument
     *
     * @param string $local_instrument Especifica a forma de iniciação do pagamento - MANU - Inserção manual de dados da conta transacional - DICT - Inserção manual de chave Pix - INIC - Indica que o recebedor (creditor) contratou o Iniciador de Pagamentos especificamente para realizar iniciações de pagamento em que o beneficiário é previamente conhecido  [Restrição] Caso consentimento associado a tentativa de pagamento seja para Pix automático (objeto “automatic” selecionado no oneOf do campo \"/data/recurringConfiguration\"), apenas o método MANU é permitido.
     *
     * @return self
     */
    public function setLocalInstrument($local_instrument)
    {
        if (is_null($local_instrument)) {
            throw new \InvalidArgumentException('non-nullable local_instrument cannot be null');
        }
        $allowedValues = $this->getLocalInstrumentAllowableValues();
        if (!in_array($local_instrument, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'local_instrument', must be one of '%s'",
                    $local_instrument,
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['local_instrument'] = $local_instrument;

        return $this;
    }

    /**
     * Gets original_recurring_payment_id
     *
     * @return string|null
     */
    public function getOriginalRecurringPaymentId()
    {
        return $this->container['original_recurring_payment_id'];
    }

    /**
     * Sets original_recurring_payment_id
     *
     * @param string|null $original_recurring_payment_id Campo que contém o código ou o identificador da tentativa original de pagamento que falhou.  A tentativa de pagamento original é a primeira tentativa (Intradia – Primeira Tentativa, vide documentação) realizada para o pagamento de uma determinada recorrência.  Código ou identificador único informado pela instituição detentora da conta para representar a iniciação de pagamento.  O recurringPaymentId deve ser diferente do endToEndId.  Este é o identificador que deverá ser utilizado na consulta ao status da iniciação de pagamento efetuada.  [Restrição] Este campo é de envio obrigatório pela Iniciadora quando for uma nova tentativa de liquidação de pagamento que falhou anteriormente.
     *
     * @return self
     */
    public function setOriginalRecurringPaymentId($original_recurring_payment_id)
    {
        if (is_null($original_recurring_payment_id)) {
            throw new \InvalidArgumentException('non-nullable original_recurring_payment_id cannot be null');
        }
        if ((mb_strlen($original_recurring_payment_id) > 100)) {
            throw new \InvalidArgumentException('invalid length for $original_recurring_payment_id when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 100.');
        }
        if ((mb_strlen($original_recurring_payment_id) < 1)) {
            throw new \InvalidArgumentException('invalid length for $original_recurring_payment_id when calling ResponseRecurringPaymentsDataPatch., must be bigger than or equal to 1.');
        }
        if ((!preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/", ObjectSerializer::toString($original_recurring_payment_id)))) {
            throw new \InvalidArgumentException("invalid value for \$original_recurring_payment_id when calling ResponseRecurringPaymentsDataPatch., must conform to the pattern /^[a-zA-Z0-9][a-zA-Z0-9\\-]{0,99}$/.");
        }

        $this->container['original_recurring_payment_id'] = $original_recurring_payment_id;

        return $this;
    }

    /**
     * Gets payment_reference
     *
     * @return string|null
     */
    public function getPaymentReference()
    {
        return $this->container['payment_reference'];
    }

    /**
     * Sets payment_reference
     *
     * @param string|null $payment_reference [Restrição] Campo de preenchimento obrigatório caso seja um pagamento de Pix automático, caso não respeitado, a instituição detentora deve retornar erro HTTP 422 com o código DETALHE_PAGAMENTO_INVALIDO.  - Primeiro pagamento: Se for o pagamento inicial especificado em “/data/firstPayment”, preencha o campo com a string fixa “zero”. - Semanal: Preencha com W$numSemana-$ano, onde $numSemana representa o número da semana no ano. Exemplo: \"W50-2024\". - Mensal: Use M$mês-$ano, onde $mês representa o mês com dois dígitos. Exemplo: \"M09-2024\". - Trimestral: Utilize Q$trimestre-$ano, onde $trimestre indica o trimestre do ano (1 a 4).   - Janeiro a Março: Q1-$ano (ex.: \"Q1-2024\").   - Abril a Junho: Q2-$ano (ex.: \"Q2-2024\").   - Julho a Setembro: Q3-$ano (ex.: \"Q3-2024\").   - Outubro a Dezembro: Q4-$ano (ex.: \"Q4-2024\"). - Semestral: Utilize $semestre-$ano, onde $semestre indica o semestre do ano (1 para janeiro a junho e 2 para julho a dezembro).   - Janeiro a Junho: S1-$ano (ex.: \"S1-2024\").   - Julho a Dezembro: S2-$ano (ex.: \"S2-2024\"). - Anual: Use Y$ano, apenas com o ano. Exemplo: \"Y2024\".   - Exemplo de Formatos:     - Primeiro pagamento: \"zero\"     - Semanal: \"W50-2024\"     - Mensal: \"M09-2024\"     - Trimestral: \"Q3-2024\"     - Semestral: \"S2-2024\"     - Anual: \"Y2024\"
     *
     * @return self
     */
    public function setPaymentReference($payment_reference)
    {
        if (is_null($payment_reference)) {
            throw new \InvalidArgumentException('non-nullable payment_reference cannot be null');
        }
        if ((mb_strlen($payment_reference) > 10)) {
            throw new \InvalidArgumentException('invalid length for $payment_reference when calling ResponseRecurringPaymentsDataPatch., must be smaller than or equal to 10.');
        }

        $this->container['payment_reference'] = $payment_reference;

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


