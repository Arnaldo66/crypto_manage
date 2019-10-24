<?php

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'],
        __DIR__
    ));
    // echo 'Database drop' . PHP_EOL;
    // passthru(sprintf(
    //     'php "%s/../bin/console" doctrine:database:drop --env=%s --if-exists --force',
    //     __DIR__,
    //     $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    // ));
    // echo 'Database create' . PHP_EOL;
    // passthru(sprintf(
    //     'php "%s/../bin/console" doctrine:database:create --env=%s',
    //     __DIR__,
    //     $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    // ));
    // echo 'Database update' . PHP_EOL;
    // passthru(sprintf(
    //     'php "%s/../bin/console" doctrine:schema:update --env=%s --force',
    //     __DIR__,
    //     $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    // ));
}

require __DIR__.'/../config/bootstrap.php';
