#!/usr/bin/env sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
DEPLOY_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/.." && pwd)
ROLE=${1:-}
ENV_FILE=${2:-$DEPLOY_DIR/env/production.env}
REQUESTED_TAG=${3:-}

case "$ROLE" in
    app|workers|scheduler) ;;
    *)
        echo "Uso: $0 <app|workers|scheduler> [arquivo.env] [image-tag]" >&2
        exit 1
        ;;
esac

if [ -z "$REQUESTED_TAG" ]; then
    PREVIOUS_FILE="$DEPLOY_DIR/state/$ROLE.previous"
    if [ ! -f "$PREVIOUS_FILE" ]; then
        echo "Não há tag anterior registrada para $ROLE." >&2
        exit 1
    fi
    REQUESTED_TAG=$(cat "$PREVIOUS_FILE")
fi

echo "Rollback de $ROLE para $REQUESTED_TAG; migrações não serão revertidas."
OVERRIDE_IMAGE_TAG=$REQUESTED_TAG exec "$SCRIPT_DIR/deploy.sh" "$ROLE" "$ENV_FILE"

