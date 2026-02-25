# OpenFinance Workspace

Este workspace reúne os repositórios e scripts para gerar SDKs a partir das especificações OpenAPI do Open Finance Brasil.

## O que este README cobre

- Gerar SDKs individuais em `sdks/` a partir de `openapi/swagger-apis`
- Gerar SDK unificado em `unified-sdk/`
- Instalar dependências e validar o resultado

## Pré-requisitos

Tenha instalado na máquina:

- `git`
- `node` e `npm` (ou `npx`)
- `php` e `composer`

Opcional (para mocks):

- `docker`
- `docker compose`

## Estrutura relevante

- `generate-sdks.sh`: gera SDKs PHP individuais em `sdks/`
- `unify-sdks.sh`: consolida os SDKs em `unified-sdk/`
- `openapitools.json`: versão do OpenAPI Generator CLI
- `openapi/`: especificações OpenAPI

## Como gerar os arquivos

A partir da raiz do workspace:

```bash
cd /Users/johnsabba/Projects/OpenFinance
```

### 1) Gerar SDKs individuais

```bash
bash generate-sdks.sh
```

Resultado esperado:

- diretórios por domínio em `sdks/` (ex.: `sdks/accounts`, `sdks/payments`, etc.)

### 2) Gerar SDK unificado

```bash
bash unify-sdks.sh
```

Resultado esperado:

- `unified-sdk/` com `lib/`, `docs/`, `test/`, `composer.json` e demais arquivos de suporte

### 3) Instalar dependências dos SDKs

SDK Pix (quando necessário):

```bash
composer install -d pix-api-sdk-php --no-interaction
```

SDK unificado:

```bash
composer install -d unified-sdk --no-interaction
```

### 4) Validar a geração

```bash
composer validate -d unified-sdk --no-check-publish
```

Se quiser validar o compose dos mocks:

```bash
cd mock-api
docker compose config -q
```

## Atualizando especificações antes da geração (opcional)

Para garantir que você está usando o conteúdo mais recente dos repositórios locais:

```bash
git -C openapi fetch --all --prune
git -C mock-api fetch --all --prune
git -C pix-api fetch --all --prune
```

> Se quiser trazer mudanças para a sua branch local, faça também `git pull` dentro de cada repositório.

## Solução de problemas

- Erro de `composer.json` inválido no `unified-sdk`:
  - execute novamente `bash unify-sdks.sh` e valide com `composer validate -d unified-sdk`
- Falha no gerador OpenAPI:
  - confirme internet ativa e execução do `npx` sem bloqueios
- Scripts sem permissão:
  - execute com `bash generate-sdks.sh` e `bash unify-sdks.sh` (sem precisar `chmod +x`)

## Comandos rápidos (resumo)

```bash
cd /Users/johnsabba/Projects/OpenFinance
bash generate-sdks.sh
bash unify-sdks.sh
composer install -d unified-sdk --no-interaction
composer validate -d unified-sdk --no-check-publish
```
