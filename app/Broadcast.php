<?php
namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Broadcast implements MessageComponentInterface {
	protected $clients;
    protected $background_items = "{}";
    protected $foreground_items = "{}";
    protected $actors = "{}";

	public function __construct() {
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		echo "new connection! ({$conn->resourceId}) \n";
        echo $this->background_items;
        $conn->send($this->background_items);
        $conn->send($this->foreground_items);
        $conn->send($this->actors);
	}

	public function onMessage(ConnectionInterface $from, $msg) {
		foreach ($this->clients as $client) {
			if ($from !== $client) {
				$client->send($msg);
			}
		}
        //if it's a canvas layer, cache it.
        $passed_obj = json_decode($msg);
        if ($passed_obj[0]->obj_type == 'box'){
            $this->background_items = $msg;
        }
        if ($passed_obj[0]->obj_type == 'line'){
            $this->foreground_items = $msg;
        }
        if ($passed_obj[0]->obj_type == 'actor'){
            $this->actors = $msg;
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
