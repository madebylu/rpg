<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Broadcast;

    require dirname(__DIR__) . '/public_html/vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
		new WsServer(
			new Broadcast()
		)
	),
        8080
    );

    $server->run();
