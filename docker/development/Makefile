#Указываем конфигурацию для docker-compose
#Можно использовать наследование через символ ":", последний файл важнее
export COMPOSE_FILE=./docker-compose.yml

##
# Запустить/пересоздать контейнеров из образов
# 
# Пример: make up
up:
	docker-compose up -d --no-build --force-recreate --remove-orphans

##
# Останавить и удалить контейнеры проекта
#
# Пример: make down
##
down:
	docker-compose down -v

##
# Запустить bash внутри контейнера
#
# Пример: make bash-php
##
bash-php:
	docker-compose exec -u www-data php bash
bash-php-root:
	docker-compose exec php bash
bash-%:
	docker-compose exec $* bash

##
# Посмотреть логи сервиса в фоллоу режиме
#
# Пример: make log-php или make log-nginx
##
log-%:
	docker-compose logs -f $*

##
# Перезапустить сервис
#
# Пример: make restart-php
##
restart-%:
	docker-compose restart $*

phpcs:
	docker-compose exec -u www-data php vendor/bin/phpcs --standard=phpcs.xml --colors ./src/
	
phpcbf:
	docker-compose exec -u www-data php vendor/bin/phpcbf --standard=phpcs.xml --colors ./src/
