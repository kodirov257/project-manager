<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

http_response_code(500);

(function () {
    $app = AppFactory::create();
    $app->addRoutingMiddleware();

    $isDebug = (bool)getenv('APP_DEBUG');
    $app->addErrorMiddleware($isDebug, $isDebug, $isDebug);

    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write(json_encode([
            'name' => 'Manager',
            'param' => $request->getQueryParams(),
        ]));

        return $response;
    });

    $app->run();
})();