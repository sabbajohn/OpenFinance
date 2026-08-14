#!/usr/bin/env sh
set -eu

URL=${1:-http://127.0.0.1:8080/api/v1/health}
ATTEMPTS=${HEALTHCHECK_ATTEMPTS:-30}
INTERVAL=${HEALTHCHECK_INTERVAL_SECONDS:-2}
attempt=1

while [ "$attempt" -le "$ATTEMPTS" ]; do
    response=$(curl --fail --silent --show-error --max-time 5 "$URL" 2>/dev/null || true)

    if printf '%s' "$response" | grep -q '"status":"ok"'; then
        echo "Healthcheck aprovado: $URL"
        exit 0
    fi

    sleep "$INTERVAL"
    attempt=$((attempt + 1))
done

echo "Healthcheck falhou após $ATTEMPTS tentativas: $URL" >&2
exit 1
