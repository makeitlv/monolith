#!/bin/bash

if [ $# -gt 0 ]; then
    if [ "$1" == "php" ]; then
        shift 1
        docker-compose exec php php "$@"
        docker-compose exec php chown -R $(id -u):$(id -g) .
    elif [ "$1" == "composer" ]; then
        shift 1
        docker-compose exec php composer "$@"
        docker-compose exec php chown -R $(id -u):$(id -g) .
    elif [ "$1" == "qa" ]; then
        shift 1
        docker-compose exec php php -d memory_limit=-1 ./bin/phpcs.phar --standard=phpcs.xml
        docker-compose exec php php -d memory_limit=-1 ./bin/phpcpd.phar --fuzzy src/ config/
        docker-compose exec php php -d memory_limit=-1 ./bin/psalm.phar
        docker-compose exec php php -d memory_limit=-1 ./bin/deptrac.phar analyse
    elif [ "$1" == "test" ]; then
        shift 1
        docker-compose exec php php -d memory_limit=-1 bin/console cache:clear --env=test
        docker-compose exec php php -d memory_limit=-1 bin/console doctrine:database:create --env=test --if-not-exists
        docker-compose exec php php -d memory_limit=-1 bin/console doctrine:schema:drop --env=test --force
        docker-compose exec php php -d memory_limit=-1 bin/console doctrine:schema:create --env=test
        docker-compose exec php php -d memory_limit=-1 ./bin/phpunit "$@"
    else
        echo "Unknown command"
    fi
else
    echo "Unknown command"
fi