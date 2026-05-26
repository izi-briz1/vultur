#!/bin/sh
set -e

composer install --no-interaction --optimize-autoloader
composer build-css

exec "$@"
