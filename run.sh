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
    else
        echo "Unknown command"
    fi
else
    echo "Unknown command"
fi