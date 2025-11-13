<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response) {

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => [
                'content-type: application/json',
                'x-api-key:' . $_ENV['API_KEY']
            ],
            'content' => json_encode([
                'orgNo' => '1111222233',
                'email' => 'dude@example.com'
            ])
        ]
    ]);

    $newResponse = $response->withBody(new \Slim\Psr7\Stream(fopen($_ENV['API_URL'], 'rb', false, $context)));
    return $newResponse->withHeader('Content-Type', 'application/octet-stream');
});

$app->run();
