#!/usr/bin/env php
<?php

/**
 * Run me!
 *
 * *nix: ./app.php
 * Win: php app.php
 */

use GraphQL\Executor\Promise\Adapter\ReactPromiseAdapter;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Executor\ExecutionResult;
use React\Promise\Promise;

// Composer
require_once 'vendor/autoload.php';

// Start the Event Loop
$loop = React\EventLoop\Factory::create();
$client = new React\HttpClient\Client($loop);

// Initialise GraphQL
require_once 'app/GraphQL/index.php';

$schema = new Schema([
    'query' => $queryType
]);

$server = new React\Http\Server(function (Psr\Http\Message\ServerRequestInterface $request) use ($schema, $queryType) {
    return new Promise(function ($resolve, $reject) use ($request, $schema, $queryType) {
        $input = json_decode(
            $request->getBody()->getContents(),
            true
        );
        $query = $input['query'];
        $variableValues = isset($input['variables']) ? $input['variables'] : null;

        $rootValue = ['prefix' => 'You said: '];

        $promise = GraphQL::promiseToExecute(
            new ReactPromiseAdapter(),
            $schema,
            $query,
            $rootValue,
            null,
            $variableValues,
            null,
            null,
            null
        );

        $promise->then(function(ExecutionResult $result) use ($resolve) {
            $resolve(
                new React\Http\Response(
                    200,
                    array('Content-Type' => 'application/json'),
                    json_encode($result->toArray())
                )
            );
        });
    });
});

$socket = new React\Socket\Server(9999, $loop);
$server->listen($socket);

echo "Server running at http://127.0.0.1:9999\n";

$loop->run();
