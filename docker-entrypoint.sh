#!/bin/sh
set -eu

config=/var/www/html/config/config.inc.php
if [ ! -r "$config" ]; then
    echo "DVWA startup refused: configuration file is missing or unreadable." >&2
    exit 1
fi

if ! authentication_disabled=$(DVWA_CONFIG_PATH="$config" php -r '$_DVWA = []; $DBMS = ""; require getenv("DVWA_CONFIG_PATH"); echo !empty($_DVWA["disable_authentication"]) ? "1" : "0";'); then
    echo "DVWA startup refused: unable to validate authentication configuration." >&2
    exit 1
fi

case "$authentication_disabled" in
    0)
        ;;
    1)
        if [ "${DVWA_UNAUTHENTICATED_TRAINING:-}" != "isolated" ]; then
            echo "DVWA startup refused: disable_authentication=true requires DVWA_UNAUTHENTICATED_TRAINING=isolated and an isolated, non-published network." >&2
            exit 1
        fi
        ;;
    *)
        echo "DVWA startup refused: unexpected authentication configuration value." >&2
        exit 1
        ;;
esac

exec /usr/local/bin/docker-php-entrypoint "$@"
