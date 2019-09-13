<?php

require_once 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$server = new React\Http\Server(function (Psr\Http\Message\ServerRequestInterface $request) {
    echo $request->getUri() . "\n";

    return new React\Http\Response(
        200,
        array('Content-Type' => 'text/plain'),
        "Hello World!\n"
    );
});

$socket = new React\Socket\Server(9999, $loop);
$server->listen($socket);

echo "Server running at http://127.0.0.1:9999\n";

$loop->run();
