#!/bin/sh
set -e

composer install --no-interaction --optimize-autoloader
composer build-css

chown -R www-data:www-data /var/www/html/runtime

exec "$@"
