<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        Redis::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $redisSettings = $settings->get('redis');

            $redis = new Redis();
            $redis->connect($redisSettings['host'], $redisSettings['port']);
            return $redis;
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $mariadbSettings = $settings->get('mariadb');

            //PDO設定
            $host    = $mariadbSettings['host'];
            $port    = $mariadbSettings['port'];
            $dbname  = $mariadbSettings['dbname'];
            $charset = $mariadbSettings['charset'];
            $user    = $mariadbSettings['user'];
            $pass    = $mariadbSettings['pass'];
            $option = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            );
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s;', $host, $port, $dbname, $charset);

            $pdo = new \PDO($dsn, $user, $pass, $option);
            return $pdo;
        },
    ]);
};
