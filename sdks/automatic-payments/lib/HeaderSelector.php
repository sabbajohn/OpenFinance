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
