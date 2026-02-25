<?php
/**
 * ObjectSerializer
 *
 * PHP version 8.1
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * API Pix
 *
 * A API Pix padroniza serviços oferecidos pelo PSP recebedor no contexto do arranjo Pix, direcionando: - o gerenciamentos de cobranças, com e sem recorrências, em lotes ou não; - o acompanhamento dos Pix e suas devoluções; - as consultas.  Os serviços expostos pelo PSP recebedor permitem ao usuário recebedor estabelecer integração de sua automação com os serviços Pix do PSP.  # Evolução da API Pix  A API Pix busca respeitar __[SemVer](https://semver.org/lang/pt-BR/)__. Nesse sentido, mudanças compatíveis não devem gerar nova versão _major_.  A versão da API é composta por 4 elementos: _major_, _minor_, _patch_ e _release candidate_. A versão `v[x]`que consta no path da URL é o elemento _major_ da versão da API. A evolução da versão se dá seguinte forma:    - Major: alterações incompatíveis, com quebra de contrato (v1.0.0 → v2.0.0)    - Minor: alterações compatíveis, sem quebra de contrato (v1.1.0 → v1.2.0)   - Patch: bugfixes, esclarecimentos às especificações, sem alterações funcionais (v1.1.1 → v1.1.2)   - Release candidate: versões de pré-lançamento de qualquer patch futuro, minor ou major (v1.0.0-rc.1 → v1.0.0-rc.22)  Alterações sem quebra de contrato e esclarecimentos às especificações podem ocorrer a qualquer momento. Clientes devem estar preparados para lidar com essas mudanças sem quebrar.  As seguintes mudanças são esperadas e consideradas retrocompatíveis:  - Adição de novos recursos na API Pix; - Adição de novos parâmetros opcionais; - Adição de novos campos em respostas da API Pix; - Alteração da ordem de campos; - Adição de novos elementos em enumerações.   # Tratamento de erros  A API Pix retorna códigos de status HTTP para indicar sucesso ou falhas das requisições, são eles: - Códigos `2xx` indicam sucesso;  - Códigos `4xx` indicam falhas causadas pelas informações enviadas pelo cliente ou pelo estado atual das entidades e; - Códigos `5xx` indicam problemas no serviço no lado da API Pix.  As respostas de erro incluem no corpo detalhes do erro seguindo o _schema_ da [RFC 7807](https://tools.ietf.org/html/rfc7807).  O campo `type` identifica o tipo de erro e na API Pix segue o padrão:  `https://pix.bcb.gov.br/api/v2/error/<TipoErro>`  O padrão acima listado, referente ao campo `type`, não consiste, necessariamente, em uma URL que apresentará uma página web válida, ou um endpoint válido, embora possa, futuramente, ser exatamente o caso. O objetivo primário é apenas e tão somente identificar o tipo de erro.  Convém reforçar que a API Pix contempla uma lista de produtos e respectivas funcionalidades ofertadas pelo PSP recebedor.  Cabe à relação contratual com cada usuário recebedor a concessão da totalidade ou de um subconjunto de acessos relacionados aos produtos ofertados. Por exemplo, o usuário recebedor, ao acessar uma funcionalidade não contemplada  no seu escopo contratual, receberá o erro geral `AcessoNegado` descrito na próxima seção.  Abaixo estão listados os tipos de erro e possíveis violações da API Pix.  ## Gerais  Esta seção reúne erros que poderiam ser retornados por quaisquer endpoints listados na API Pix.  ### `RequisicaoInvalida`    * __Significado__: Requisição inválida.   * __HTTP Status Code__: [400 Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1).  ### `AcessoNegado`    * __Significado__: Requisição de participante autenticado que viola alguma regra de autorização.   * __HTTP Status Code__: [403 Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3).  ### `NaoEncontrado`    * __Significado__: Entidade não encontrada.   * __HTTP Status Code__: [404 Not Found](https://tools.ietf.org/html/rfc7231#section-6.5.4).  ### `PermanentementeRemovido`    * __Significado__: Indica que a entidade existia, mas foi permanentemente removida.   * __HTTP Status Code__: [410 Gone](https://tools.ietf.org/html/rfc7231#section-6.5.9).  ### `ErroInternoDoServidor`    * __Significado__: Condição inesperada ao processar requisição.   * __HTTP Status Code__: [500 Internal Server Error](https://tools.ietf.org/html/rfc7231#section-6.6.1).  ### `ServicoIndisponivel`    * __Significado__: Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.   * __HTTP Status Code__: [503 Service Unavailable](https://tools.ietf.org/html/rfc7231#section-6.6.4).  ### `IndisponibilidadePorTempoEsgotado`    * __Significado__: Indica que o serviço demorou além do esperado para retornar.   * __HTTP Status Code__: [504 Gateway Timeout](https://tools.ietf.org/html/rfc7231#section-6.6.5).  ## Tag CobPayload   Esta seção reúne erros retornados pelos endpoints organizados sob a tag `CobPayload`. Estes erros indicam problemas na tentativa de recuperação, via _location_, do Payload JSON que representa a cobrança.  ### `CobPayloadNaoEncontrado`  * __Significado__: a cobrança em questão não foi encontrada para a location requisitada. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4) ou [410](https://tools.ietf.org/html/rfc7231#section-6.5.9). * __endpoints__: `GET /{pixUrlAccessToken}`, `GET /cobv/{pixUrlAccessToken}`.  Se a presente location exibia uma cobrança, mas não a exibirá mais de maneira permanentemente, pode-se aplicar o HTTP status code [410](https://tools.ietf.org/html/rfc7231#section-6.5.9). Se a presente location não está exibindo nenhuma cobrança, pode-se utilizar o HTTP status code [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).  Uma cobrança pode estar \"expirada\" (`calendario.expiracao`), \"vencida\", \"Concluida\", entre outros estados em que não poderia ser efetivamente paga. Nesses casos, é uma liberalidade do PSP recebedor retornar o presente código de erro ou optar por servir o payload de qualquer maneira, objetivando fornecer uma informação adicional ao usuário pagador final a respeito da cobrança.  ### `CobPayloadOperacaoInvalida`  * __Significado__: a cobrança existe, mas a requisição é inválida. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /cobv/{pixUrlAccessToken}`.  __Violações__:   - `codMun` não respeita o _schema_.   - `codMun` não é um código válido segundo a __[tabela de municípios do IBGE](https://www.ibge.gov.br/explica/codigos-dos-municipios.php)__.   - `DPP` não respeita o _schema_.   - `DPP` anterior ao momento presente.   - `DPP` superior à validade da cobrança em função dos parâmetros `calendario.dataDeVencimento`   e `calendario.validadeAposVencimento`. Exemplo: `dataDeVencimento` => 2020-12-25,   `validadeAposVencimento` => 10, `DPP` => 2021-01-05. Neste exemplo, o parâmetro `DPP` é   inválido considerando o contexto apresentado porque é uma data em que a cobrança   não poderá ser paga. A cobrança, neste exemplo, não será considerada válida   a partir da data 2021-01-05.  ## Tag RecPayload   Esta seção reúne erros retornados pelos endpoints organizados sob a tag `RecPayload`. Estes erros indicam problemas na tentativa de recuperação, via _location_, do Payload JSON que representa a recorrência.  ### `RecPayloadNaoEncontrado`  * __Significado__: a recorrência em questão não foi encontrada para a location requisitada. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4) ou [410](https://tools.ietf.org/html/rfc7231#section-6.5.9). * __endpoint__: `GET /rec/{recUrlAccessToken}`.  Se a presente location exibia uma recorrência, mas não a exibirá mais de maneira permanentemente, pode-se aplicar o HTTP status code [410](https://tools.ietf.org/html/rfc7231#section-6.5.9). Se a presente location não está exibindo nenhuma recorrência, pode-se utilizar o HTTP status code [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).  Uma recorrência pode estar expirada, cancelada ou rejeitada, nesses casos, é uma liberalidade do PSP recebedor retornar o presente código de erro ou optar por servir o payload de qualquer maneira, objetivando fornecer uma informação adicional ao usuário pagador final a respeito da recorrência.  ### `RecPayloadOperacaoInvalida`  * __Significado__: a recorrência em questão encontra-se em expirada, rejeitada ou cancelada para a location requisitada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoint__: `GET /rec/{recUrlAccessToken}`.  __Violações__ para o endpoint `GET /rec/{recUrlAccessToken}`: - O campo `recUrlAccessToken` referencia uma recorrência expirada, rejeitada ou cancelada.  ## Tag Rec  Esta seção reúne erros retornados pelos endpoints organizados sob a tag `Rec`. Esses erros indicam problemas no gerenciamento de uma recorrência.  ### `RecNaoEncontrada`  * __Significado__: Recorrência não encontrada para o idRec informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET|PATCH] /rec/{idRec}`.   ### `RecOperacaoInvalida`  * __Significado__: a requisição que busca alterar ou criar uma recorrência não respeita o _schema_ ou está semanticamente errada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `POST /rec` e `PATCH /rec/{idRec}`.  __Violações__ para o endpoint `POST /rec`:   - O objeto `rec.vinculo` não respeita o _schema_.   - O campo `rec.calendario.dataInicial` é anterior à data de criação da recorrência.   - O campo `rec.calendario.dataFinal` é anterior ao campo `rec.calendario.dataInicial`.   - O campo `rec.calendario.periodicidade` não respeita o _schema_.   - O objeto `rec.valor` não respeita o _schema_.   - O campo `rec.valor.valorRec` não respeita o _schema_.   - O campo `rec.valor.valorMinimoRecebedor` não respeita o _schema_.   - Ambos os campos `rec.valor.valorRec` e `rec.valor.valorMinimoRecebedor` estão preenchidos.   - O objeto `rec.recebedor` não respeita o _schema_.   - O campo `rec.politicaRetentativa` não respeita o _schema_.   - O location referenciado por `rec.loc` inexiste.   - O location referenciado por `rec.loc` já está sendo utilizado por outra recorrência.   - O valor do campo `rec.recebedor.convenio` não é aceito pelo PSP Recebedor.  __Violações__ para o endpoint `PATCH /rec/{idRec}`:    - O campo `rec.calendario.dataInicial` é anterior à data de criação da recorrência.   - O location referenciado por `rec.loc` inexiste.   - O location referenciado por `rec.loc` já está sendo utilizado por outra recorrência.   - O campo `rec.status` não respeita o _schema_.   - A recorrência encontra-se expirada, cancelada ou rejeitada.   - O campo `rec.loc` somente pode ser alterado quando a recorrência apresentar-se com o status CRIADA.   - O campo `rec.calendario.dataInicial` somente pode ser alterado quando a recorrência apresentar-se com o status CRIADA.   - O campo `rec.dadosJornada.txid` não pode ser alterado quando a recorrência apresentar-se com o status REJEITADA ou CANCELADA.  ### `RecConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de recorrências que não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /rec` e `GET /rec/{idRec}`.  __Violações__ específicas para o endpoint `GET /rec`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  __Violações__ específicas para o endpoint `GET /rec/{idRec}`:    - o parâmetro `txid` não corresponde a uma cobrança compatível com o campo `ativacao.tipoJornada`. (_Exemplo: `txid` correspondente a uma CobV e `ativação.tipoJornada` igual a JORNADA_3._)   - o parâmetro `txid` corresponde a uma cobrança imediata diferente da informada no campo `ativação.dadosJornada.txid`. Esta violação não ocorre caso o parâmetro txid corresponda a uma cobrança com vencimento.  ## Tag SolicRec  Esta seção reúne erros retornados pelos endpoints organizados sob a tag `SolicRec`. Esses erros indicam problemas no gerenciamento de uma solicitação de confirmação de recorrência.  ### `SolicRecNaoEncontrada`  * __Significado__: Solicitação de recorrência não encontrada para o idSolicRec informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET] /solicrec/{idSolicRec}`.  ### `SolicRecOperacaoInvalida`  * __Significado__: a requisição que busca criar ou alterar uma solicitação de confirmação de recorrência não respeita o _schema_ ou está semanticamente errada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `[POST] /solicrec` e `PATCH /solicrec/{idSolicRec}`.  __Violações__ para o endpoint `POST /solicrec`:   - O objeto `solicrec.calendario` não respeita o _schema_.   - O campo `solicrec.calendario.dataExpiracaoSolicitacao` é anterior à data de criação da solicitação da recorrência.   - O objeto `solicrec.destinatario` não respeita o _schema_.   - Existe uma solicitação ativa referente ao mesmo `solicrec.idRec`.  __Violações__ para o endpoint `PATCH /solicrec/{idSolicRec}`:    - Não é possível cancelar uma solicitação de recorrência com o status diferente de CRIADA, ENVIADA ou RECEBIDA.  ## Tag CobR  Esta seção reúne erros retornados pelos endpoints organizados sob a tag `CobR`. Esses erros indicam problemas no gerenciamento de uma cobrança recorrente.  ### `CobRNaoEncontrado`  * __Significado__: Cobrança não encontrada para o txid informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET|PATCH] /cobr/{txid}` e  `[POST] /cobr/{txid}/retentativa/{data}`.  ### `CobROperacaoInvalida`  * __Significado__: a requisição que busca alterar ou criar uma cobrança recorrente não respeita o _schema_ ou está semanticamente errada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `[POST|PUT|PATCH] /cobr/{txid}` e  `[POST] /cobr/{txid}/retentativa/{data}`.  __Violações__ para o endpoint `POST|PUT /cobr/{txid}`:   - O campo `cobr.infoAdicional` não respeita o _schema_.   - O campo `cobr.status` não respeita o _schema_.   - O objeto `cobr.calendario` não respeita o _schema_.   - O campo `cobr.calendario.dataDeVencimento` é anterior à data de criação da cobrança.   - O campo `cobr.valor` não respeita o _schema_.   - O objeto `cobr.recebedor` não respeita o _schema_.   - Os campos `cobr.recebedor.conta` e `cobr.recebedor.agencia` correspondem a uma conta que não pertence a este usuário recebedor.   - O objeto `cobr.devedor` não respeita o _schema_.   - O campo `cobr.txid` encontra-se em uso.   - Existe uma CobR com status diferente de REJEITADA e CANCELADA referente ao mesmo `cobr.idRec` com `calendario.dataDeVencimento` no mesmo ciclo.  __Violações__ para o endpoint `PATCH /cobr/{txid}`:    - Não é possível cancelar uma cobrança em uma data igual ou maior que a data prevista da primeira tentativa de liquidação.  __Violações__ para o endpoint `POST /cobr/{txid}/retentativa/{data}`:    - Existe uma tentativa com status `SOLICITADA` ou `AGENDADA`.   - Existe uma tentativa em andamento.   - Existe uma tentativa ativa.   - Existe uma tentativa não finalizada.   - Existe uma tentativa vigente para a `data` informada.   - O parâmetro `data` não corresponde a uma data futura.   - A política configurada na recorrência não permite retentativa de cobrança.  ### `CobRConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de cobranças que não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /cobr` e `GET /cobr/{txid}`.  __Violações__ específicas para o endpoint `GET /cobr`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ## Tag Cob  Esta seção reúne erros retornados pelos endpoints organizados sob a tag `Cob`. Esses erros indicam problemas no gerenciamento de uma cobrança para pagamento imediato.  ### `CobNaoEncontrado`  * __Significado__: Cobrança não encontrada para o txid informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET|PATCH] /cob/{txid}`.  ### `CobOperacaoInvalida`  * __Significado__: a requisição que busca alterar ou criar uma cobrança para pagamento imediato não respeita o _schema_ ou está semanticamente errada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `[POST|PUT|PATCH] /cob/{txid}`.  __Violações__ para os endpoints `PUT|PATCH /cob/{txid}`:   - O campo `cob.calendario.expiracao` é igual ou menor que `zero`.   - O campo `cob.valor.original` não respeita o _schema_.   - O campo `cob.valor.original` é `zero`.   - O objeto `cob.devedor` não respeita o _schema_.   - O campo `cob.chave` não respeita o _schema_.   - O campo `cob.chave` corresponde a uma conta que não pertence a este usuário recebedor.   - O campo `solicitacaoPagador` não respeita o _schema_.   - O objeto `infoAdicionais` não respeita o _schema_.   - O `location` referenciado por `loc.id` inexiste.   - O `location` referenciado por `loc.id` já está sendo utilizado por outra cobrança.   - O `location` referenciado por `cob.loc.id` apresenta tipo \"cobv\" (deveria ser \"cob\").  __Violações__ específicas para o endpoint `PUT /cob/{txid}`:   - A cobrança já existe, não está no status ATIVA, e a presente requisição busca alterá-la.  __Violações__ específicas para o endpoint `PATCH /cob/{txid}`:   - A cobrança não está ATIVA, e a presente requisição busca alterá-la.   - A cobrança está ATIVA, e a presente requisição propõe alterar   seu status para _REMOVIDA_PELO_USUARIO_RECEBEDOR_ juntamente com outras alterações   (não faz sentido remover uma cobrança ao mesmo tempo em que se realizam   alterações que não serão aproveitadas).   - o campo `cob.status` não respeita o _schema_.  ### `CobConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de cobranças para pagamento imediato não respeitam o _schema_ ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /cob` e `GET /cob/{txid}`.  __Violações__ específicas para o endpoint `GET /cob`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  __Violações__ específicas para o endpoint `GET /cob/{txid}`:   - o parâmetro `revisao` corresponde a uma revisão inexistente para a cobrança   apontada pelo parâmetro `txid`.  ## Tag CobV  Esta seção reúne erros retornados pelos endpoints organizados sob a tag `CobV`. Esses erros indicam problemas no gerenciamento de uma cobrança com vencimento.  ### `CobVNaoEncontrada`  * __Significado__: Cobrança com vencimento não encontrada para o txid informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET|PATCH] /cobv/{txid}`.  ### `CobVOperacaoInvalida`  * __Significado__: a requisição que busca alterar ou criar uma cobrança com vencimento não respeita o _schema_ ou está semanticamente errada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `[PUT|PATCH] /cobv/{txid}`.  __Violações__ para os endpoints `PUT|PATCH /cobv/{txid}`:   - Este `txid` está associado a um lote e no referido lote, o status desta cobrança está atribuído como   \"EM_PROCESSAMENTO\" ou \"NEGADA\".   - O campo `cobv.calendario.dataDeVencimento` é anterior à data de criação da cobrança.   - O campo `cobv.calendario.validadeAposVencimento` é menor do que zero.   - O objeto `cobv.devedor` não respeita o _schema_.   - O campo `cobv.chave` não respeita o _schema_.   - O campo `cobv.chave` corresponde a uma conta que não pertence a este usuário recebedor.   - O campo `solicitacaoPagador` não respeita o _schema_.   - O objeto `infoAdicionais` não respeita o _schema_.   - O location referenciado por `cobv.loc.id` inexiste.   - O location referenciado por `cobv.loc.id` já está sendo utilizado por outra cobrança.   - O location referenciado por `cobv.loc.id` apresenta tipo \"cob\" (deveria ser \"cobv\").   - O campo `cobv.valor.original` não respeita o _schema_.   - O campo `cobv.valor.original` apresenta o valor `zero`.   - O objeto `cobv.valor.multa` não respeita o _schema_.   - O objeto `cobv.valor.juros` não respeita o _schema_.   - O objeto `cobv.valor.abatimento` não respeita o _schema_.   - O objeto `cobv.valor.desconto` não respeita o _schema_.   - O objeto `cobv.valor.abatimento` representa um valor maior ou igual ao valor da   cobrança original ou maior ou igual a 100%.   - O objeto `cobv.valor.desconto` apresenta algum elemento de desconto que representa um valor maior ou   igual ao valor da cobrança original ou maior ou igual a 100%.   - O objeto `cobv.valor.desconto` apresenta algum elemento cuja data seja posterior à data de vencimento   representada por `calendario.dataDeVencimento`.   - O objeto `cobv.valor.desconto` apresenta modalidade no valor `1` ou `2`,   porém `cobv.valor.desconto.valorPerc` encontra-se preenchido   - O objeto `cobv.valor.desconto` apresenta modalidade no valor `1` ou `2`, porém   o array `cobv.valor.desconto.descontoDataFixa` está vazio ou nulo.   - O objeto `cobv.valor.desconto` apresenta modalidade nos valores de `3` a `6`, porém   o elemento `cobv.valor.desconto.valorPerc` não está preenchido.   - O objeto `cobv.valor.desconto` apresenta modalidade nos valores de `3` a `6`, porém   o elemento `cobv.valor.desconto.descontoDataFixa` está preenchido ou não nulo.    __Violações__ específicas para o endpoint `PUT /cobv/{txid}`:   - A cobrança já existe, não está ATIVA, e a presente requisição busca alterá-la  __Violações__ específicas para o endpoint `PATCH /cobv/{txid}`:   - A cobrança não está ATIVA, e a presente requisição busca alterá-la   - A cobrança está ATIVA, e a presente requisição propõe alterar   seu status para _REMOVIDA_PELO_USUARIO_RECEBEDOR_ juntamente com outras alterações   (não faz sentido remover uma cobrança ao mesmo tempo em que se realizam   alterações que não serão aproveitadas).   - o campo `cob.status` não respeita o _schema_.  ### `CobVConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de cobranças com vencimento não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /cobv` e `GET /cobv/{txid}`.  __Violações__ específicas para o endpoint `GET /cobv`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  __Violações__ específicas para o endpoint `GET /cobv/{txid}`:   - o parâmetro `revisao` corresponde a uma revisão inexistente para a cobrança   apontada pelo parâmetro `txid`.  ## Tag LoteCobV Esta seção reúne erros referentes a endpoints que tratam do gerenciamento de lotes de cobrança.  ### `LoteCobVNaoEncontrado` * __Significado__: Lote não encontrado para o `id` informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET|PATCH] /lotecobv/{id}`.  ### `LoteCobVOperacaoInvalida` * __Significado__: a requisição que busca alterar ou criar um lote de cobranças com vencimento não respeita o _schema_ ou está semanticamente errada. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `[PUT|PATCH] /lotecobv/{id}`.  __Violações__ para os endpoints `PUT|PATCH /lotecobv/{id}`:   - O campo `loteCobV.descricao` não respeita o _schema_.   - O objeto `loteCobV.cobsV` não respeita o _schema_.  __Violações__ para o endpoint `PUT /lotecobv/{id}`:   - a presente requisição tenta criar um conjunto de cobranças dentre as quais pelo menos   uma cobrança já encontra-se criada.   - a presente requisição busca alterar um lote já existente, entretanto contém um array de   solicitações de alteração de cobranças que não referencia exatamente as mesmas cobranças   referenciadas pela requisição original que criou o lote.   Uma vez criado um lote, não se pode remover ou adicionar solicitações de   criação ou alteração de cobranças a este lote.  __Violações__ para o endpoint `PATCH /lotecobv/{id}`:   - a presente requisição busca alterar um lote já existente e contém, no `array`   de cobranças representado por `cobsv`, uma cobrança não existente no array de cobranças   atribuído pela requisição original que criou o lote.   Uma vez criado um lote, não se pode remover ou adicionar cobranças a este lote.  __Violações__ para os endpoints `GET /lotecobv/{id}`:   - __observação__: para cada elemento do array `cobsv`, retornado por este endpoint, caso a requisição de criação de cobrança esteja em   status \"NEGADA\", o atributo `problema` deste elemento deve ser preenchido respeitando   o [schema](https://tools.ietf.org/html/rfc7807) referenciado pela API Pix.   - o preenchimento do atributo `problema`, conforme descrito acima, segue o mesmo regramento dos   erros especificados para os endpoints `[PUT/PATCH /cobv/{txid}]`, de maneira a possibilitar, ao   usuário recebedor, entender qual foi a violação cometida ao se tentar criar   a cobrança referenciada por este elemento do array `cobsv`.  ### `LoteCobVConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de lotes de cobrança com vencimento não respeitam o _schema_ ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /lotecobv` e `GET /lotecobv/{id}`.  __Violações__ específicas para o endpoint `GET /lotecobv`:   - algum dos parâmetros informados para a consulta não respeitam o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ## Tag PayloadLocation Esta seção reúne erros referentes a endpoints que tratam do gerenciamento de _locations_.  ### `PayloadLocationNaoEncontrado` * __Significado__: _Location_ não encontrada para o `id` informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET|PATCH] /loc/{id}`, `DELETE /loc/{id}/txid`.  ### `PayloadLocationOperacaoInvalida`  * __Significado__: a presente requisição busca criar uma location sem respeitar o _schema_ estabelecido. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `POST /loc`.  __Violações__ para o endpoint `POST /loc`:   - o campo `tipoCob` não respeita o _schema_.  ### `PayloadLocationConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de _locations_ não respeitam o _schema_ ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /loc` e `GET /loc/{id}`.  __Violações__ específicas para o endpoint `GET /loc`:   - algum dos parâmetros informados para a consulta não respeitam o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ## Tag PayloadLocationRec Esta seção reúne erros referentes a endpoints que tratam do gerenciamento de _locations_ de uma recorrência.  ### `PayloadLocationRecNaoEncontrado` * __Significado__: _Location_ não encontrada para o `id` informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `[GET] /locrec/{id}`, `DELETE /locrec/{id}/idRec`.  ### `PayloadLocationRecConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de _locations_ não respeitam o _schema_ ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /locrec` e `GET /locrec/{id}`.  __Violações__ específicas para o endpoint `GET /locrec`:   - algum dos parâmetros informados para a consulta não respeitam o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ## Tag Pix  Reúne erros em endpoints de gestão de Pix recebidos e solicitação de devoluções.  ### `PixNaoEncontrado`  * __Significado__: pix não encontrada para o `e2eid` informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `GET /pix/{e2eid}`  ### `PixDevolucaoNaoEncontrada`  * __Significado__: devolução representada por {id} não encontrada para o `e2eid` informado. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `GET /pix/{e2eid}/devolucao/{id}`  ### `PixConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de pix recebidos não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /pix`.  __Violações__ específicas para o endpoint `GET /pix`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ### `PixDevolucaoInvalida`  * __Significado__: a presente requisição de devolução não respeita o _schema_ ou não faz sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `PUT /pix/{e2eid}/devolucao/{id}`.  __Violações__ específicas para o endpoint `PUT /pix/{e2eid}/devolucao/{id}`:   - O campo `devolucao.valor` não respeita o _schema_.   - A presente requisição de devolução, em conjunto com as demais prévias devoluções,   se aplicável, excederia o valor do pix originário.   - A presente requisição de devolução apresenta um `{id}` já utilizado por outra requisição de   devolução para o `{e2eid}` em questão.   - A presente requisição de devolução viola a janela de tempo permitida para solicitações de devoluções   de um pix (hoje estabelecida como 90 dias desde a data de liquidação original do pix).  ## Tag Webhook Reúne erros dos endpoints que tratam do gerenciamento dos Webhooks da API Pix.  ### `WebhookOperacaoInvalida` * __Significado__: a presente requisição busca criar um webhook sem respeitar o _schema_ ou, ainda, apresenta semântica inválida. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `PUT /webhook/{chave}`.  __Violações__ para o endpoint `PUT /webhook/{chave}`:   - o parâmetro {chave} não corresponde a uma chave DICT válida.   - o parâmetro {chave} não corresponde a uma chave DICT pertencente a este usuário recebedor.   - Campo webhook.webhookUrl não respeita o _schema_.  ### `WebhookNaoEncontrado`  * __Significado__: o webhook denotado por {chave} não encontra-se estabelecido. * __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4). * __endpoints__: `GET /webhook/{chave}`,  `DELETE /webhook/{chave}`  ### `WebhookConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de webhooks ativados não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /webhook`.  __Violações__ específicas para o endpoint `GET /webhook`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ## Tag WebhookRec Reúne erros dos endpoints que tratam do gerenciamento dos Webhooks de recorrências da API Pix.  ### `WebhookRecOperacaoInvalida` * __Significado__: a presente requisição busca criar um webhook sem respeitar o _schema_ ou, ainda, apresenta semântica inválida. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `PUT /webhookrec`.  __Violações__ para o endpoint `PUT /webhookrec`:   - o campo `webhookUrl` não respeita o _schema_.  ### `WebhookRecConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de webhooks ativados não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /webhookrec`.  __Violações__ específicas para o endpoint `GET /webhookrec`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.  ## Tag WebhookCobR Reúne erros dos endpoints que tratam do gerenciamento dos Webhooks de cobranças recorrentes da API Pix.  ### `WebhookCobROperacaoInvalida` * __Significado__: a presente requisição busca criar um webhook sem respeitar o _schema_ ou, ainda, apresenta semântica inválida. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `PUT /webhookcobr`.  __Violações__ para o endpoint `PUT /webhookcobr`:   - o campo `webhookUrl` não respeita o _schema_.  ### `WebhookCobRConsultaInvalida`  * __Significado__: os parâmetros de consulta à lista de webhooks ativados não respeitam o schema ou não fazem sentido semanticamente. * __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1). * __endpoints__: `GET /webhookcobr`.  __Violações__ específicas para o endpoint `GET /webhookcobr`:   - algum dos parâmetros informados para a consulta não respeita o _schema_.   - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp   representado pelo parâmetro `inicio`.   - o parâmetro `paginacao.paginaAtual` é negativo.   - o parâmetro `paginacao.itensPorPagina` é negativo.
 *
 * The version of the OpenAPI document: 2.9.0
 * Contact: suporte.pix@bcb.gov.br
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.17.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace OpenAPI\Client;

use GuzzleHttp\Psr7\Utils;
use OpenAPI\Client\Model\ModelInterface;

/**
 * ObjectSerializer Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class ObjectSerializer
{
    /** @var string */
    private static $dateTimeFormat = \DateTime::ATOM;

    /**
     * Change the date format
     *
     * @param string $format   the new date format to use
     */
    public static function setDateTimeFormat($format)
    {
        self::$dateTimeFormat = $format;
    }

    /**
     * Serialize data
     *
     * @param mixed  $data   the data to serialize
     * @param string|null $type   the OpenAPIToolsType of the data
     * @param string|null $format the format of the OpenAPITools type of the data
     *
     * @return scalar|object|array|null serialized form of $data
     */
    public static function sanitizeForSerialization($data, $type = null, $format = null)
    {
        if (is_scalar($data) || null === $data) {
            return $data;
        }

        if ($data instanceof \DateTime) {
            return ($format === 'date') ? $data->format('Y-m-d') : $data->format(self::$dateTimeFormat);
        }

        if (is_array($data)) {
            foreach ($data as $property => $value) {
                $data[$property] = self::sanitizeForSerialization($value);
            }
            return $data;
        }

        if (is_object($data)) {
            $values = [];
            if ($data instanceof ModelInterface) {
                $formats = $data::openAPIFormats();
                foreach ($data::openAPITypes() as $property => $openAPIType) {
                    $getter = $data::getters()[$property];
                    $value = $data->$getter();
                    if ($value !== null && !in_array($openAPIType, ['\DateTime', '\SplFileObject', 'array', 'bool', 'boolean', 'byte', 'float', 'int', 'integer', 'mixed', 'number', 'object', 'string', 'void'], true)) {
                        $callable = [$openAPIType, 'getAllowableEnumValues'];
                        if (is_callable($callable)) {
                            /** array $callable */
                            $allowedEnumTypes = $callable();
                            if (!in_array($value, $allowedEnumTypes, true)) {
                                $imploded = implode("', '", $allowedEnumTypes);
                                throw new \InvalidArgumentException("Invalid value for enum '$openAPIType', must be one of: '$imploded'");
                            }
                        }
                    }
                    if (($data::isNullable($property) && $data->isNullableSetToNull($property)) || $value !== null) {
                        $values[$data::attributeMap()[$property]] = self::sanitizeForSerialization($value, $openAPIType, $formats[$property]);
                    }
                }
            } else {
                foreach($data as $property => $value) {
                    $values[$property] = self::sanitizeForSerialization($value);
                }
            }
            return (object)$values;
        } else {
            return (string)$data;
        }
    }

    /**
     * Sanitize filename by removing path.
     * e.g. ../../sun.gif becomes sun.gif
     *
     * @param string $filename filename to be sanitized
     *
     * @return string the sanitized filename
     */
    public static function sanitizeFilename($filename)
    {
        if (preg_match("/.*[\/\\\\](.*)$/", $filename, $match)) {
            return $match[1];
        } else {
            return $filename;
        }
    }

    /**
     * Shorter timestamp microseconds to 6 digits length.
     *
     * @param string $timestamp Original timestamp
     *
     * @return string the shorten timestamp
     */
    public static function sanitizeTimestamp($timestamp)
    {
        if (!is_string($timestamp)) return $timestamp;

        return preg_replace('/(:\d{2}.\d{6})\d*/', '$1', $timestamp);
    }

    /**
     * Take value and turn it into a string suitable for inclusion in
     * the path, by url-encoding.
     *
     * @param string $value a string which will be part of the path
     *
     * @return string the serialized object
     */
    public static function toPathValue($value)
    {
        return rawurlencode(self::toString($value));
    }

    /**
     * Checks if a value is empty, based on its OpenAPI type.
     *
     * @param mixed  $value
     * @param string $openApiType
     *
     * @return bool true if $value is empty
     */
    private static function isEmptyValue($value, string $openApiType): bool
    {
        # If empty() returns false, it is not empty regardless of its type.
        if (!empty($value)) {
            return false;
        }

        # Null is always empty, as we cannot send a real "null" value in a query parameter.
        if ($value === null) {
            return true;
        }

        switch ($openApiType) {
            # For numeric values, false and '' are considered empty.
            # This comparison is safe for floating point values, since the previous call to empty() will
            # filter out values that don't match 0.
            case 'int':
            case 'integer':
                return $value !== 0;

            case 'number':
            case 'float':
                return $value !== 0 && $value !== 0.0;

            # For boolean values, '' is considered empty
            case 'bool':
            case 'boolean':
                return !in_array($value, [false, 0], true);

            # For string values, '' is considered empty.
            case 'string':
                return $value === '';

            # For all the other types, any value at this point can be considered empty.
            default:
                return true;
        }
    }

    /**
     * Take query parameter properties and turn it into an array suitable for
     * native http_build_query or GuzzleHttp\Psr7\Query::build.
     *
     * @param mixed  $value       Parameter value
     * @param string $paramName   Parameter name
     * @param string $openApiType OpenAPIType eg. array or object
     * @param string $style       Parameter serialization style
     * @param bool   $explode     Parameter explode option
     * @param bool   $required    Whether query param is required or not
     *
     * @return array
     */
    public static function toQueryValue(
        $value,
        string $paramName,
        string $openApiType = 'string',
        string $style = 'form',
        bool $explode = true,
        bool $required = true
    ): array {

        # Check if we should omit this parameter from the query. This should only happen when:
        #  - Parameter is NOT required; AND
        #  - its value is set to a value that is equivalent to "empty", depending on its OpenAPI type. For
        #    example, 0 as "int" or "boolean" is NOT an empty value.
        if (self::isEmptyValue($value, $openApiType)) {
            if ($required) {
                return ["{$paramName}" => ''];
            } else {
                return [];
            }
        }

        # Handle DateTime objects in query
        if($openApiType === "\\DateTime" && $value instanceof \DateTime) {
            return ["{$paramName}" => $value->format(self::$dateTimeFormat)];
        }

        $query = [];
        $value = (in_array($openApiType, ['object', 'array'], true)) ? (array)$value : $value;

        // since \GuzzleHttp\Psr7\Query::build fails with nested arrays
        // need to flatten array first
        $flattenArray = function ($arr, $name, &$result = []) use (&$flattenArray, $style, $explode) {
            if (!is_array($arr)) return $arr;

            foreach ($arr as $k => $v) {
                $prop = ($style === 'deepObject') ? $prop = "{$name}[{$k}]" : $k;

                if (is_array($v)) {
                    $flattenArray($v, $prop, $result);
                } else {
                    if ($style !== 'deepObject' && !$explode) {
                        // push key itself
                        $result[] = $prop;
                    }
                    $result[$prop] = $v;
                }
            }
            return $result;
        };

        $value = $flattenArray($value, $paramName);

        // https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.1.0.md#style-values
        if ($openApiType === 'array' && $style === 'deepObject' && $explode) {
            return $value;
        }

        if ($openApiType === 'object' && ($style === 'deepObject' || $explode)) {
            return $value;
        }

        if ('boolean' === $openApiType && is_bool($value)) {
            $value = self::convertBoolToQueryStringFormat($value);
        }

        // handle style in serializeCollection
        $query[$paramName] = ($explode) ? $value : self::serializeCollection((array)$value, $style);

        return $query;
    }

    /**
     * Convert boolean value to format for query string.
     *
     * @param bool $value Boolean value
     *
     * @return int|string Boolean value in format
     */
    public static function convertBoolToQueryStringFormat(bool $value)
    {
        if (Configuration::BOOLEAN_FORMAT_STRING == Configuration::getDefaultConfiguration()->getBooleanFormatForQueryString()) {
            return $value ? 'true' : 'false';
        }

        return (int) $value;
    }

    /**
     * Take value and turn it into a string suitable for inclusion in
     * the header. If it's a string, pass through unchanged
     * If it's a datetime object, format it in ISO8601
     *
     * @param string $value a string which will be part of the header
     *
     * @return string the header string
     */
    public static function toHeaderValue($value)
    {
        $callable = [$value, 'toHeaderValue'];
        if (is_callable($callable)) {
            return $callable();
        }

        return self::toString($value);
    }

    /**
     * Take value and turn it into a string suitable for inclusion in
     * the parameter. If it's a string, pass through unchanged
     * If it's a datetime object, format it in ISO8601
     * If it's a boolean, convert it to "true" or "false".
     *
     * @param float|int|bool|\DateTime $value the value of the parameter
     *
     * @return string the header string
     */
    public static function toString($value)
    {
        if ($value instanceof \DateTime) { // datetime in ISO8601 format
            return $value->format(self::$dateTimeFormat);
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } else {
            return (string) $value;
        }
    }

    /**
     * Serialize an array to a string.
     *
     * @param array  $collection                 collection to serialize to a string
     * @param string $style                      the format use for serialization (csv,
     * ssv, tsv, pipes, multi)
     * @param bool   $allowCollectionFormatMulti allow collection format to be a multidimensional array
     *
     * @return string
     */
    public static function serializeCollection(array $collection, $style, $allowCollectionFormatMulti = false)
    {
        if ($allowCollectionFormatMulti && ('multi' === $style)) {
            // http_build_query() almost does the job for us. We just
            // need to fix the result of multidimensional arrays.
            return preg_replace('/%5B[0-9]+%5D=/', '=', http_build_query($collection, '', '&'));
        }
        switch ($style) {
            case 'pipeDelimited':
            case 'pipes':
                return implode('|', $collection);

            case 'tsv':
                return implode("\t", $collection);

            case 'spaceDelimited':
            case 'ssv':
                return implode(' ', $collection);

            case 'simple':
            case 'csv':
                // Deliberate fall through. CSV is default format.
            default:
                return implode(',', $collection);
        }
    }

    /**
     * Deserialize a JSON string into an object
     *
     * @param mixed    $data          object or primitive to be deserialized
     * @param string   $class         class name is passed as a string
     * @param string[]|null $httpHeaders   HTTP headers
     *
     * @return object|array|null a single or an array of $class instances
     */
    public static function deserialize($data, $class, $httpHeaders = null)
    {
        if (null === $data) {
            return null;
        }

        if (strcasecmp(substr($class, -2), '[]') === 0) {
            $data = is_string($data) ? json_decode($data) : $data;

            if (!is_array($data)) {
                throw new \InvalidArgumentException("Invalid array '$class'");
            }

            $subClass = substr($class, 0, -2);
            $values = [];
            foreach ($data as $key => $value) {
                $values[] = self::deserialize($value, $subClass, null);
            }
            return $values;
        }

        if (preg_match('/^(array<|map\[)/', $class)) { // for associative array e.g. array<string,int>
            $data = is_string($data) ? json_decode($data) : $data;
            settype($data, 'array');
            $inner = substr($class, 4, -1);
            $deserialized = [];
            if (strrpos($inner, ",") !== false) {
                $subClass_array = explode(',', $inner, 2);
                $subClass = $subClass_array[1];
                foreach ($data as $key => $value) {
                    $deserialized[$key] = self::deserialize($value, $subClass, null);
                }
            }
            return $deserialized;
        }

        if ($class === 'object') {
            settype($data, 'array');
            return $data;
        } elseif ($class === 'mixed') {
            settype($data, gettype($data));
            return $data;
        }

        if ($class === '\DateTime') {
            // Some APIs return an invalid, empty string as a
            // date-time property. DateTime::__construct() will return
            // the current time for empty input which is probably not
            // what is meant. The invalid empty string is probably to
            // be interpreted as a missing field/value. Let's handle
            // this graceful.
            if (!empty($data)) {
                try {
                    return new \DateTime($data);
                } catch (\Exception $exception) {
                    // Some APIs return a date-time with too high nanosecond
                    // precision for php's DateTime to handle.
                    // With provided regexp 6 digits of microseconds saved
                    return new \DateTime(self::sanitizeTimestamp($data));
                }
            } else {
                return null;
            }
        }

        if ($class === '\SplFileObject') {
            $data = Utils::streamFor($data);

            /** @var \Psr\Http\Message\StreamInterface $data */

            // determine file name
            if (
                is_array($httpHeaders)
                && array_key_exists('Content-Disposition', $httpHeaders)
                && preg_match('/inline; filename=[\'"]?([^\'"\s]+)[\'"]?$/i', $httpHeaders['Content-Disposition'], $match)
            ) {
                $filename = Configuration::getDefaultConfiguration()->getTempFolderPath() . DIRECTORY_SEPARATOR . self::sanitizeFilename($match[1]);
            } else {
                $filename = tempnam(Configuration::getDefaultConfiguration()->getTempFolderPath(), '');
            }

            $file = fopen($filename, 'w');
            while ($chunk = $data->read(200)) {
                fwrite($file, $chunk);
            }
            fclose($file);

            return new \SplFileObject($filename, 'r');
        }

        /** @psalm-suppress ParadoxicalCondition */
        if (in_array($class, ['\DateTime', '\SplFileObject', 'array', 'bool', 'boolean', 'byte', 'float', 'int', 'integer', 'mixed', 'number', 'object', 'string', 'void'], true)) {
            settype($data, $class);
            return $data;
        }


        if (method_exists($class, 'getAllowableEnumValues')) {
            if (!in_array($data, $class::getAllowableEnumValues(), true)) {
                $imploded = implode("', '", $class::getAllowableEnumValues());
                throw new \InvalidArgumentException("Invalid value for enum '$class', must be one of: '$imploded'");
            }
            return $data;
        } else {
            $data = is_string($data) ? json_decode($data) : $data;

            if (is_array($data)) {
                $data = (object)$data;
            }

            // If a discriminator is defined and points to a valid subclass, use it.
            $discriminator = $class::DISCRIMINATOR;
            if (!empty($discriminator) && isset($data->{$discriminator}) && is_string($data->{$discriminator})) {
                $subclass = '\OpenAPI\Client\Model\\' . $data->{$discriminator};
                if (is_subclass_of($subclass, $class)) {
                    $class = $subclass;
                }
            }

            /** @var ModelInterface $instance */
            $instance = new $class();
            foreach ($instance::openAPITypes() as $property => $type) {
                $propertySetter = $instance::setters()[$property];

                if (!isset($propertySetter)) {
                    continue;
                }

                if (!isset($data->{$instance::attributeMap()[$property]})) {
                    if ($instance::isNullable($property)) {
                        $instance->$propertySetter(null);
                    }

                    continue;
                }

                if (isset($data->{$instance::attributeMap()[$property]})) {
                    $propertyValue = $data->{$instance::attributeMap()[$property]};
                    $instance->$propertySetter(self::deserialize($propertyValue, $type, null));
                }
            }
            return $instance;
        }
    }

    /**
    * Build a query string from an array of key value pairs.
    *
    * This function can use the return value of `parse()` to build a query
    * string. This function does not modify the provided keys when an array is
    * encountered (like `http_build_query()` would).
    *
    * The function is copied from https://github.com/guzzle/psr7/blob/a243f80a1ca7fe8ceed4deee17f12c1930efe662/src/Query.php#L59-L112
    * with a modification which is described in https://github.com/guzzle/psr7/pull/603
    *
    * @param array     $params              Query string parameters.
    * @param int|false $encoding            Set to false to not encode, PHP_QUERY_RFC3986
    *                                       to encode using RFC3986, or PHP_QUERY_RFC1738
    *                                       to encode using RFC1738.
    */
    public static function buildQuery(array $params, $encoding = PHP_QUERY_RFC3986): string
    {
        if (!$params) {
            return '';
        }

        if ($encoding === false) {
            $encoder = function (string $str): string {
                return $str;
            };
        } elseif ($encoding === PHP_QUERY_RFC3986) {
            $encoder = 'rawurlencode';
        } elseif ($encoding === PHP_QUERY_RFC1738) {
            $encoder = 'urlencode';
        } else {
            throw new \InvalidArgumentException('Invalid type');
        }

        $castBool = Configuration::BOOLEAN_FORMAT_INT == Configuration::getDefaultConfiguration()->getBooleanFormatForQueryString()
            ? function ($v) { return (int) $v; }
            : function ($v) { return $v ? 'true' : 'false'; };

        $qs = '';
        foreach ($params as $k => $v) {
            $k = $encoder((string) $k);
            if (!is_array($v)) {
                $qs .= $k;
                $v = is_bool($v) ? $castBool($v) : $v;
                if ($v !== null) {
                    $qs .= '='.$encoder((string) $v);
                }
                $qs .= '&';
            } else {
                foreach ($v as $vv) {
                    $qs .= $k;
                    $vv = is_bool($vv) ? $castBool($vv) : $vv;
                    if ($vv !== null) {
                        $qs .= '='.$encoder((string) $vv);
                    }
                    $qs .= '&';
                }
            }
        }

        return $qs ? substr($qs, 0, -1) : '';
    }
}
