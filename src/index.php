<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => ['Content-type: application/json', 'X-API-Key: 123abc'],
            'content' => json_encode([
                'orgNo' => '1111222233',
                'email' => 'dude@example.com'
            ])
        ]
    ]);

    $newResponse = $response->withBody(new \Slim\Psr7\Stream(fopen('http://localhost:8000/api/export', 'rb', false, $context)));
    return $newResponse->withHeader('Content-Type', 'application/json');
});

$app->run();
