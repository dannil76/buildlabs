<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;

Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->load();

$app = AppFactory::create();

$exportExcelHandler = function (Request $request, Response $response): Response {
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => [
                'content-type: application/json',
                'x-api-key:' . $_ENV['API_KEY'],
            ],
            'content' => json_encode([
                'orgNo' => '1111222233',
                'email' => 'dude@example.com',
            ]),
        ],
    ]);

    $responseWithBody = $response->withBody(new Stream(fopen("{$_ENV['API_URL']}/export/excel",
        'rb',
        false, $context)));

    return $responseWithBody->withHeader('Content-Type', 'application/octet-stream');
};

$indexHandler = function (Request $request, Response $response): Response {
    $response->getBody()->write('Hello, World!');
    return $response->withHeader('Content-Type', 'text/plain');
};

$app->get('/', $indexHandler);

$app->get('/projection', $exportExcelHandler);

$app->run();
