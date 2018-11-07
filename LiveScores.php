// src/LiveScores/Scores.php

<?php namespace LiveScores;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Scores implements MessageComponentInterface {

    private $clients;    

    public function __construct() 
    {    
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) 
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) 
    {            
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) 
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {     
        $conn->close();
    }
public function __construct() {

    // Create a collection of clients
    $this->clients = new \SplObjectStorage;

    $this->games = Fixtures::random();
}
public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);

    // New connection, send it the current set of matches
    $conn->send(json_encode(array('type' => 'init', 'games' => $this->games)));

    echo "New connection! ({$conn->resourceId})\n";
}

}
