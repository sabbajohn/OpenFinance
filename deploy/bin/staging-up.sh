#!/usr/bin/env sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
DEPLOY_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
ENV_FILE=${1:-$DEPLOY_DIR/env/staging.env}

if [ ! -f "$ENV_FILE" ]; then
    echo "Arquivo de ambiente não encontrado: $ENV_FILE" >&2
    exit 1
fi

compose() {
    docker compose --env-file "$ENV_FILE" --file "$DEPLOY_DIR/docker-compose.staging.yml" "$@"
}

compose config --quiet
compose pull
compose up --detach --wait postgres redis minio
compose up minio-init
compose --profile release run --rm release
compose up --detach --wait app web horizon scheduler

set -a
# shellcheck disable=SC1090
. "$ENV_FILE"
set +a
"$SCRIPT_DIR/healthcheck.sh" "${DEPLOY_HEALTH_URL:-${APP_URL%/}/api/v1/health}"

echo "Staging atualizado com a imagem $REGISTRY_IMAGE/app:$IMAGE_TAG."
