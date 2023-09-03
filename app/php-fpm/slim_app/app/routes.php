<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use SlopeIt\ClockMock\ClockMock;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $logger = $this->get(LoggerInterface::class);
        $logger->debug('Hello world!');
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->get('/testredis', function (Request $request, Response $response) {
        $key = 'key_dummy';
        $redis = $this->get(Redis::class);
        if (!$redis->exists($key)) {
            $redis->set($key, mt_rand(), ['ex'=>10]);
        }
        $value = $redis->get($key);
        $response->getBody()->write($value);
        return $response;
    });
    $app->get('/testmariadb', function (Request $request, Response $response) {
        $pdo = $this->get(PDO::class);
        $stmt = $pdo->query('SELECT * from `sample`');
        $data = $stmt->fetchAll();
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response;
    });
    $app->get('/testmariadb_read', function (Request $request, Response $response) {
        $pdo = $this->get('db_read');
        $stmt = $pdo->query('SELECT * from `sample`');
        $data = $stmt->fetchAll();
        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response;
    });
    $app->get('/testtime', function (Request $request, Response $response) {
        $data = [];

        $now = new Datetime();
        $data['01'] = $now->format('Y-m-d H:i:s.u');

        ClockMock::freeze(new \DateTime('2000-01-01 12:00:00'));
        $now = new Datetime();
        $data['02'] = $now->format('Y-m-d H:i:s.u');

        ClockMock::reset();
        $now = new Datetime();
        $data['03'] = $now->format('Y-m-d H:i:s.u');

        $json = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write($json);
        return $response;
    });
};
