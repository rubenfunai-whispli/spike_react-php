<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use React\Promise\Promise;

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
    ],
]);