#!/bin/bash

if [ $# -gt 0 ]; then
    if [ "$1" == "php" ]; then
        shift 1
        docker-compose exec -T php php "$@"
        docker-compose exec -T php chown -R $(id -u):$(id -g) .
    elif [ "$1" == "composer" ]; then
        shift 1
        docker-compose exec -T php composer "$@"
        docker-compose exec -T php chown -R $(id -u):$(id -g) .
    elif [ "$1" == "fix" ]; then
        shift 1
        docker-compose exec -T php php -d memory_limit=-1 ./bin/phpcbf.phar
    elif [ "$1" == "metrics" ]; then
        shift 1
        docker-compose exec -T php php -d memory_limit=-1 ./vendor/bin/phpmetrics "$@" --config=phpmetrics.json --quiet
    elif [ "$1" == "qa" ]; then
        shift 1
        docker-compose exec -T php php -d memory_limit=-1 ./bin/phpcs.phar --standard=phpcs.xml
        docker-compose exec -T php php -d memory_limit=-1 ./bin/phpcpd.phar --fuzzy src/ config/
        docker-compose exec -T php php -d memory_limit=-1 ./bin/psalm.phar
        docker-compose exec -T php php -d memory_limit=-1 ./bin/deptrac.phar analyse
    elif [ "$1" == "test" ]; then
        shift 1
        docker-compose exec -T php php -d memory_limit=-1 ./bin/phpunit "$@"
    else
        echo "Unknown command"
    fi
else
    echo "Unknown command"
fi