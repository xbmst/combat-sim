<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    new Dotenv()->bootEnv(dirname(__DIR__).'/.env');
}

if ($_ENV['APP_ENV'] === 'test') {
    passthru(sprintf(
        'php "%s/../bin/console" doctrine:database:create --env=test --if-not-exists --no-interaction',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:schema:update --force --env=test',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:fixtures:load --env=test --no-interaction',
        __DIR__
    ));
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
