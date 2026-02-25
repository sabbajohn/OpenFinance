<?php
/**
 * PayloadLocationRecApi
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
 * PayloadLocationRecApi Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class PayloadLocationRecApi
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
        'locrecGet' => [
            'application/json',
        ],
        'locrecIdGet' => [
            'application/json',
        ],
        'locrecIdIdRecDelete' => [
            'application/json',
        ],
        'locrecPost' => [
            'application/json',
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
     * Operation locrecGet
     *
     * Consultar locations cadastradas.
     *
     * @param  \DateTime $inicio inicio (required)
     * @param  \DateTime $fim fim (required)
     * @param  bool|null $id_rec_presente id_rec_presente (optional)
     * @param  string|null $convenio convenio (optional)
     * @param  int|null $paginacao_pagina_atual paginacao_pagina_atual (optional, default to 0)
     * @param  int|null $paginacao_itens_por_pagina paginacao_itens_por_pagina (optional, default to 100)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecGet'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\PayloadLocationRecConsultadas|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema
     */
    public function locrecGet($inicio, $fim, $id_rec_presente = null, $convenio = null, $paginacao_pagina_atual = 0, $paginacao_itens_por_pagina = 100, string $contentType = self::contentTypes['locrecGet'][0])
    {
        list($response) = $this->locrecGetWithHttpInfo($inicio, $fim, $id_rec_presente, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina, $contentType);
        return $response;
    }

    /**
     * Operation locrecGetWithHttpInfo
     *
     * Consultar locations cadastradas.
     *
     * @param  \DateTime $inicio (required)
     * @param  \DateTime $fim (required)
     * @param  bool|null $id_rec_presente (optional)
     * @param  string|null $convenio (optional)
     * @param  int|null $paginacao_pagina_atual (optional, default to 0)
     * @param  int|null $paginacao_itens_por_pagina (optional, default to 100)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecGet'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\PayloadLocationRecConsultadas|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema, HTTP status code, HTTP response headers (array of strings)
     */
    public function locrecGetWithHttpInfo($inicio, $fim, $id_rec_presente = null, $convenio = null, $paginacao_pagina_atual = 0, $paginacao_itens_por_pagina = 100, string $contentType = self::contentTypes['locrecGet'][0])
    {
        $request = $this->locrecGetRequest($inicio, $fim, $id_rec_presente, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina, $contentType);

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
                        '\OpenAPI\Client\Model\PayloadLocationRecConsultadas',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
                        $request,
                        $response,
                    );
                case 503:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
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
                '\OpenAPI\Client\Model\PayloadLocationRecConsultadas',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\PayloadLocationRecConsultadas',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 503:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation locrecGetAsync
     *
     * Consultar locations cadastradas.
     *
     * @param  \DateTime $inicio (required)
     * @param  \DateTime $fim (required)
     * @param  bool|null $id_rec_presente (optional)
     * @param  string|null $convenio (optional)
     * @param  int|null $paginacao_pagina_atual (optional, default to 0)
     * @param  int|null $paginacao_itens_por_pagina (optional, default to 100)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecGet'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecGetAsync($inicio, $fim, $id_rec_presente = null, $convenio = null, $paginacao_pagina_atual = 0, $paginacao_itens_por_pagina = 100, string $contentType = self::contentTypes['locrecGet'][0])
    {
        return $this->locrecGetAsyncWithHttpInfo($inicio, $fim, $id_rec_presente, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation locrecGetAsyncWithHttpInfo
     *
     * Consultar locations cadastradas.
     *
     * @param  \DateTime $inicio (required)
     * @param  \DateTime $fim (required)
     * @param  bool|null $id_rec_presente (optional)
     * @param  string|null $convenio (optional)
     * @param  int|null $paginacao_pagina_atual (optional, default to 0)
     * @param  int|null $paginacao_itens_por_pagina (optional, default to 100)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecGet'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecGetAsyncWithHttpInfo($inicio, $fim, $id_rec_presente = null, $convenio = null, $paginacao_pagina_atual = 0, $paginacao_itens_por_pagina = 100, string $contentType = self::contentTypes['locrecGet'][0])
    {
        $returnType = '\OpenAPI\Client\Model\PayloadLocationRecConsultadas';
        $request = $this->locrecGetRequest($inicio, $fim, $id_rec_presente, $convenio, $paginacao_pagina_atual, $paginacao_itens_por_pagina, $contentType);

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
     * Create request for operation 'locrecGet'
     *
     * @param  \DateTime $inicio (required)
     * @param  \DateTime $fim (required)
     * @param  bool|null $id_rec_presente (optional)
     * @param  string|null $convenio (optional)
     * @param  int|null $paginacao_pagina_atual (optional, default to 0)
     * @param  int|null $paginacao_itens_por_pagina (optional, default to 100)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecGet'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function locrecGetRequest($inicio, $fim, $id_rec_presente = null, $convenio = null, $paginacao_pagina_atual = 0, $paginacao_itens_por_pagina = 100, string $contentType = self::contentTypes['locrecGet'][0])
    {

        // verify the required parameter 'inicio' is set
        if ($inicio === null || (is_array($inicio) && count($inicio) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $inicio when calling locrecGet'
            );
        }

        // verify the required parameter 'fim' is set
        if ($fim === null || (is_array($fim) && count($fim) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $fim when calling locrecGet'
            );
        }


        if ($convenio !== null && strlen($convenio) > 60) {
            throw new \InvalidArgumentException('invalid length for "$convenio" when calling PayloadLocationRecApi.locrecGet, must be smaller than or equal to 60.');
        }
        
        if ($paginacao_pagina_atual !== null && $paginacao_pagina_atual < 0) {
            throw new \InvalidArgumentException('invalid value for "$paginacao_pagina_atual" when calling PayloadLocationRecApi.locrecGet, must be bigger than or equal to 0.');
        }
        
        if ($paginacao_itens_por_pagina !== null && $paginacao_itens_por_pagina > 1000) {
            throw new \InvalidArgumentException('invalid value for "$paginacao_itens_por_pagina" when calling PayloadLocationRecApi.locrecGet, must be smaller than or equal to 1000.');
        }
        if ($paginacao_itens_por_pagina !== null && $paginacao_itens_por_pagina < 1) {
            throw new \InvalidArgumentException('invalid value for "$paginacao_itens_por_pagina" when calling PayloadLocationRecApi.locrecGet, must be bigger than or equal to 1.');
        }
        

        $resourcePath = '/locrec';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $inicio,
            'inicio', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $fim,
            'fim', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $id_rec_presente,
            'idRecPresente', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $convenio,
            'convenio', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $paginacao_pagina_atual,
            'paginacao.paginaAtual', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $paginacao_itens_por_pagina,
            'paginacao.itensPorPagina', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);




        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/problem+json', ],
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
     * Operation locrecIdGet
     *
     * Recuperar location do payload.
     *
     * @param  string $id id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdGet'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\PayloadLocationRecCompleta|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema
     */
    public function locrecIdGet($id, string $contentType = self::contentTypes['locrecIdGet'][0])
    {
        list($response) = $this->locrecIdGetWithHttpInfo($id, $contentType);
        return $response;
    }

    /**
     * Operation locrecIdGetWithHttpInfo
     *
     * Recuperar location do payload.
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdGet'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\PayloadLocationRecCompleta|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema, HTTP status code, HTTP response headers (array of strings)
     */
    public function locrecIdGetWithHttpInfo($id, string $contentType = self::contentTypes['locrecIdGet'][0])
    {
        $request = $this->locrecIdGetRequest($id, $contentType);

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
                        '\OpenAPI\Client\Model\PayloadLocationRecCompleta',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
                        $request,
                        $response,
                    );
                case 503:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
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
                '\OpenAPI\Client\Model\PayloadLocationRecCompleta',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\PayloadLocationRecCompleta',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 503:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation locrecIdGetAsync
     *
     * Recuperar location do payload.
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdGet'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecIdGetAsync($id, string $contentType = self::contentTypes['locrecIdGet'][0])
    {
        return $this->locrecIdGetAsyncWithHttpInfo($id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation locrecIdGetAsyncWithHttpInfo
     *
     * Recuperar location do payload.
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdGet'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecIdGetAsyncWithHttpInfo($id, string $contentType = self::contentTypes['locrecIdGet'][0])
    {
        $returnType = '\OpenAPI\Client\Model\PayloadLocationRecCompleta';
        $request = $this->locrecIdGetRequest($id, $contentType);

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
     * Create request for operation 'locrecIdGet'
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdGet'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function locrecIdGetRequest($id, string $contentType = self::contentTypes['locrecIdGet'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling locrecIdGet'
            );
        }


        $resourcePath = '/locrec/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/problem+json', ],
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
     * Operation locrecIdIdRecDelete
     *
     * Desvincular uma recorrência de uma location.
     *
     * @param  string $id id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdIdRecDelete'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\PayloadLocationRecCompleta|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema
     */
    public function locrecIdIdRecDelete($id, string $contentType = self::contentTypes['locrecIdIdRecDelete'][0])
    {
        list($response) = $this->locrecIdIdRecDeleteWithHttpInfo($id, $contentType);
        return $response;
    }

    /**
     * Operation locrecIdIdRecDeleteWithHttpInfo
     *
     * Desvincular uma recorrência de uma location.
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdIdRecDelete'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\PayloadLocationRecCompleta|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema, HTTP status code, HTTP response headers (array of strings)
     */
    public function locrecIdIdRecDeleteWithHttpInfo($id, string $contentType = self::contentTypes['locrecIdIdRecDelete'][0])
    {
        $request = $this->locrecIdIdRecDeleteRequest($id, $contentType);

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
                        '\OpenAPI\Client\Model\PayloadLocationRecCompleta',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
                        $request,
                        $response,
                    );
                case 404:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
                        $request,
                        $response,
                    );
                case 503:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
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
                '\OpenAPI\Client\Model\PayloadLocationRecCompleta',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\PayloadLocationRecCompleta',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 404:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 503:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation locrecIdIdRecDeleteAsync
     *
     * Desvincular uma recorrência de uma location.
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdIdRecDelete'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecIdIdRecDeleteAsync($id, string $contentType = self::contentTypes['locrecIdIdRecDelete'][0])
    {
        return $this->locrecIdIdRecDeleteAsyncWithHttpInfo($id, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation locrecIdIdRecDeleteAsyncWithHttpInfo
     *
     * Desvincular uma recorrência de uma location.
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdIdRecDelete'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecIdIdRecDeleteAsyncWithHttpInfo($id, string $contentType = self::contentTypes['locrecIdIdRecDelete'][0])
    {
        $returnType = '\OpenAPI\Client\Model\PayloadLocationRecCompleta';
        $request = $this->locrecIdIdRecDeleteRequest($id, $contentType);

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
     * Create request for operation 'locrecIdIdRecDelete'
     *
     * @param  string $id (required)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecIdIdRecDelete'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function locrecIdIdRecDeleteRequest($id, string $contentType = self::contentTypes['locrecIdIdRecDelete'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling locrecIdIdRecDelete'
            );
        }


        $resourcePath = '/locrec/{id}/idRec';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/problem+json', ],
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
            'DELETE',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation locrecPost
     *
     * Criar location do payload.
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecPost'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return \OpenAPI\Client\Model\PayloadLocationRecGerada|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema
     */
    public function locrecPost(string $contentType = self::contentTypes['locrecPost'][0])
    {
        list($response) = $this->locrecPostWithHttpInfo($contentType);
        return $response;
    }

    /**
     * Operation locrecPostWithHttpInfo
     *
     * Criar location do payload.
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecPost'] to see the possible values for this operation
     *
     * @throws \OpenAPI\Client\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of \OpenAPI\Client\Model\PayloadLocationRecGerada|\OpenAPI\Client\Model\Problema|\OpenAPI\Client\Model\Problema, HTTP status code, HTTP response headers (array of strings)
     */
    public function locrecPostWithHttpInfo(string $contentType = self::contentTypes['locrecPost'][0])
    {
        $request = $this->locrecPostRequest($contentType);

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
                        '\OpenAPI\Client\Model\PayloadLocationRecGerada',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
                        $request,
                        $response,
                    );
                case 503:
                    return $this->handleResponseWithDataType(
                        '\OpenAPI\Client\Model\Problema',
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
                '\OpenAPI\Client\Model\PayloadLocationRecGerada',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 201:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\PayloadLocationRecGerada',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 503:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\OpenAPI\Client\Model\Problema',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation locrecPostAsync
     *
     * Criar location do payload.
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecPost'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecPostAsync(string $contentType = self::contentTypes['locrecPost'][0])
    {
        return $this->locrecPostAsyncWithHttpInfo($contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation locrecPostAsyncWithHttpInfo
     *
     * Criar location do payload.
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecPost'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function locrecPostAsyncWithHttpInfo(string $contentType = self::contentTypes['locrecPost'][0])
    {
        $returnType = '\OpenAPI\Client\Model\PayloadLocationRecGerada';
        $request = $this->locrecPostRequest($contentType);

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
     * Create request for operation 'locrecPost'
     *
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['locrecPost'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function locrecPostRequest(string $contentType = self::contentTypes['locrecPost'][0])
    {


        $resourcePath = '/locrec';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;





        $headers = $this->headerSelector->selectHeaders(
            ['application/json', 'application/problem+json', ],
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
