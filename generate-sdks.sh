#!/bin/bash

# Caminho para o openapi-generator-cli (usando npx para garantir a versão mais recente)
GENERATOR="npx @openapitools/openapi-generator-cli"

# Linguagem alvo (java, php, cpp-restsdk, etc.)
LANGUAGE="php"

# Diretório base do repositório openapi
BASE_DIR="./openapi"

# Diretório de saída
OUTPUT_DIR="./sdks"

# Cria pasta de saída
mkdir -p $OUTPUT_DIR

# Função para gerar SDK
generate_sdk() {
  local spec=$1
  local name=$2
  echo "Gerando SDK para $name..."
  $GENERATOR generate \
    -i $spec \
    -g $LANGUAGE \
    -o $OUTPUT_DIR/$name
}

export -f generate_sdk
export GENERATOR
export LANGUAGE
export OUTPUT_DIR

# Loop em todas as subpastas de swagger-apis e encontra a versão mais recente (não beta) de cada API
find $BASE_DIR/swagger-apis -mindepth 1 -maxdepth 1 -type d | while read -r dir; do
  # Encontra o arquivo .yml com a versão mais recente (ordenado semanticamente), excluindo versões beta/rc
  latest_spec=$(find "$dir" -name "*.yml" | grep -v -E "(beta|rc|RC)" | sort -V | tail -1)
  if [ -n "$latest_spec" ]; then
    NAME=$(basename "$dir")
    generate_sdk "$latest_spec" "$NAME"
  fi
done

echo "✅ SDKs gerados em $OUTPUT_DIR"
