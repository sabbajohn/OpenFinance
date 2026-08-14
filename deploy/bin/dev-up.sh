#!/usr/bin/env sh
set -eu

SCRIPT_DIR=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
PROJECT_DIR=$(CDPATH= cd -- "$SCRIPT_DIR/../.." && pwd)
ENV_FILE="$PROJECT_DIR/platform/.env"
ENV_EXAMPLE="$PROJECT_DIR/platform/.env.example"

cd "$PROJECT_DIR"

if ! command -v docker >/dev/null 2>&1; then
    echo "Docker não está instalado ou não está no PATH." >&2
    exit 1
fi

if ! docker compose version >/dev/null 2>&1; then
    echo "O plugin Docker Compose v2 é obrigatório." >&2
    exit 1
fi

if [ ! -f "$ENV_FILE" ]; then
    cp "$ENV_EXAMPLE" "$ENV_FILE"
    echo "Criado platform/.env a partir do exemplo."
fi

if ! grep -Eq '^APP_KEY=base64:.+' "$ENV_FILE"; then
    generated_key="base64:$(openssl rand -base64 32 | tr -d '\n')"
    temporary_file=$(mktemp "${TMPDIR:-/tmp}/openfinance-env.XXXXXX")
    awk -v key="$generated_key" '
        BEGIN { replaced = 0 }
        /^APP_KEY=/ { print "APP_KEY=" key; replaced = 1; next }
        { print }
        END { if (!replaced) print "APP_KEY=" key }
    ' "$ENV_FILE" > "$temporary_file"
    mv "$temporary_file" "$ENV_FILE"
    chmod 600 "$ENV_FILE"
    echo "APP_KEY local gerada."
fi

echo "Construindo as imagens da aplicação..."
docker compose build app web horizon scheduler

echo "Iniciando PostgreSQL, Redis, MinIO e Mailpit..."
docker compose up -d --wait postgres redis minio mailpit
docker compose up minio-init

echo "Aplicando migrações e seed local idempotente..."
docker compose run --rm app php artisan migrate --force
docker compose run --rm app php artisan db:seed --force

echo "Iniciando aplicação, workers e scheduler..."
docker compose up -d --wait app web horizon scheduler mailpit

"$SCRIPT_DIR/healthcheck.sh" "${APP_URL:-http://127.0.0.1:${HTTP_PORT:-8080}}/api/v1/health"

if [ "${WITH_MONITORING:-0}" = "1" ]; then
    echo "Iniciando Prometheus, Alertmanager e Grafana..."
    docker compose --profile monitoring up -d --wait prometheus alertmanager grafana
fi

echo
echo "OpenFinance: http://localhost:${HTTP_PORT:-8080}"
echo "Mailpit:     http://localhost:${MAILPIT_PORT:-8025}"
echo "MinIO:       http://localhost:${MINIO_CONSOLE_PORT:-9001}"
if [ "${WITH_MONITORING:-0}" = "1" ]; then
    echo "Prometheus:  http://localhost:${PROMETHEUS_PORT:-9090}"
    echo "Grafana:     http://localhost:${GRAFANA_PORT:-3000}"
fi
echo "Login local: admin@openfinance.local / password"
echo "Os volumes locais são preservados por deploy/bin/dev-down.sh."
