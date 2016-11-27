<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Ratchetsv 
 *
 * A CodeIgniter library for a simple cache system using Ratchetsv.
 *
 * @package             CodeIgniter
 * @category            Libraries
 * @author              TPT-KALI
*/

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class RatchetSV implements MessageComponentInterface {
    
    protected $clients;
    protected $store;
    protected $CI;

    public function __construct() {
        
        $this->CI = &get_instance();

        $this->CI->load->library('RatchetStore');

        $this->store = new $this->CI->ratchetstore();

        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later

        $query = $conn->WebSocket->request->getQuery()->toArray();

        print_r($query);
        if(isset($query['user'])){
            $this->store->attach($conn,$query['user']);
        }
        
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $query = $from->WebSocket->request->getQuery()->toArray();
        print_r($query);

        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $clientSend = $this->store->getClientWithInfo($query['user']);    
        $clientSend->send($msg);
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                //$client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function abcsss (){
       return print 'testing';
    }
}