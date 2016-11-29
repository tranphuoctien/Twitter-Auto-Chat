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
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $query = $conn->WebSocket->request->getQuery()->toArray();
        $this->store->attach($conn,isset($query['user'])?$query['user']:null);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        $query = $from->WebSocket->request->getQuery()->toArray();
        
        $clientSend = $this->store->getClientWithInfo();    
        
        print_r($query);

        $numRecv = count($clientSend) - 1;

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($clientSend as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->store->detach($conn);        
        $query = $conn->WebSocket->request->getQuery()->toArray();
        print_r($query);
        if(isset($query['user'])){
            $this->store->detachObject($query['user']);
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}