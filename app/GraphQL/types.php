<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use React\HttpClient\Response;
use React\Promise\Promise;

/** @var React\HttpClient\Client $httpClient */
$httpClient = $client;

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echo' => [
            'type' => Type::string(),
            'args' => [
                'message' => Type::nonNull(Type::string()),
            ],
            'resolve' => function ($root, $args) {
                return new Promise(function ($resolve, $reject) use ($root, $args) {
                    $resolve($root['prefix'] . $args['message']);
                });
            }
        ],
        'request_1' => [
            'type' => Type::string(),
            'args' => [],
            'resolve' => function ($root, $args) {
                $startTime = new DateTime();
                file_get_contents('https://postman-echo.com/get?foo1=bar1&foo2=bar2');
                $endTime = new DateTime();
                return "request_1: Start Time: {$startTime->format('Y-m-d H:i:s')} ----> End Time: {$endTime->format('Y-m-d H:i:s')}";
            }
        ],
        'request_2' => [
            'type' => Type::string(),
            'args' => [],
            'resolve' => function ($root, $args) {
                $startTime = new DateTime();
                file_get_contents('https://postman-echo.com/get?foo1=bar1&foo2=bar2');
                $endTime = new DateTime();
                return "request_2: Start Time: {$startTime->format('Y-m-d H:i:s')} ----> End Time: {$endTime->format('Y-m-d H:i:s')}";
            }
        ],
        'async_request_1' => [
            'type' => Type::string(),
            'args' => [],
            'resolve' => function ($root, $args) use ($httpClient) {
                return new Promise(function ($resolve, $reject) use ($httpClient) {
                    $startTime = new DateTime();
                    $request = $httpClient->request('GET', 'https://postman-echo.com/get?foo1=bar1&foo2=bar2');
                    $request->on('response', function (Response $response) use ($resolve, $startTime) {
                        $response->on('end', function() use ($resolve, $startTime) {
                            $endTime = new DateTime();
                            $resolve(
                                "async_request_1: Start Time: {$startTime->format('Y-m-d H:i:s')} ----> End Time: {$endTime->format('Y-m-d H:i:s')}"
                            );
                        });
                    });
                    $request->on('error', function (Exception $e)  use ($resolve) {
                        $resolve($e);
                    });
                    $request->end();
                });
            }
        ],
        'async_request_2' => [
            'type' => Type::string(),
            'args' => [],
            'resolve' => function ($root, $args) use ($httpClient)  {
                return new Promise(function ($resolve, $reject) use ($httpClient) {
                    $startTime = new DateTime();
                    $request = $httpClient->request('GET', 'https://postman-echo.com/get?foo1=bar1&foo2=bar2');
                    $request->on('response', function (Response $response) use ($resolve, $startTime) {
                        $response->on('end', function() use ($resolve, $startTime) {
                            $endTime = new DateTime();
                            $resolve(
                                "async_request_2: Start Time: {$startTime->format('Y-m-d H:i:s')} ----> End Time: {$endTime->format('Y-m-d H:i:s')}"
                            );
                        });
                    });
                    $request->on('error', function (Exception $e)  use ($resolve) {
                        $resolve($e);
                    });
                    $request->end();
                });
            }
        ],
    ],
]);