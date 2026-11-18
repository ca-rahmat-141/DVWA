#!/bin/sh
set -eu

# Refuse to serve a configuration that bypasses authentication unless the
# operator explicitly opts in to the isolated training deployment.
status=0
php -r 'require "/var/www/html/config/config.inc.php"; exit(!empty($_DVWA["disable_authentication"]) ? 42 : 0);' || status=$?
case "$status" in
    0)
        ;;
    42)
        if [ "${DVWA_UNAUTHENTICATED_TRAINING:-}" != "true" ] || [ "${DVWA_ISOLATED_NETWORK:-}" != "true" ]; then
            echo >&2 "Refusing to start: disable_authentication requires DVWA_UNAUTHENTICATED_TRAINING=true and DVWA_ISOLATED_NETWORK=true. Use the isolated training Compose configuration only."
            exit 1
        fi
        if awk 'NR > 1 && $2 == "00000000" { found = 1 } END { exit !found }' /proc/net/route; then
            echo >&2 "Refusing to start: unauthenticated training mode must not have a default network route."
            exit 1
        fi
        ;;
    *)
        echo >&2 "Refusing to start: could not validate DVWA authentication configuration."
        exit "$status"
        ;;
esac

exec /usr/local/bin/docker-php-entrypoint "$@"
