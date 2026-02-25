<?php
/**
 * Violacao
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

namespace OpenAPI\Client\Model;

use \ArrayAccess;
use \OpenAPI\Client\ObjectSerializer;

/**
 * Violacao Class Doc Comment
 *
 * @category Class
 * @package  OpenAPI\Client
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class Violacao implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'Violacao';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'razao' => 'string',
        'propriedade' => 'string',
        'valor' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'razao' => null,
        'propriedade' => null,
        'valor' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'razao' => false,
        'propriedade' => false,
        'valor' => false
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
        'razao' => 'razao',
        'propriedade' => 'propriedade',
        'valor' => 'valor'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'razao' => 'setRazao',
        'propriedade' => 'setPropriedade',
        'valor' => 'setValor'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'razao' => 'getRazao',
        'propriedade' => 'getPropriedade',
        'valor' => 'getValor'
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
        $this->setIfExists('razao', $data ?? [], null);
        $this->setIfExists('propriedade', $data ?? [], null);
        $this->setIfExists('valor', $data ?? [], null);
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
     * Gets razao
     *
     * @return string|null
     */
    public function getRazao()
    {
        return $this->container['razao'];
    }

    /**
     * Sets razao
     *
     * @param string|null $razao Descrição do erro
     *
     * @return self
     */
    public function setRazao($razao)
    {
        if (is_null($razao)) {
            throw new \InvalidArgumentException('non-nullable razao cannot be null');
        }
        $this->container['razao'] = $razao;

        return $this;
    }

    /**
     * Gets propriedade
     *
     * @return string|null
     */
    public function getPropriedade()
    {
        return $this->container['propriedade'];
    }

    /**
     * Sets propriedade
     *
     * @param string|null $propriedade Nome da propriedade
     *
     * @return self
     */
    public function setPropriedade($propriedade)
    {
        if (is_null($propriedade)) {
            throw new \InvalidArgumentException('non-nullable propriedade cannot be null');
        }
        $this->container['propriedade'] = $propriedade;

        return $this;
    }

    /**
     * Gets valor
     *
     * @return string|null
     */
    public function getValor()
    {
        return $this->container['valor'];
    }

    /**
     * Sets valor
     *
     * @param string|null $valor Valor da propriedade
     *
     * @return self
     */
    public function setValor($valor)
    {
        if (is_null($valor)) {
            throw new \InvalidArgumentException('non-nullable valor cannot be null');
        }
        $this->container['valor'] = $valor;

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


