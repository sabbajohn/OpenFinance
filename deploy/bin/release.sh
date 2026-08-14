#!/usr/bin/env sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
DEPLOY_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
ENV_FILE=${1:-$DEPLOY_DIR/env/production.env}

if [ ! -f "$ENV_FILE" ]; then
    echo "Arquivo de ambiente não encontrado: $ENV_FILE" >&2
    exit 1
fi

set -a
# shellcheck disable=SC1090
. "$ENV_FILE"
set +a

PROJECT_NAME="${COMPOSE_PROJECT_NAME:-openfinance-production}-release"

docker compose --env-file "$ENV_FILE" --project-name "$PROJECT_NAME" \
    --file "$DEPLOY_DIR/docker-compose.production.yml" --profile release config --quiet
docker compose --env-file "$ENV_FILE" --project-name "$PROJECT_NAME" \
    --file "$DEPLOY_DIR/docker-compose.production.yml" --profile release pull release
docker compose --env-file "$ENV_FILE" --project-name "$PROJECT_NAME" \
    --file "$DEPLOY_DIR/docker-compose.production.yml" --profile release run --rm release

echo "Migrações concluídas para $IMAGE_TAG."

