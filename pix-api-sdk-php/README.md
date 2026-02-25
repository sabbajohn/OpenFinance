# OpenAPIClient-php

A API Pix padroniza serviços oferecidos pelo PSP recebedor no contexto do arranjo Pix, direcionando:
- o gerenciamentos de cobranças, com e sem recorrências, em lotes ou não;
- o acompanhamento dos Pix e suas devoluções;
- as consultas.

Os serviços expostos pelo PSP recebedor permitem ao usuário recebedor estabelecer integração
de sua automação com os serviços Pix do PSP.

# Evolução da API Pix

A API Pix busca respeitar __[SemVer](https://semver.org/lang/pt-BR/)__. Nesse sentido,
mudanças compatíveis não devem gerar nova versão _major_.

A versão da API é composta por 4 elementos: _major_, _minor_, _patch_ e _release candidate_.
A versão `v[x]`que consta no path da URL é o elemento _major_ da versão da API.
A evolução da versão se dá seguinte forma:

  - Major: alterações incompatíveis, com quebra de contrato (v1.0.0 → v2.0.0) 
  - Minor: alterações compatíveis, sem quebra de contrato (v1.1.0 → v1.2.0)
  - Patch: bugfixes, esclarecimentos às especificações, sem alterações funcionais (v1.1.1 → v1.1.2)
  - Release candidate: versões de pré-lançamento de qualquer patch futuro, minor ou major (v1.0.0-rc.1 → v1.0.0-rc.22)

Alterações sem quebra de contrato e esclarecimentos às especificações podem ocorrer a qualquer momento.
Clientes devem estar preparados para lidar com essas mudanças sem quebrar.

As seguintes mudanças são esperadas e consideradas retrocompatíveis:

- Adição de novos recursos na API Pix;
- Adição de novos parâmetros opcionais;
- Adição de novos campos em respostas da API Pix;
- Alteração da ordem de campos;
- Adição de novos elementos em enumerações.


# Tratamento de erros

A API Pix retorna códigos de status HTTP para indicar sucesso ou falhas das
requisições, são eles:
- Códigos `2xx` indicam sucesso; 
- Códigos `4xx` indicam falhas causadas pelas
informações enviadas pelo cliente ou pelo estado atual das entidades e;
- Códigos `5xx` indicam problemas no serviço no lado da API Pix.

As respostas de erro incluem no corpo detalhes do erro seguindo o
_schema_ da [RFC 7807](https://tools.ietf.org/html/rfc7807).

O campo `type` identifica o tipo de erro e na API Pix segue o padrão:

`https://pix.bcb.gov.br/api/v2/error/<TipoErro>`

O padrão acima listado, referente ao campo `type`, não consiste, necessariamente, em uma
URL que apresentará uma página web válida, ou um endpoint válido, embora possa, futuramente,
ser exatamente o caso. O objetivo primário é apenas e tão somente identificar o tipo de erro.

Convém reforçar que a API Pix contempla uma lista de produtos e respectivas funcionalidades ofertadas pelo PSP recebedor. 
Cabe à relação contratual com cada usuário recebedor a concessão da totalidade ou de um subconjunto de acessos
relacionados aos produtos ofertados. Por exemplo, o usuário recebedor, ao acessar uma funcionalidade não contemplada 
no seu escopo contratual, receberá o erro geral `AcessoNegado` descrito na próxima seção.

Abaixo estão listados os tipos de erro e possíveis violações da API Pix.

## Gerais

Esta seção reúne erros que poderiam ser retornados por quaisquer endpoints listados na API Pix.

### `RequisicaoInvalida`

  * __Significado__: Requisição inválida.
  * __HTTP Status Code__: [400 Bad Request](https://tools.ietf.org/html/rfc7231#section-6.5.1).

### `AcessoNegado`

  * __Significado__: Requisição de participante autenticado que viola alguma regra de autorização.
  * __HTTP Status Code__: [403 Forbidden](https://tools.ietf.org/html/rfc7231#section-6.5.3).

### `NaoEncontrado`

  * __Significado__: Entidade não encontrada.
  * __HTTP Status Code__: [404 Not Found](https://tools.ietf.org/html/rfc7231#section-6.5.4).

### `PermanentementeRemovido`

  * __Significado__: Indica que a entidade existia, mas foi permanentemente removida.
  * __HTTP Status Code__: [410 Gone](https://tools.ietf.org/html/rfc7231#section-6.5.9).

### `ErroInternoDoServidor`

  * __Significado__: Condição inesperada ao processar requisição.
  * __HTTP Status Code__: [500 Internal Server Error](https://tools.ietf.org/html/rfc7231#section-6.6.1).

### `ServicoIndisponivel`

  * __Significado__: Serviço não está disponível no momento. Serviço solicitado pode estar em manutenção ou fora da janela de funcionamento.
  * __HTTP Status Code__: [503 Service Unavailable](https://tools.ietf.org/html/rfc7231#section-6.6.4).

### `IndisponibilidadePorTempoEsgotado`

  * __Significado__: Indica que o serviço demorou além do esperado para retornar.
  * __HTTP Status Code__: [504 Gateway Timeout](https://tools.ietf.org/html/rfc7231#section-6.6.5).

## Tag CobPayload 

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `CobPayload`.
Estes erros indicam problemas na tentativa de recuperação, via _location_, do Payload JSON que representa a cobrança.

### `CobPayloadNaoEncontrado`

* __Significado__: a cobrança em questão não foi encontrada para a location requisitada.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4) ou [410](https://tools.ietf.org/html/rfc7231#section-6.5.9).
* __endpoints__: `GET /{pixUrlAccessToken}`, `GET /cobv/{pixUrlAccessToken}`.

Se a presente location exibia uma cobrança, mas não a exibirá mais de maneira permanentemente,
pode-se aplicar o HTTP status code [410](https://tools.ietf.org/html/rfc7231#section-6.5.9). Se a presente location não
está exibindo nenhuma cobrança, pode-se utilizar o HTTP status code [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).

Uma cobrança pode estar \"expirada\" (`calendario.expiracao`), \"vencida\", \"Concluida\",
entre outros estados em que não poderia ser efetivamente paga. Nesses casos, é uma liberalidade
do PSP recebedor retornar o presente código de erro ou optar por servir o payload de qualquer maneira,
objetivando fornecer uma informação adicional ao usuário pagador final a respeito da cobrança.

### `CobPayloadOperacaoInvalida`

* __Significado__: a cobrança existe, mas a requisição é inválida.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /cobv/{pixUrlAccessToken}`.

__Violações__:
  - `codMun` não respeita o _schema_.
  - `codMun` não é um código válido segundo a __[tabela de municípios do IBGE](https://www.ibge.gov.br/explica/codigos-dos-municipios.php)__.
  - `DPP` não respeita o _schema_.
  - `DPP` anterior ao momento presente.
  - `DPP` superior à validade da cobrança em função dos parâmetros `calendario.dataDeVencimento`
  e `calendario.validadeAposVencimento`. Exemplo: `dataDeVencimento` => 2020-12-25,
  `validadeAposVencimento` => 10, `DPP` => 2021-01-05. Neste exemplo, o parâmetro `DPP` é
  inválido considerando o contexto apresentado porque é uma data em que a cobrança
  não poderá ser paga. A cobrança, neste exemplo, não será considerada válida
  a partir da data 2021-01-05.

## Tag RecPayload 

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `RecPayload`.
Estes erros indicam problemas na tentativa de recuperação, via _location_, do Payload JSON que representa a recorrência.

### `RecPayloadNaoEncontrado`

* __Significado__: a recorrência em questão não foi encontrada para a location requisitada.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4) ou [410](https://tools.ietf.org/html/rfc7231#section-6.5.9).
* __endpoint__: `GET /rec/{recUrlAccessToken}`.

Se a presente location exibia uma recorrência, mas não a exibirá mais de maneira permanentemente,
pode-se aplicar o HTTP status code [410](https://tools.ietf.org/html/rfc7231#section-6.5.9). Se a presente location não
está exibindo nenhuma recorrência, pode-se utilizar o HTTP status code [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).

Uma recorrência pode estar expirada, cancelada ou rejeitada, nesses casos, é uma liberalidade
do PSP recebedor retornar o presente código de erro ou optar por servir o payload de qualquer maneira,
objetivando fornecer uma informação adicional ao usuário pagador final a respeito da recorrência.

### `RecPayloadOperacaoInvalida`

* __Significado__: a recorrência em questão encontra-se em expirada, rejeitada ou cancelada para a location requisitada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoint__: `GET /rec/{recUrlAccessToken}`.

__Violações__ para o endpoint `GET /rec/{recUrlAccessToken}`:
- O campo `recUrlAccessToken` referencia uma recorrência expirada, rejeitada ou cancelada.

## Tag Rec

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `Rec`.
Esses erros indicam problemas no gerenciamento de uma recorrência.

### `RecNaoEncontrada`

* __Significado__: Recorrência não encontrada para o idRec informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET|PATCH] /rec/{idRec}`. 

### `RecOperacaoInvalida`

* __Significado__: a requisição que busca alterar ou criar uma recorrência não respeita o _schema_ ou está semanticamente errada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `POST /rec` e `PATCH /rec/{idRec}`.

__Violações__ para o endpoint `POST /rec`:
  - O objeto `rec.vinculo` não respeita o _schema_.
  - O campo `rec.calendario.dataInicial` é anterior à data de criação da recorrência.
  - O campo `rec.calendario.dataFinal` é anterior ao campo `rec.calendario.dataInicial`.
  - O campo `rec.calendario.periodicidade` não respeita o _schema_.
  - O objeto `rec.valor` não respeita o _schema_.
  - O campo `rec.valor.valorRec` não respeita o _schema_.
  - O campo `rec.valor.valorMinimoRecebedor` não respeita o _schema_.
  - Ambos os campos `rec.valor.valorRec` e `rec.valor.valorMinimoRecebedor` estão preenchidos.
  - O objeto `rec.recebedor` não respeita o _schema_.
  - O campo `rec.politicaRetentativa` não respeita o _schema_.
  - O location referenciado por `rec.loc` inexiste.
  - O location referenciado por `rec.loc` já está sendo utilizado por outra recorrência.
  - O valor do campo `rec.recebedor.convenio` não é aceito pelo PSP Recebedor.

__Violações__ para o endpoint `PATCH /rec/{idRec}`:

  - O campo `rec.calendario.dataInicial` é anterior à data de criação da recorrência.
  - O location referenciado por `rec.loc` inexiste.
  - O location referenciado por `rec.loc` já está sendo utilizado por outra recorrência.
  - O campo `rec.status` não respeita o _schema_.
  - A recorrência encontra-se expirada, cancelada ou rejeitada.
  - O campo `rec.loc` somente pode ser alterado quando a recorrência apresentar-se com o status CRIADA.
  - O campo `rec.calendario.dataInicial` somente pode ser alterado quando a recorrência apresentar-se com o status CRIADA.
  - O campo `rec.dadosJornada.txid` não pode ser alterado quando a recorrência apresentar-se com o status REJEITADA ou CANCELADA.

### `RecConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de recorrências que não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /rec` e `GET /rec/{idRec}`.

__Violações__ específicas para o endpoint `GET /rec`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

__Violações__ específicas para o endpoint `GET /rec/{idRec}`:

  - o parâmetro `txid` não corresponde a uma cobrança compatível com o campo `ativacao.tipoJornada`. (_Exemplo: `txid` correspondente a uma CobV e `ativação.tipoJornada` igual a JORNADA_3._)
  - o parâmetro `txid` corresponde a uma cobrança imediata diferente da informada no campo `ativação.dadosJornada.txid`. Esta violação não ocorre caso o parâmetro txid corresponda a uma cobrança com vencimento.

## Tag SolicRec

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `SolicRec`.
Esses erros indicam problemas no gerenciamento de uma solicitação de confirmação de recorrência.

### `SolicRecNaoEncontrada`

* __Significado__: Solicitação de recorrência não encontrada para o idSolicRec informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET] /solicrec/{idSolicRec}`.

### `SolicRecOperacaoInvalida`

* __Significado__: a requisição que busca criar ou alterar uma solicitação de confirmação de recorrência não respeita o _schema_ ou está semanticamente errada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `[POST] /solicrec` e `PATCH /solicrec/{idSolicRec}`.

__Violações__ para o endpoint `POST /solicrec`:
  - O objeto `solicrec.calendario` não respeita o _schema_.
  - O campo `solicrec.calendario.dataExpiracaoSolicitacao` é anterior à data de criação da solicitação da recorrência.
  - O objeto `solicrec.destinatario` não respeita o _schema_.
  - Existe uma solicitação ativa referente ao mesmo `solicrec.idRec`.

__Violações__ para o endpoint `PATCH /solicrec/{idSolicRec}`:

  - Não é possível cancelar uma solicitação de recorrência com o status diferente de CRIADA, ENVIADA ou RECEBIDA.

## Tag CobR

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `CobR`.
Esses erros indicam problemas no gerenciamento de uma cobrança recorrente.

### `CobRNaoEncontrado`

* __Significado__: Cobrança não encontrada para o txid informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET|PATCH] /cobr/{txid}` e  `[POST] /cobr/{txid}/retentativa/{data}`.

### `CobROperacaoInvalida`

* __Significado__: a requisição que busca alterar ou criar uma cobrança recorrente não respeita o _schema_ ou está semanticamente errada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `[POST|PUT|PATCH] /cobr/{txid}` e  `[POST] /cobr/{txid}/retentativa/{data}`.

__Violações__ para o endpoint `POST|PUT /cobr/{txid}`:
  - O campo `cobr.infoAdicional` não respeita o _schema_.
  - O campo `cobr.status` não respeita o _schema_.
  - O objeto `cobr.calendario` não respeita o _schema_.
  - O campo `cobr.calendario.dataDeVencimento` é anterior à data de criação da cobrança.
  - O campo `cobr.valor` não respeita o _schema_.
  - O objeto `cobr.recebedor` não respeita o _schema_.
  - Os campos `cobr.recebedor.conta` e `cobr.recebedor.agencia` correspondem a uma conta que não pertence a este usuário recebedor.
  - O objeto `cobr.devedor` não respeita o _schema_.
  - O campo `cobr.txid` encontra-se em uso.
  - Existe uma CobR com status diferente de REJEITADA e CANCELADA referente ao mesmo `cobr.idRec` com `calendario.dataDeVencimento` no mesmo ciclo.

__Violações__ para o endpoint `PATCH /cobr/{txid}`:

  - Não é possível cancelar uma cobrança em uma data igual ou maior que a data prevista da primeira tentativa de liquidação.

__Violações__ para o endpoint `POST /cobr/{txid}/retentativa/{data}`:

  - Existe uma tentativa com status `SOLICITADA` ou `AGENDADA`.
  - Existe uma tentativa em andamento.
  - Existe uma tentativa ativa.
  - Existe uma tentativa não finalizada.
  - Existe uma tentativa vigente para a `data` informada.
  - O parâmetro `data` não corresponde a uma data futura.
  - A política configurada na recorrência não permite retentativa de cobrança.

### `CobRConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de cobranças que não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /cobr` e `GET /cobr/{txid}`.

__Violações__ específicas para o endpoint `GET /cobr`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

## Tag Cob

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `Cob`.
Esses erros indicam problemas no gerenciamento de uma cobrança para pagamento imediato.

### `CobNaoEncontrado`

* __Significado__: Cobrança não encontrada para o txid informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET|PATCH] /cob/{txid}`.

### `CobOperacaoInvalida`

* __Significado__: a requisição que busca alterar ou criar uma cobrança para pagamento imediato
não respeita o _schema_ ou está semanticamente errada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `[POST|PUT|PATCH] /cob/{txid}`.

__Violações__ para os endpoints `PUT|PATCH /cob/{txid}`:
  - O campo `cob.calendario.expiracao` é igual ou menor que `zero`.
  - O campo `cob.valor.original` não respeita o _schema_.
  - O campo `cob.valor.original` é `zero`.
  - O objeto `cob.devedor` não respeita o _schema_.
  - O campo `cob.chave` não respeita o _schema_.
  - O campo `cob.chave` corresponde a uma conta que não pertence a este usuário recebedor.
  - O campo `solicitacaoPagador` não respeita o _schema_.
  - O objeto `infoAdicionais` não respeita o _schema_.
  - O `location` referenciado por `loc.id` inexiste.
  - O `location` referenciado por `loc.id` já está sendo utilizado por outra cobrança.
  - O `location` referenciado por `cob.loc.id` apresenta tipo \"cobv\" (deveria ser \"cob\").

__Violações__ específicas para o endpoint `PUT /cob/{txid}`:
  - A cobrança já existe, não está no status ATIVA, e a presente requisição busca alterá-la.

__Violações__ específicas para o endpoint `PATCH /cob/{txid}`:
  - A cobrança não está ATIVA, e a presente requisição busca alterá-la.
  - A cobrança está ATIVA, e a presente requisição propõe alterar
  seu status para _REMOVIDA_PELO_USUARIO_RECEBEDOR_ juntamente com outras alterações
  (não faz sentido remover uma cobrança ao mesmo tempo em que se realizam
  alterações que não serão aproveitadas).
  - o campo `cob.status` não respeita o _schema_.

### `CobConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de cobranças para pagamento imediato
não respeitam o _schema_ ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /cob` e `GET /cob/{txid}`.

__Violações__ específicas para o endpoint `GET /cob`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

__Violações__ específicas para o endpoint `GET /cob/{txid}`:
  - o parâmetro `revisao` corresponde a uma revisão inexistente para a cobrança
  apontada pelo parâmetro `txid`.

## Tag CobV

Esta seção reúne erros retornados pelos endpoints organizados sob a tag `CobV`.
Esses erros indicam problemas no gerenciamento de uma cobrança com vencimento.

### `CobVNaoEncontrada`

* __Significado__: Cobrança com vencimento não encontrada para o txid informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET|PATCH] /cobv/{txid}`.

### `CobVOperacaoInvalida`

* __Significado__: a requisição que busca alterar ou criar uma cobrança com vencimento
não respeita o _schema_ ou está semanticamente errada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `[PUT|PATCH] /cobv/{txid}`.

__Violações__ para os endpoints `PUT|PATCH /cobv/{txid}`:
  - Este `txid` está associado a um lote e no referido lote, o status desta cobrança está atribuído como
  \"EM_PROCESSAMENTO\" ou \"NEGADA\".
  - O campo `cobv.calendario.dataDeVencimento` é anterior à data de criação da cobrança.
  - O campo `cobv.calendario.validadeAposVencimento` é menor do que zero.
  - O objeto `cobv.devedor` não respeita o _schema_.
  - O campo `cobv.chave` não respeita o _schema_.
  - O campo `cobv.chave` corresponde a uma conta que não pertence a este usuário recebedor.
  - O campo `solicitacaoPagador` não respeita o _schema_.
  - O objeto `infoAdicionais` não respeita o _schema_.
  - O location referenciado por `cobv.loc.id` inexiste.
  - O location referenciado por `cobv.loc.id` já está sendo utilizado por outra cobrança.
  - O location referenciado por `cobv.loc.id` apresenta tipo \"cob\" (deveria ser \"cobv\").
  - O campo `cobv.valor.original` não respeita o _schema_.
  - O campo `cobv.valor.original` apresenta o valor `zero`.
  - O objeto `cobv.valor.multa` não respeita o _schema_.
  - O objeto `cobv.valor.juros` não respeita o _schema_.
  - O objeto `cobv.valor.abatimento` não respeita o _schema_.
  - O objeto `cobv.valor.desconto` não respeita o _schema_.
  - O objeto `cobv.valor.abatimento` representa um valor maior ou igual ao valor da
  cobrança original ou maior ou igual a 100%.
  - O objeto `cobv.valor.desconto` apresenta algum elemento de desconto que representa um valor maior ou
  igual ao valor da cobrança original ou maior ou igual a 100%.
  - O objeto `cobv.valor.desconto` apresenta algum elemento cuja data seja posterior à data de vencimento
  representada por `calendario.dataDeVencimento`.
  - O objeto `cobv.valor.desconto` apresenta modalidade no valor `1` ou `2`,
  porém `cobv.valor.desconto.valorPerc` encontra-se preenchido
  - O objeto `cobv.valor.desconto` apresenta modalidade no valor `1` ou `2`, porém
  o array `cobv.valor.desconto.descontoDataFixa` está vazio ou nulo.
  - O objeto `cobv.valor.desconto` apresenta modalidade nos valores de `3` a `6`, porém
  o elemento `cobv.valor.desconto.valorPerc` não está preenchido.
  - O objeto `cobv.valor.desconto` apresenta modalidade nos valores de `3` a `6`, porém
  o elemento `cobv.valor.desconto.descontoDataFixa` está preenchido ou não nulo.



__Violações__ específicas para o endpoint `PUT /cobv/{txid}`:
  - A cobrança já existe, não está ATIVA, e a presente requisição busca alterá-la

__Violações__ específicas para o endpoint `PATCH /cobv/{txid}`:
  - A cobrança não está ATIVA, e a presente requisição busca alterá-la
  - A cobrança está ATIVA, e a presente requisição propõe alterar
  seu status para _REMOVIDA_PELO_USUARIO_RECEBEDOR_ juntamente com outras alterações
  (não faz sentido remover uma cobrança ao mesmo tempo em que se realizam
  alterações que não serão aproveitadas).
  - o campo `cob.status` não respeita o _schema_.

### `CobVConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de cobranças com vencimento não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /cobv` e `GET /cobv/{txid}`.

__Violações__ específicas para o endpoint `GET /cobv`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

__Violações__ específicas para o endpoint `GET /cobv/{txid}`:
  - o parâmetro `revisao` corresponde a uma revisão inexistente para a cobrança
  apontada pelo parâmetro `txid`.

## Tag LoteCobV
Esta seção reúne erros referentes a endpoints que tratam do gerenciamento de lotes de cobrança.

### `LoteCobVNaoEncontrado`
* __Significado__: Lote não encontrado para o `id` informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET|PATCH] /lotecobv/{id}`.

### `LoteCobVOperacaoInvalida`
* __Significado__: a requisição que busca alterar ou criar um lote de cobranças com vencimento
não respeita o _schema_ ou está semanticamente errada.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `[PUT|PATCH] /lotecobv/{id}`.

__Violações__ para os endpoints `PUT|PATCH /lotecobv/{id}`:
  - O campo `loteCobV.descricao` não respeita o _schema_.
  - O objeto `loteCobV.cobsV` não respeita o _schema_.

__Violações__ para o endpoint `PUT /lotecobv/{id}`:
  - a presente requisição tenta criar um conjunto de cobranças dentre as quais pelo menos
  uma cobrança já encontra-se criada.
  - a presente requisição busca alterar um lote já existente, entretanto contém um array de
  solicitações de alteração de cobranças que não referencia exatamente as mesmas cobranças
  referenciadas pela requisição original que criou o lote.
  Uma vez criado um lote, não se pode remover ou adicionar solicitações de
  criação ou alteração de cobranças a este lote.

__Violações__ para o endpoint `PATCH /lotecobv/{id}`:
  - a presente requisição busca alterar um lote já existente e contém, no `array`
  de cobranças representado por `cobsv`, uma cobrança não existente no array de cobranças
  atribuído pela requisição original que criou o lote.
  Uma vez criado um lote, não se pode remover ou adicionar cobranças a este lote.

__Violações__ para os endpoints `GET /lotecobv/{id}`:
  - __observação__: para cada elemento do array `cobsv`, retornado por este endpoint, caso a requisição de criação de cobrança esteja em
  status \"NEGADA\", o atributo `problema` deste elemento deve ser preenchido respeitando
  o [schema](https://tools.ietf.org/html/rfc7807) referenciado pela API Pix.
  - o preenchimento do atributo `problema`, conforme descrito acima, segue o mesmo regramento dos
  erros especificados para os endpoints `[PUT/PATCH /cobv/{txid}]`, de maneira a possibilitar, ao
  usuário recebedor, entender qual foi a violação cometida ao se tentar criar
  a cobrança referenciada por este elemento do array `cobsv`.

### `LoteCobVConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de lotes de cobrança com vencimento
não respeitam o _schema_ ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /lotecobv` e `GET /lotecobv/{id}`.

__Violações__ específicas para o endpoint `GET /lotecobv`:
  - algum dos parâmetros informados para a consulta não respeitam o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

## Tag PayloadLocation
Esta seção reúne erros referentes a endpoints que tratam do gerenciamento de _locations_.

### `PayloadLocationNaoEncontrado`
* __Significado__: _Location_ não encontrada para o `id` informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET|PATCH] /loc/{id}`, `DELETE /loc/{id}/txid`.

### `PayloadLocationOperacaoInvalida`

* __Significado__: a presente requisição busca criar uma location sem respeitar o _schema_ estabelecido.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `POST /loc`.

__Violações__ para o endpoint `POST /loc`:
  - o campo `tipoCob` não respeita o _schema_.

### `PayloadLocationConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de _locations_ não respeitam
o _schema_ ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /loc` e `GET /loc/{id}`.

__Violações__ específicas para o endpoint `GET /loc`:
  - algum dos parâmetros informados para a consulta não respeitam o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

## Tag PayloadLocationRec
Esta seção reúne erros referentes a endpoints que tratam do gerenciamento de _locations_ de uma recorrência.

### `PayloadLocationRecNaoEncontrado`
* __Significado__: _Location_ não encontrada para o `id` informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `[GET] /locrec/{id}`, `DELETE /locrec/{id}/idRec`.

### `PayloadLocationRecConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de _locations_ não respeitam
o _schema_ ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /locrec` e `GET /locrec/{id}`.

__Violações__ específicas para o endpoint `GET /locrec`:
  - algum dos parâmetros informados para a consulta não respeitam o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

## Tag Pix

Reúne erros em endpoints de gestão de Pix recebidos e solicitação de devoluções.

### `PixNaoEncontrado`

* __Significado__: pix não encontrada para o `e2eid` informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `GET /pix/{e2eid}`

### `PixDevolucaoNaoEncontrada`

* __Significado__: devolução representada por {id} não encontrada para o `e2eid` informado.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `GET /pix/{e2eid}/devolucao/{id}`

### `PixConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de pix recebidos não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /pix`.

__Violações__ específicas para o endpoint `GET /pix`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - ambos os parâmetros `cpf` e `cnpj` estão preenchidos.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

### `PixDevolucaoInvalida`

* __Significado__: a presente requisição de devolução não respeita o _schema_ ou não faz sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `PUT /pix/{e2eid}/devolucao/{id}`.

__Violações__ específicas para o endpoint `PUT /pix/{e2eid}/devolucao/{id}`:
  - O campo `devolucao.valor` não respeita o _schema_.
  - A presente requisição de devolução, em conjunto com as demais prévias devoluções,
  se aplicável, excederia o valor do pix originário.
  - A presente requisição de devolução apresenta um `{id}` já utilizado por outra requisição de
  devolução para o `{e2eid}` em questão.
  - A presente requisição de devolução viola a janela de tempo permitida para solicitações de devoluções
  de um pix (hoje estabelecida como 90 dias desde a data de liquidação original do pix).

## Tag Webhook
Reúne erros dos endpoints que tratam do gerenciamento dos Webhooks da API Pix.

### `WebhookOperacaoInvalida`
* __Significado__: a presente requisição busca criar um webhook sem respeitar o _schema_ ou,
ainda, apresenta semântica inválida.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `PUT /webhook/{chave}`.

__Violações__ para o endpoint `PUT /webhook/{chave}`:
  - o parâmetro {chave} não corresponde a uma chave DICT válida.
  - o parâmetro {chave} não corresponde a uma chave DICT pertencente a este usuário recebedor.
  - Campo webhook.webhookUrl não respeita o _schema_.

### `WebhookNaoEncontrado`

* __Significado__: o webhook denotado por {chave} não encontra-se estabelecido.
* __HTTP Status Code__: [404](https://tools.ietf.org/html/rfc7231#section-6.5.4).
* __endpoints__: `GET /webhook/{chave}`,  `DELETE /webhook/{chave}`

### `WebhookConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de webhooks ativados não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /webhook`.

__Violações__ específicas para o endpoint `GET /webhook`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

## Tag WebhookRec
Reúne erros dos endpoints que tratam do gerenciamento dos Webhooks de recorrências da API Pix.

### `WebhookRecOperacaoInvalida`
* __Significado__: a presente requisição busca criar um webhook sem respeitar o _schema_ ou,
ainda, apresenta semântica inválida.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `PUT /webhookrec`.

__Violações__ para o endpoint `PUT /webhookrec`:
  - o campo `webhookUrl` não respeita o _schema_.

### `WebhookRecConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de webhooks ativados não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /webhookrec`.

__Violações__ específicas para o endpoint `GET /webhookrec`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

## Tag WebhookCobR
Reúne erros dos endpoints que tratam do gerenciamento dos Webhooks de cobranças recorrentes da API Pix.

### `WebhookCobROperacaoInvalida`
* __Significado__: a presente requisição busca criar um webhook sem respeitar o _schema_ ou,
ainda, apresenta semântica inválida.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `PUT /webhookcobr`.

__Violações__ para o endpoint `PUT /webhookcobr`:
  - o campo `webhookUrl` não respeita o _schema_.

### `WebhookCobRConsultaInvalida`

* __Significado__: os parâmetros de consulta à lista de webhooks ativados não respeitam o schema
ou não fazem sentido semanticamente.
* __HTTP Status Code__: [400](https://tools.ietf.org/html/rfc7231#section-6.5.1).
* __endpoints__: `GET /webhookcobr`.

__Violações__ específicas para o endpoint `GET /webhookcobr`:
  - algum dos parâmetros informados para a consulta não respeita o _schema_.
  - o _timestamp_ representado pelo parâmetro `fim` é anterior ao timestamp
  representado pelo parâmetro `inicio`.
  - o parâmetro `paginacao.paginaAtual` é negativo.
  - o parâmetro `paginacao.itensPorPagina` é negativo.

For more information, please visit [https://www.bcb.gov.br/estabilidadefinanceira/pix](https://www.bcb.gov.br/estabilidadefinanceira/pix).

## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure OAuth2 access token for authorization: OAuth2
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\CobApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$inicio = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$fim = new \DateTime('2013-10-20T19:20:30+01:00'); // \DateTime
$cpf = 'cpf_example'; // string
$cnpj = 'cnpj_example'; // string
$location_presente = True; // bool
$status = 'status_example'; // string
$paginacao_pagina_atual = 0; // int
$paginacao_itens_por_pagina = 100; // int

try {
    $result = $apiInstance->cobGet($inicio, $fim, $cpf, $cnpj, $location_presente, $status, $paginacao_pagina_atual, $paginacao_itens_por_pagina);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CobApi->cobGet: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://pix.example.com/api*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*CobApi* | [**cobGet**](docs/Api/CobApi.md#cobget) | **GET** /cob | Consultar lista de cobranças imediatas.
*CobApi* | [**cobPost**](docs/Api/CobApi.md#cobpost) | **POST** /cob | Criar cobrança imediata.
*CobApi* | [**cobTxidGet**](docs/Api/CobApi.md#cobtxidget) | **GET** /cob/{txid} | Consultar cobrança imediata.
*CobApi* | [**cobTxidPatch**](docs/Api/CobApi.md#cobtxidpatch) | **PATCH** /cob/{txid} | Revisar cobrança imediata.
*CobApi* | [**cobTxidPut**](docs/Api/CobApi.md#cobtxidput) | **PUT** /cob/{txid} | Criar cobrança imediata.
*CobPayloadApi* | [**cobvPixUrlAccessTokenGet**](docs/Api/CobPayloadApi.md#cobvpixurlaccesstokenget) | **GET** /cobv/{pixUrlAccessToken} | Recuperar o payload JSON que representa a cobrança com vencimento.
*CobPayloadApi* | [**pixUrlAccessTokenGet**](docs/Api/CobPayloadApi.md#pixurlaccesstokenget) | **GET** /{pixUrlAccessToken} | Recuperar o payload JSON que representa a cobrança imediata.
*CobRApi* | [**cobrGet**](docs/Api/CobRApi.md#cobrget) | **GET** /cobr | Consultar lista de cobranças recorrentes.
*CobRApi* | [**cobrPost**](docs/Api/CobRApi.md#cobrpost) | **POST** /cobr | Criar cobrança recorrente.
*CobRApi* | [**cobrTxidGet**](docs/Api/CobRApi.md#cobrtxidget) | **GET** /cobr/{txid} | Consultar cobrança recorrente.
*CobRApi* | [**cobrTxidPatch**](docs/Api/CobRApi.md#cobrtxidpatch) | **PATCH** /cobr/{txid} | Revisar cobrança recorrente.
*CobRApi* | [**cobrTxidPut**](docs/Api/CobRApi.md#cobrtxidput) | **PUT** /cobr/{txid} | Criar cobrança recorrente.
*CobRApi* | [**cobrTxidRetentativaDataPost**](docs/Api/CobRApi.md#cobrtxidretentativadatapost) | **POST** /cobr/{txid}/retentativa/{data} | Solicitar retentativa de cobrança.
*CobVApi* | [**cobvGet**](docs/Api/CobVApi.md#cobvget) | **GET** /cobv | Consultar lista de cobranças com vencimento.
*CobVApi* | [**cobvTxidGet**](docs/Api/CobVApi.md#cobvtxidget) | **GET** /cobv/{txid} | Consultar cobrança com vencimento.
*CobVApi* | [**cobvTxidPatch**](docs/Api/CobVApi.md#cobvtxidpatch) | **PATCH** /cobv/{txid} | Revisar cobrança com vencimento.
*CobVApi* | [**cobvTxidPut**](docs/Api/CobVApi.md#cobvtxidput) | **PUT** /cobv/{txid} | Criar cobrança com vencimento.
*LoteCobVApi* | [**lotecobvGet**](docs/Api/LoteCobVApi.md#lotecobvget) | **GET** /lotecobv | Consultar lotes de cobranças com vencimento.
*LoteCobVApi* | [**lotecobvIdGet**](docs/Api/LoteCobVApi.md#lotecobvidget) | **GET** /lotecobv/{id} | Consultar um lote específico de cobranças com vencimento.
*LoteCobVApi* | [**lotecobvIdPatch**](docs/Api/LoteCobVApi.md#lotecobvidpatch) | **PATCH** /lotecobv/{id} | Utilizado para revisar cobranças específicas dentro de um lote de cobranças com vencimento.
*LoteCobVApi* | [**lotecobvIdPut**](docs/Api/LoteCobVApi.md#lotecobvidput) | **PUT** /lotecobv/{id} | Criar/Alterar lote de cobranças com vencimento.
*PayloadLocationApi* | [**locGet**](docs/Api/PayloadLocationApi.md#locget) | **GET** /loc | Consultar locations cadastradas.
*PayloadLocationApi* | [**locIdGet**](docs/Api/PayloadLocationApi.md#locidget) | **GET** /loc/{id} | Recuperar location do payload.
*PayloadLocationApi* | [**locIdTxidDelete**](docs/Api/PayloadLocationApi.md#locidtxiddelete) | **DELETE** /loc/{id}/txid | Desvincular uma cobrança de uma location.
*PayloadLocationApi* | [**locPost**](docs/Api/PayloadLocationApi.md#locpost) | **POST** /loc | Criar location do payload.
*PayloadLocationRecApi* | [**locrecGet**](docs/Api/PayloadLocationRecApi.md#locrecget) | **GET** /locrec | Consultar locations cadastradas.
*PayloadLocationRecApi* | [**locrecIdGet**](docs/Api/PayloadLocationRecApi.md#locrecidget) | **GET** /locrec/{id} | Recuperar location do payload.
*PayloadLocationRecApi* | [**locrecIdIdRecDelete**](docs/Api/PayloadLocationRecApi.md#locrecididrecdelete) | **DELETE** /locrec/{id}/idRec | Desvincular uma recorrência de uma location.
*PayloadLocationRecApi* | [**locrecPost**](docs/Api/PayloadLocationRecApi.md#locrecpost) | **POST** /locrec | Criar location do payload.
*PixApi* | [**pixE2eidDevolucaoIdGet**](docs/Api/PixApi.md#pixe2eiddevolucaoidget) | **GET** /pix/{e2eid}/devolucao/{id} | Consultar devolução.
*PixApi* | [**pixE2eidDevolucaoIdPut**](docs/Api/PixApi.md#pixe2eiddevolucaoidput) | **PUT** /pix/{e2eid}/devolucao/{id} | Solicitar devolução.
*PixApi* | [**pixE2eidGet**](docs/Api/PixApi.md#pixe2eidget) | **GET** /pix/{e2eid} | Consultar Pix.
*PixApi* | [**pixGet**](docs/Api/PixApi.md#pixget) | **GET** /pix | Consultar Pix recebidos.
*RecApi* | [**recGet**](docs/Api/RecApi.md#recget) | **GET** /rec | Consultar lista de recorrências.
*RecApi* | [**recIdRecGet**](docs/Api/RecApi.md#recidrecget) | **GET** /rec/{idRec} | Consultar recorrência.
*RecApi* | [**recIdRecPatch**](docs/Api/RecApi.md#recidrecpatch) | **PATCH** /rec/{idRec} | Revisar recorrência.
*RecApi* | [**recPost**](docs/Api/RecApi.md#recpost) | **POST** /rec | Criar recorrência.
*RecPayloadApi* | [**recRecUrlAccessTokenGet**](docs/Api/RecPayloadApi.md#recrecurlaccesstokenget) | **GET** /rec/{recUrlAccessToken} | Recuperar o payload JSON que representa a configuração da recorrência.
*SolicRecApi* | [**solicrecIdSolicRecGet**](docs/Api/SolicRecApi.md#solicrecidsolicrecget) | **GET** /solicrec/{idSolicRec} | Consultar solicitação de confirmação de recorrência.
*SolicRecApi* | [**solicrecIdSolicRecPatch**](docs/Api/SolicRecApi.md#solicrecidsolicrecpatch) | **PATCH** /solicrec/{idSolicRec} | Revisar solicitação de confirmação de recorrência.
*SolicRecApi* | [**solicrecPost**](docs/Api/SolicRecApi.md#solicrecpost) | **POST** /solicrec | Criar solicitação de confirmação de recorrência.
*WebhookApi* | [**webhookChaveDelete**](docs/Api/WebhookApi.md#webhookchavedelete) | **DELETE** /webhook/{chave} | Cancelar o webhook Pix.
*WebhookApi* | [**webhookChaveGet**](docs/Api/WebhookApi.md#webhookchaveget) | **GET** /webhook/{chave} | Exibir informações acerca do Webhook Pix.
*WebhookApi* | [**webhookChavePut**](docs/Api/WebhookApi.md#webhookchaveput) | **PUT** /webhook/{chave} | Configurar o Webhook Pix.
*WebhookApi* | [**webhookGet**](docs/Api/WebhookApi.md#webhookget) | **GET** /webhook | Consultar webhooks cadastrados.
*WebhookCobRApi* | [**webhookcobrDelete**](docs/Api/WebhookCobRApi.md#webhookcobrdelete) | **DELETE** /webhookcobr | Cancelar o Webhook.
*WebhookCobRApi* | [**webhookcobrGet**](docs/Api/WebhookCobRApi.md#webhookcobrget) | **GET** /webhookcobr | Exibir informações acerca do Webhook.
*WebhookCobRApi* | [**webhookcobrPut**](docs/Api/WebhookCobRApi.md#webhookcobrput) | **PUT** /webhookcobr | Configurar Webhook.
*WebhookRecApi* | [**webhookrecDelete**](docs/Api/WebhookRecApi.md#webhookrecdelete) | **DELETE** /webhookrec | Cancelar o Webhook.
*WebhookRecApi* | [**webhookrecGet**](docs/Api/WebhookRecApi.md#webhookrecget) | **GET** /webhookrec | Exibir informações acerca do Webhook.
*WebhookRecApi* | [**webhookrecPut**](docs/Api/WebhookRecApi.md#webhookrecput) | **PUT** /webhookrec | Configurar Webhook.

## Models

- [AbatimentoAplicado](docs/Model/AbatimentoAplicado.md)
- [CNPJ](docs/Model/CNPJ.md)
- [CPF](docs/Model/CPF.md)
- [CalendRio](docs/Model/CalendRio.md)
- [CalendRio1](docs/Model/CalendRio1.md)
- [CalendRio2](docs/Model/CalendRio2.md)
- [CalendRio3](docs/Model/CalendRio3.md)
- [CobApresentacao](docs/Model/CobApresentacao.md)
- [CobBase](docs/Model/CobBase.md)
- [CobBaseCopiaCola](docs/Model/CobBaseCopiaCola.md)
- [CobCompleta](docs/Model/CobCompleta.md)
- [CobCompletaAllOfPix](docs/Model/CobCompletaAllOfPix.md)
- [CobCriacao](docs/Model/CobCriacao.md)
- [CobDataDeVencimento](docs/Model/CobDataDeVencimento.md)
- [CobExpiracao](docs/Model/CobExpiracao.md)
- [CobGerada](docs/Model/CobGerada.md)
- [CobGeradaAllOfDevedor](docs/Model/CobGeradaAllOfDevedor.md)
- [CobPayload](docs/Model/CobPayload.md)
- [CobPayloadAllOfDevedor](docs/Model/CobPayloadAllOfDevedor.md)
- [CobPayloadValor](docs/Model/CobPayloadValor.md)
- [CobRAtualizacao](docs/Model/CobRAtualizacao.md)
- [CobRBase](docs/Model/CobRBase.md)
- [CobRCompleta](docs/Model/CobRCompleta.md)
- [CobRCompletaAllOfPix](docs/Model/CobRCompletaAllOfPix.md)
- [CobRConfiguracao](docs/Model/CobRConfiguracao.md)
- [CobRGerada](docs/Model/CobRGerada.md)
- [CobRGeradaAllOfCalendario](docs/Model/CobRGeradaAllOfCalendario.md)
- [CobRNotification](docs/Model/CobRNotification.md)
- [CobRNotificationAllOfPix](docs/Model/CobRNotificationAllOfPix.md)
- [CobRRevisada](docs/Model/CobRRevisada.md)
- [CobRSolicitada](docs/Model/CobRSolicitada.md)
- [CobRStatus](docs/Model/CobRStatus.md)
- [CobRStatusRevisada](docs/Model/CobRStatusRevisada.md)
- [CobRTentativas](docs/Model/CobRTentativas.md)
- [CobRevisada](docs/Model/CobRevisada.md)
- [CobRevisadaAllOfDevedor](docs/Model/CobRevisadaAllOfDevedor.md)
- [CobSolicitada](docs/Model/CobSolicitada.md)
- [CobSolicitadaAllOfDevedor](docs/Model/CobSolicitadaAllOfDevedor.md)
- [CobVCompleta](docs/Model/CobVCompleta.md)
- [CobVCompletaAllOfPix](docs/Model/CobVCompletaAllOfPix.md)
- [CobVGerada](docs/Model/CobVGerada.md)
- [CobVPayload](docs/Model/CobVPayload.md)
- [CobVPayloadAllOfDevedor](docs/Model/CobVPayloadAllOfDevedor.md)
- [CobVPayloadValor](docs/Model/CobVPayloadValor.md)
- [CobVRevisada](docs/Model/CobVRevisada.md)
- [CobVSolicitada](docs/Model/CobVSolicitada.md)
- [CobVValor](docs/Model/CobVValor.md)
- [CobValor](docs/Model/CobValor.md)
- [CobranAImediataVinculadaJornada3](docs/Model/CobranAImediataVinculadaJornada3.md)
- [CobrancaStatus](docs/Model/CobrancaStatus.md)
- [CobsConsultadas](docs/Model/CobsConsultadas.md)
- [CobsRConsultadas](docs/Model/CobsRConsultadas.md)
- [CobsVConsultadas](docs/Model/CobsVConsultadas.md)
- [DadosBancarios](docs/Model/DadosBancarios.md)
- [DadosBancariosRecebedor](docs/Model/DadosBancariosRecebedor.md)
- [DadosComplementaresPessoa](docs/Model/DadosComplementaresPessoa.md)
- [DadosDevedor](docs/Model/DadosDevedor.md)
- [DadosDevedorDevedor](docs/Model/DadosDevedorDevedor.md)
- [DadosDevedorRecorrencia](docs/Model/DadosDevedorRecorrencia.md)
- [DadosDevedorRecorrenciaDevedor](docs/Model/DadosDevedorRecorrenciaDevedor.md)
- [DadosPagadorRec](docs/Model/DadosPagadorRec.md)
- [DadosPagadorRecPagador](docs/Model/DadosPagadorRecPagador.md)
- [DadosRecebedor](docs/Model/DadosRecebedor.md)
- [DadosRecebedorRecebedorOneOf](docs/Model/DadosRecebedorRecebedorOneOf.md)
- [DadosRelacionadosConfirmaODaAtivaODaRecorrNcia](docs/Model/DadosRelacionadosConfirmaODaAtivaODaRecorrNcia.md)
- [DadosRelacionadosConfirmaODaAtivaODaRecorrNcia1](docs/Model/DadosRelacionadosConfirmaODaAtivaODaRecorrNcia1.md)
- [DescontosAplicados](docs/Model/DescontosAplicados.md)
- [DescontosAplicadosOneOf](docs/Model/DescontosAplicadosOneOf.md)
- [DescontosAplicadosOneOf1](docs/Model/DescontosAplicadosOneOf1.md)
- [DescriODoObjetoDaRecorrNcia](docs/Model/DescriODoObjetoDaRecorrNcia.md)
- [DescriODoObjetoDaRecorrNciaDevedor](docs/Model/DescriODoObjetoDaRecorrNciaDevedor.md)
- [DetalhamentoDoEncerramentoDaCobranA](docs/Model/DetalhamentoDoEncerramentoDaCobranA.md)
- [DetalhamentoDoEncerramentoDaCobranAOneOf](docs/Model/DetalhamentoDoEncerramentoDaCobranAOneOf.md)
- [DetalhamentoDoEncerramentoDaCobranAOneOf1](docs/Model/DetalhamentoDoEncerramentoDaCobranAOneOf1.md)
- [DetalhamentoDoEncerramentoDaRecorrNcia](docs/Model/DetalhamentoDoEncerramentoDaRecorrNcia.md)
- [DetalhamentoDoEncerramentoDaRecorrNciaOneOf](docs/Model/DetalhamentoDoEncerramentoDaRecorrNciaOneOf.md)
- [DetalhamentoDoEncerramentoDaRecorrNciaOneOf1](docs/Model/DetalhamentoDoEncerramentoDaRecorrNciaOneOf1.md)
- [Devolucao](docs/Model/Devolucao.md)
- [DevolucaoHorario](docs/Model/DevolucaoHorario.md)
- [DevolucaoNatureza](docs/Model/DevolucaoNatureza.md)
- [DevolucaoNaturezaPixAutomatico](docs/Model/DevolucaoNaturezaPixAutomatico.md)
- [DevolucaoPixAutomatico](docs/Model/DevolucaoPixAutomatico.md)
- [DevolucaoSolicitada](docs/Model/DevolucaoSolicitada.md)
- [DevolucaoSolicitadaNatureza](docs/Model/DevolucaoSolicitadaNatureza.md)
- [HistRicoDeStatusDaSolicitaODeRecorrNciaInner](docs/Model/HistRicoDeStatusDaSolicitaODeRecorrNciaInner.md)
- [HistRicoDeStatusDaTentativaInner](docs/Model/HistRicoDeStatusDaTentativaInner.md)
- [HistRicoDeStatusInner](docs/Model/HistRicoDeStatusInner.md)
- [HistRicoDeStatusInner1](docs/Model/HistRicoDeStatusInner1.md)
- [HistRicoDeTentativasDeCobranAInner](docs/Model/HistRicoDeTentativasDeCobranAInner.md)
- [InfoBaseAgenciaConta](docs/Model/InfoBaseAgenciaConta.md)
- [InfoBaseChave](docs/Model/InfoBaseChave.md)
- [InformaEsAdicionaisInner](docs/Model/InformaEsAdicionaisInner.md)
- [InformaEsDeCalendRioDaSolicitaODaRecorrNcia](docs/Model/InformaEsDeCalendRioDaSolicitaODaRecorrNcia.md)
- [InformaEsDeRetirada](docs/Model/InformaEsDeRetirada.md)
- [InformaEsDeRetiradaOneOf](docs/Model/InformaEsDeRetiradaOneOf.md)
- [InformaEsDeRetiradaOneOf1](docs/Model/InformaEsDeRetiradaOneOf1.md)
- [InformaEsDoQRComposto](docs/Model/InformaEsDoQRComposto.md)
- [InformaEsSobreARejeiODaCobranA](docs/Model/InformaEsSobreARejeiODaCobranA.md)
- [InformaEsSobreARejeiODaRecorrNcia](docs/Model/InformaEsSobreARejeiODaRecorrNcia.md)
- [InformaEsSobreARejeiODaTentativaDaCobranA](docs/Model/InformaEsSobreARejeiODaTentativaDaCobranA.md)
- [InformaEsSobreCalendRioDaCobranA](docs/Model/InformaEsSobreCalendRioDaCobranA.md)
- [InformaEsSobreCalendRioDaRecorrNcia](docs/Model/InformaEsSobreCalendRioDaRecorrNcia.md)
- [InformaEsSobreCalendRioDaRecorrNcia1](docs/Model/InformaEsSobreCalendRioDaRecorrNcia1.md)
- [InformaEsSobreOCancelamentoDaCobranA](docs/Model/InformaEsSobreOCancelamentoDaCobranA.md)
- [InformaEsSobreOCancelamentoDaRecorrNcia](docs/Model/InformaEsSobreOCancelamentoDaRecorrNcia.md)
- [InformaEsSobreOValorDoPix](docs/Model/InformaEsSobreOValorDoPix.md)
- [JuroAplicado](docs/Model/JuroAplicado.md)
- [ListaDeDescontosInner](docs/Model/ListaDeDescontosInner.md)
- [LoteCobVConsultado](docs/Model/LoteCobVConsultado.md)
- [LoteCobVConsultadoCobsvInner](docs/Model/LoteCobVConsultadoCobsvInner.md)
- [LoteCobVGerado](docs/Model/LoteCobVGerado.md)
- [LotecobvIdPatchRequest](docs/Model/LotecobvIdPatchRequest.md)
- [LotecobvIdPatchRequestCobsvInner](docs/Model/LotecobvIdPatchRequestCobsvInner.md)
- [LotecobvIdPutRequest](docs/Model/LotecobvIdPutRequest.md)
- [LotecobvIdPutRequestCobsvInner](docs/Model/LotecobvIdPutRequestCobsvInner.md)
- [LotesCobVConsultados](docs/Model/LotesCobVConsultados.md)
- [MultaAplicada](docs/Model/MultaAplicada.md)
- [Paginacao](docs/Model/Paginacao.md)
- [ParametrosConsultaCob](docs/Model/ParametrosConsultaCob.md)
- [ParametrosConsultaCobR](docs/Model/ParametrosConsultaCobR.md)
- [ParametrosConsultaLote](docs/Model/ParametrosConsultaLote.md)
- [ParametrosConsultaPayloadLocation](docs/Model/ParametrosConsultaPayloadLocation.md)
- [ParametrosConsultaPayloadLocationRec](docs/Model/ParametrosConsultaPayloadLocationRec.md)
- [ParametrosConsultaPix](docs/Model/ParametrosConsultaPix.md)
- [ParametrosConsultaRec](docs/Model/ParametrosConsultaRec.md)
- [ParametrosConsultaWebhooks](docs/Model/ParametrosConsultaWebhooks.md)
- [PayloadLocation](docs/Model/PayloadLocation.md)
- [PayloadLocationCob](docs/Model/PayloadLocationCob.md)
- [PayloadLocationCompleta](docs/Model/PayloadLocationCompleta.md)
- [PayloadLocationConsultadas](docs/Model/PayloadLocationConsultadas.md)
- [PayloadLocationRecCompleta](docs/Model/PayloadLocationRecCompleta.md)
- [PayloadLocationRecConsultadas](docs/Model/PayloadLocationRecConsultadas.md)
- [PayloadLocationRecGerada](docs/Model/PayloadLocationRecGerada.md)
- [PayloadLocationRecSolicitada](docs/Model/PayloadLocationRecSolicitada.md)
- [PayloadLocationSolicitada](docs/Model/PayloadLocationSolicitada.md)
- [PessoaFSica](docs/Model/PessoaFSica.md)
- [PessoaFisica](docs/Model/PessoaFisica.md)
- [PessoaFisicaRecorrencia](docs/Model/PessoaFisicaRecorrencia.md)
- [PessoaJurDica](docs/Model/PessoaJurDica.md)
- [PessoaJuridica](docs/Model/PessoaJuridica.md)
- [PessoaJuridicaRecorrencia](docs/Model/PessoaJuridicaRecorrencia.md)
- [Pix](docs/Model/Pix.md)
- [PixAutomatico](docs/Model/PixAutomatico.md)
- [PixConsultados](docs/Model/PixConsultados.md)
- [PixValorAbatimento](docs/Model/PixValorAbatimento.md)
- [PixValorAbatimentoAbatimento](docs/Model/PixValorAbatimentoAbatimento.md)
- [PixValorDesconto](docs/Model/PixValorDesconto.md)
- [PixValorDescontoDesconto](docs/Model/PixValorDescontoDesconto.md)
- [PixValorJuros](docs/Model/PixValorJuros.md)
- [PixValorJurosJuros](docs/Model/PixValorJurosJuros.md)
- [PixValorMulta](docs/Model/PixValorMulta.md)
- [PixValorMultaMulta](docs/Model/PixValorMultaMulta.md)
- [PixValorOriginal](docs/Model/PixValorOriginal.md)
- [PixValorOriginalOriginal](docs/Model/PixValorOriginalOriginal.md)
- [PixValorSaque](docs/Model/PixValorSaque.md)
- [PixValorSaqueSaque](docs/Model/PixValorSaqueSaque.md)
- [PixValorTroco](docs/Model/PixValorTroco.md)
- [PixValorTrocoTroco](docs/Model/PixValorTrocoTroco.md)
- [Problema](docs/Model/Problema.md)
- [RecAtivacao](docs/Model/RecAtivacao.md)
- [RecAtivacaoSolicitada](docs/Model/RecAtivacaoSolicitada.md)
- [RecAtualizacao](docs/Model/RecAtualizacao.md)
- [RecBase](docs/Model/RecBase.md)
- [RecBaseValor](docs/Model/RecBaseValor.md)
- [RecCompleta](docs/Model/RecCompleta.md)
- [RecCompletaAllOfRecebedor](docs/Model/RecCompletaAllOfRecebedor.md)
- [RecCompletaAllOfSolicitacao](docs/Model/RecCompletaAllOfSolicitacao.md)
- [RecCompletaPesquisada](docs/Model/RecCompletaPesquisada.md)
- [RecCompletaPesquisadaAllOfRecebedor](docs/Model/RecCompletaPesquisadaAllOfRecebedor.md)
- [RecCompletaPesquisadaAllOfSolicitacao](docs/Model/RecCompletaPesquisadaAllOfSolicitacao.md)
- [RecConfiguracao](docs/Model/RecConfiguracao.md)
- [RecEncerramento](docs/Model/RecEncerramento.md)
- [RecGerada](docs/Model/RecGerada.md)
- [RecGeradaAllOfRecebedor](docs/Model/RecGeradaAllOfRecebedor.md)
- [RecNotification](docs/Model/RecNotification.md)
- [RecPayload](docs/Model/RecPayload.md)
- [RecPayloadAllOfRecebedor](docs/Model/RecPayloadAllOfRecebedor.md)
- [RecRevisada](docs/Model/RecRevisada.md)
- [RecRevisadaAllOfVinculo](docs/Model/RecRevisadaAllOfVinculo.md)
- [RecRevisadaAllOfVinculoDevedor](docs/Model/RecRevisadaAllOfVinculoDevedor.md)
- [RecSolicitada](docs/Model/RecSolicitada.md)
- [RecSolicitadaAllOfRecebedor](docs/Model/RecSolicitadaAllOfRecebedor.md)
- [RecStatus](docs/Model/RecStatus.md)
- [Recebedor](docs/Model/Recebedor.md)
- [RecsConsultadas](docs/Model/RecsConsultadas.md)
- [Saque](docs/Model/Saque.md)
- [SolicRecAtualizacao](docs/Model/SolicRecAtualizacao.md)
- [SolicRecBase](docs/Model/SolicRecBase.md)
- [SolicRecCompleta](docs/Model/SolicRecCompleta.md)
- [SolicRecId](docs/Model/SolicRecId.md)
- [SolicRecRevisada](docs/Model/SolicRecRevisada.md)
- [SolicRecSolicitada](docs/Model/SolicRecSolicitada.md)
- [SolicRecStatus](docs/Model/SolicRecStatus.md)
- [Troco](docs/Model/Troco.md)
- [ValorDaCobranARecorrente](docs/Model/ValorDaCobranARecorrente.md)
- [ValorDoPix](docs/Model/ValorDoPix.md)
- [Violacao](docs/Model/Violacao.md)
- [WebhookChavePostRequest](docs/Model/WebhookChavePostRequest.md)
- [WebhookCobRCompleto](docs/Model/WebhookCobRCompleto.md)
- [WebhookCobRSolicitado](docs/Model/WebhookCobRSolicitado.md)
- [WebhookCompleto](docs/Model/WebhookCompleto.md)
- [WebhookRecBase](docs/Model/WebhookRecBase.md)
- [WebhookRecCompleto](docs/Model/WebhookRecCompleto.md)
- [WebhookRecSolicitado](docs/Model/WebhookRecSolicitado.md)
- [WebhookSolicitado](docs/Model/WebhookSolicitado.md)
- [WebhookcobrPostRequest](docs/Model/WebhookcobrPostRequest.md)
- [WebhookrecPostRequest](docs/Model/WebhookrecPostRequest.md)
- [WebhooksConsultados](docs/Model/WebhooksConsultados.md)
- [WebhooksRecConsultados](docs/Model/WebhooksRecConsultados.md)

## Authorization

Authentication schemes defined for the API:
### OAuth2

- **Type**: `OAuth`
- **Flow**: `application`
- **Authorization URL**: ``
- **Scopes**: 
    - **cob.write**: Permissão para alteração de cobranças imediatas
    - **cob.read**: Permissão para consulta de cobranças imediatas
    - **cobr.write**: Permissão para alteração de cobranças recorrentes
    - **cobr.read**: Permissão para consulta de cobranças recorrentes
    - **rec.write**: Permissão para alteração de recorrências
    - **rec.read**: Permissão para consulta de recorrências
    - **solicrec.write**: Permissão para alteração de solicitações de recorrências
    - **solicrec.read**: Permissão para consulta de solicitações de recorrências
    - **cobv.write**: Permissão para alteração de cobranças com vencimento
    - **cobv.read**: Permissão para consulta de cobranças com vencimento
    - **lotecobv.write**: Permissão para alteração de lotes de cobranças com vencimento
    - **lotecobv.read**: Permissão para consulta de lotes de cobranças com vencimento
    - **pix.write**: Permissão para alteração de Pix
    - **pix.read**: Permissão para consulta de Pix
    - **webhook.read**: Permissão para consulta do webhook
    - **webhook.write**: Permissão para alteração do webhook
    - **webhookrec.read**: Permissão para consulta do webhook
    - **webhookrec.write**: Permissão para alteração do webhook
    - **webhookcobr.read**: Permissão para consulta do webhook
    - **webhookcobr.write**: Permissão para alteração do webhook
    - **payloadlocation.write**: Permissão para alteração de payloads
    - **payloadlocation.read**: Permissão para consulta de payloads
    - **payloadlocationrec.write**: Permissão para alteração de payloads
    - **payloadlocationrec.read**: Permissão para consulta de payloads

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

suporte.pix@bcb.gov.br

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `2.9.0`
    - Generator version: `7.17.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
