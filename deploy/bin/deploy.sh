#!/usr/bin/env sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
DEPLOY_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
ROLE=${1:-}
ENV_FILE=${2:-$DEPLOY_DIR/env/production.env}

case "$ROLE" in
    app|workers|scheduler) ;;
    *)
        echo "Uso: $0 <app|workers|scheduler> [arquivo.env]" >&2
        exit 1
        ;;
esac

if [ ! -f "$ENV_FILE" ]; then
    echo "Arquivo de ambiente não encontrado: $ENV_FILE" >&2
    exit 1
fi

override_image_tag=${OVERRIDE_IMAGE_TAG:-}
set -a
# shellcheck disable=SC1090
. "$ENV_FILE"
set +a

if [ -n "$override_image_tag" ]; then
    IMAGE_TAG=$override_image_tag
    export IMAGE_TAG
fi

: "${REGISTRY_IMAGE:?REGISTRY_IMAGE é obrigatório}"
: "${IMAGE_TAG:?IMAGE_TAG é obrigatório}"

COMPOSE_FILE="$DEPLOY_DIR/docker-compose.production.yml"
PROJECT_NAME="${COMPOSE_PROJECT_NAME:-openfinance-production}-$ROLE"
STATE_DIR="$DEPLOY_DIR/state"
CURRENT_FILE="$STATE_DIR/$ROLE.current"
PREVIOUS_FILE="$STATE_DIR/$ROLE.previous"

mkdir -p "$STATE_DIR"

compose() {
    docker compose --env-file "$ENV_FILE" --project-name "$PROJECT_NAME" \
        --file "$COMPOSE_FILE" --profile "$ROLE" "$@"
}

echo "Validando configuração para $ROLE ($IMAGE_TAG)..."
compose config --quiet
compose pull
compose up --detach --wait --remove-orphans

if [ "$ROLE" = app ]; then
    "$SCRIPT_DIR/healthcheck.sh" "${DEPLOY_HEALTH_URL:-${APP_URL%/}/api/v1/health}"
fi

if [ -f "$CURRENT_FILE" ]; then
    current_tag=$(cat "$CURRENT_FILE")
    if [ "$current_tag" != "$IMAGE_TAG" ]; then
        printf '%s\n' "$current_tag" > "$PREVIOUS_FILE"
    fi
fi
printf '%s\n' "$IMAGE_TAG" > "$CURRENT_FILE"

echo "Deploy $ROLE concluído com a imagem $REGISTRY_IMAGE/app:$IMAGE_TAG."
