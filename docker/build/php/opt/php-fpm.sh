#!/usr/bin/env bash

# Здесь вместо `php -v`` можно описать консольные команды
# Они будут выполнены после деплоя перед стартом php-fpm
/opt/wait-for-it.sh -s -t 0 ${MYSQL_HOST}:${MYSQL_PORT} -- \
sudo -EH -u www-data bash -c " \
	composer install
	vendor/bin/doctrine orm:schema-tool:update -f
" && \
php-fpm