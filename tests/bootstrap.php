<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

passthru(sprintf(
    'php "%s/../bin/console" cache:clear --env=%s',
    __DIR__,
    $_ENV['APP_ENV'],
));

passthru(sprintf(
    'php "%s/../bin/console" doctrine:database:create --env=%s --if-not-exists',
    __DIR__,
    $_ENV['APP_ENV'],
));

passthru(sprintf(
    'php "%s/../bin/console" doctrine:schema:drop --env=%s --force',
    __DIR__,
    $_ENV['APP_ENV'],
));

passthru(sprintf(
    'php "%s/../bin/console" doctrine:schema:create --env=%s',
    __DIR__,
    $_ENV['APP_ENV'],
));
