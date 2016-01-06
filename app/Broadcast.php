<?php
namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Broadcast implements MessageComponentInterface {
	protected $clients;

	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		echo "new connection! ({$conn->resourceId}) \n";
	}

	public function onMessage(ConnectionInterface $from, $msg) {
		foreach ($this->clients as $client) {
			if ($from !== $client) {
				$client->send($msg);
			}
		}
	}
	
	public function onClose(ConnectionInterface $conn){
		$this->clients->detach($conn);
		
		echo "Connection closed \n";
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "an error has occured: {$e->getMessage()} \n";
		
		$conn->close();
	}
}
