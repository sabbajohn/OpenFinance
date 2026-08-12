#!/bin/bash

# Diretório onde estão os SDKs individuais
SDKS_DIR="./sdks"

# Diretório de saída para o SDK unificado
UNIFIED_DIR="./unified-sdk"

# Remove diretório unificado se existir
rm -rf $UNIFIED_DIR

# Cria estrutura básica do SDK unificado
mkdir -p $UNIFIED_DIR/{lib/{Api,Model},test/{Api,Model},docs/{Api,Model}}

echo "🔧 Criando SDK unificado do Open Finance..."

# Copia arquivos base do primeiro SDK encontrado
first_sdk=$(find $SDKS_DIR -mindepth 1 -maxdepth 1 -type d | head -1)
if [ -n "$first_sdk" ]; then
  cp $first_sdk/.gitignore $UNIFIED_DIR/ 2>/dev/null || true
  cp $first_sdk/.php-cs-fixer.dist.php $UNIFIED_DIR/ 2>/dev/null || true
  cp $first_sdk/.travis.yml $UNIFIED_DIR/ 2>/dev/null || true
  cp $first_sdk/phpunit.xml.dist $UNIFIED_DIR/ 2>/dev/null || true
  cp $first_sdk/git_push.sh $UNIFIED_DIR/ 2>/dev/null || true
  cp $first_sdk/lib/{ApiException,Configuration,FormDataProcessor,ObjectSerializer,HeaderSelector}.php $UNIFIED_DIR/lib/ 2>/dev/null || true
  cp $first_sdk/lib/Model/ModelInterface.php $UNIFIED_DIR/lib/Model/ 2>/dev/null || true
  mkdir -p $UNIFIED_DIR/.openapi-generator
  cp $first_sdk/.openapi-generator/VERSION $UNIFIED_DIR/.openapi-generator/ 2>/dev/null || true
fi

# Inicializa arrays para consolidar informações
declare -a all_apis=()
declare -a all_models=()
declare -a all_dependencies=()

# Converte nomes de diretório como "automatic-payments" em identificadores PHP válidos.
studly_case() {
  printf '%s' "$1" | awk -F '[-_]' '{
    for (i = 1; i <= NF; i++) {
      printf "%s%s", toupper(substr($i, 1, 1)), substr($i, 2)
    }
  }'
}

# Loop através de todos os SDKs
for sdk_dir in $SDKS_DIR/*/; do
  if [ -d "$sdk_dir" ]; then
    sdk_name=$(basename "$sdk_dir")
    sdk_prefix=$(studly_case "$sdk_name")
    echo "📦 Processando SDK: $sdk_name"
    
    # Copia APIs com prefixo do domínio
    if [ -d "$sdk_dir/lib/Api" ]; then
      for api_file in "$sdk_dir/lib/Api"/*.php; do
        if [ -f "$api_file" ]; then
          api_basename=$(basename "$api_file" .php)
          new_api_name="${sdk_prefix}${api_basename}"
          
          # Copia e renomeia a classe
          sed "s/class $api_basename/class $new_api_name/g" "$api_file" > "$UNIFIED_DIR/lib/Api/${new_api_name}.php"
          all_apis+=("$new_api_name")
          
          # Copia documentação
          if [ -f "$sdk_dir/docs/Api/${api_basename}.md" ]; then
            cp "$sdk_dir/docs/Api/${api_basename}.md" "$UNIFIED_DIR/docs/Api/${new_api_name}.md"
          fi
          
          # Copia testes
          if [ -f "$sdk_dir/test/Api/${api_basename}Test.php" ]; then
            sed "s/class ${api_basename}Test/class ${new_api_name}Test/g" "$sdk_dir/test/Api/${api_basename}Test.php" > "$UNIFIED_DIR/test/Api/${new_api_name}Test.php"
          fi
        fi
      done
    fi
    
    # Copia Models com prefixo do domínio para evitar conflitos
    if [ -d "$sdk_dir/lib/Model" ]; then
      for model_file in "$sdk_dir/lib/Model"/*.php; do
        if [ -f "$model_file" ]; then
          model_basename=$(basename "$model_file" .php)
          
          # Pula modelos comuns que já existem
          if [[ "$model_basename" =~ ^(ModelInterface|Links|Meta|OpenDataMeta|ResponseError.*|CNPJ|Brand)$ ]]; then
            if [ ! -f "$UNIFIED_DIR/lib/Model/${model_basename}.php" ]; then
              cp "$model_file" "$UNIFIED_DIR/lib/Model/"
              cp "$sdk_dir/docs/Model/${model_basename}.md" "$UNIFIED_DIR/docs/Model/" 2>/dev/null || true
              cp "$sdk_dir/test/Model/${model_basename}Test.php" "$UNIFIED_DIR/test/Model/" 2>/dev/null || true
            fi
          else
            new_model_name="${sdk_prefix}${model_basename}"
            
            # Copia e renomeia a classe
            sed "s/class $model_basename/class $new_model_name/g" "$model_file" > "$UNIFIED_DIR/lib/Model/${new_model_name}.php"
            all_models+=("$new_model_name")
            
            # Copia documentação
            if [ -f "$sdk_dir/docs/Model/${model_basename}.md" ]; then
              cp "$sdk_dir/docs/Model/${model_basename}.md" "$UNIFIED_DIR/docs/Model/${new_model_name}.md"
            fi
            
            # Copia testes
            if [ -f "$sdk_dir/test/Model/${model_basename}Test.php" ]; then
              sed "s/class ${model_basename}Test/class ${new_model_name}Test/g" "$sdk_dir/test/Model/${model_basename}Test.php" > "$UNIFIED_DIR/test/Model/${new_model_name}Test.php"
            fi
          fi
        fi
      done
    fi
  fi
done

# Cria composer.json unificado
cat > $UNIFIED_DIR/composer.json << EOF
{
    "name": "openbanking-brasil/open-finance-unified-sdk",
    "description": "SDK PHP unificado para todas as APIs do Open Finance Brasil",
    "keywords": [
        "open finance",
        "open banking",
        "brasil",
        "api",
        "sdk",
        "php"
    ],
    "homepage": "https://github.com/OpenBanking-Brasil/openapi",
    "license": "MIT",
    "authors": [
        {
            "name": "Open Finance Brasil",
            "homepage": "https://openfinancebrasil.org.br/"
        }
    ],
    "require": {
        "php": "^8.1",
        "ext-curl": "*",
        "ext-json": "*",
        "ext-mbstring": "*",
        "guzzlehttp/guzzle": "^7.3",
        "guzzlehttp/psr7": "^1.7 || ^2.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^8.0 || ^9.0",
        "friendsofphp/php-cs-fixer": "^3.5"
    },
    "autoload": {
        "psr-4": {
            "OpenFinanceBrasil\\": "lib/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "OpenFinanceBrasil\\Test\\": "test/"
        }
    }
}
EOF

# Cria README.md unificado
cat > $UNIFIED_DIR/README.md << EOF
# Open Finance Brasil - SDK PHP Unificado

Este é o SDK PHP unificado para todas as APIs do Open Finance Brasil, gerado automaticamente a partir das especificações OpenAPI oficiais.

## Instalação

\`\`\`bash
composer install
\`\`\`

## Uso

\`\`\`php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

use OpenFinanceBrasil\Configuration;
use OpenFinanceBrasil\Api\AccountsApi;

// Configuração
\$config = Configuration::getDefaultConfiguration()
    ->setHost('https://api.banco.com.br/open-banking');

// Exemplo de uso - API de Contas
\$accountsApi = new AccountsApi(null, \$config);

try {
    \$result = \$accountsApi->getAccounts();
    print_r(\$result);
} catch (Exception \$e) {
    echo 'Erro: ', \$e->getMessage(), PHP_EOL;
}
?>
\`\`\`

## APIs Disponíveis

EOF

# Adiciona lista de APIs ao README
printf "### APIs de Dados\n" >> $UNIFIED_DIR/README.md
for api in "${all_apis[@]}"; do
    echo "- $api" >> $UNIFIED_DIR/README.md
done

printf "\n### Modelos de Dados\n" >> $UNIFIED_DIR/README.md
printf "Este SDK inclui %d modelos de dados para representar as estruturas das APIs.\n\n" ${#all_models[@]} >> $UNIFIED_DIR/README.md

cat >> $UNIFIED_DIR/README.md << EOF

## Estrutura

- \`lib/Api/\` - Classes das APIs
- \`lib/Model/\` - Modelos de dados
- \`test/\` - Testes unitários
- \`docs/\` - Documentação

## Executar Testes

\`\`\`bash
./vendor/bin/phpunit
\`\`\`

## Licença

MIT License

## Contribuição

Este SDK é gerado automaticamente. Para contribuições, consulte o repositório oficial:
https://github.com/OpenBanking-Brasil/openapi
EOF

# Cria arquivo com lista de componentes
cat > $UNIFIED_DIR/.openapi-generator/FILES << EOF
# OpenAPI Generator Files
lib/ApiException.php
lib/Configuration.php
lib/FormDataProcessor.php
lib/ObjectSerializer.php
lib/HeaderSelector.php
lib/Model/ModelInterface.php
composer.json
README.md
.gitignore
EOF

# Adiciona todas as APIs e Models ao arquivo FILES
for api in "${all_apis[@]}"; do
    echo "lib/Api/${api}.php" >> $UNIFIED_DIR/.openapi-generator/FILES
    echo "test/Api/${api}Test.php" >> $UNIFIED_DIR/.openapi-generator/FILES
    echo "docs/Api/${api}.md" >> $UNIFIED_DIR/.openapi-generator/FILES
done

for model in "${all_models[@]}"; do
    echo "lib/Model/${model}.php" >> $UNIFIED_DIR/.openapi-generator/FILES
    echo "test/Model/${model}Test.php" >> $UNIFIED_DIR/.openapi-generator/FILES
    echo "docs/Model/${model}.md" >> $UNIFIED_DIR/.openapi-generator/FILES
done

echo "✅ SDK unificado criado em $UNIFIED_DIR"
echo "📊 Total de APIs: ${#all_apis[@]}"
echo "📊 Total de Models: ${#all_models[@]}"
echo ""
echo "Para instalar dependências:"
echo "  cd $UNIFIED_DIR && composer install"
