<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'redis' => [
                    'host' => $_ENV['REDIS_HOST'],
                    'port' => (int) $_ENV['REDIS_PORT'],
                ],
                'mariadb' => [
                    'host'    => $_ENV['MARIADB_HOST'],
                    'port'    => $_ENV['MARIADB_PORT'],
                    'dbname'  => $_ENV['MARIADB_DBNAME'],
                    'charset' => $_ENV['MARIADB_CHARSET'],
                    'user'    => $_ENV['MARIADB_USER'],
                    'pass'    => $_ENV['MARIADB_PASS'],
                ],
                'mariadb_read' => [
                    'host'    => $_ENV['MARIADB_READ_HOST'],
                    'port'    => $_ENV['MARIADB_READ_PORT'],
                    'dbname'  => $_ENV['MARIADB_READ_DBNAME'],
                    'charset' => $_ENV['MARIADB_READ_CHARSET'],
                    'user'    => $_ENV['MARIADB_READ_USER'],
                    'pass'    => $_ENV['MARIADB_READ_PASS'],
                ],
            ]);
        }
    ]);
};
