<?php

require_once 'config.php';
require_once 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;

class GameServer implements \Ratchet\MessageComponentInterface {
  protected $clients;
  protected $gameState;

  public function __construct() {
    $this->clients = new \SplObjectStorage();
    $this->gameState = array_fill(0, 9, '');
  }

  public function onOpen(ConnectionInterface $conn) {
    $this->clients->attach($conn);
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $this->gameState = json_decode($msg, true);
    $this->broadcastGameState();
    echo 'Received game update:', $msg, PHP_EOL;
  }

  public function onClose(ConnectionInterface $conn) {
    $this->clients->detach($conn);
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    $conn->close();
  }

  protected function broadcastGameState() {
    $gameStateJson = json_encode($this->gameState);
    foreach ($this->clients as $client) {
      $client->send($gameStateJson);
    }
  }
}

$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new GameServer()
    )
  ),
  8080
);

$server->run();
