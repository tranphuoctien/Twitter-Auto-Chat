<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Runserverrc 
 *
 * A CodeIgniter library for a simple cache system using ratchet.
 *
 * @package             CodeIgniter
 * @category            Libraries
 * @author              TPT-KALI
*/
/*RUN SERVER*/

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class RunserverRC {

    protected $server;
    protected $CI;

    public function __construct(){
        $this->CI = &get_instance(); 
        
    }
    // RUN IT
    public function runIt(){
        $this->CI->load->library('RatchetSV'); 
        $this->server = IoServer::factory(
                            new HttpServer(
                                new WsServer(
                                    new $this->CI->ratchetsv()
                                )
                            ),9090);

         print 'We runing server chat!';
         $this->server->run();
    }

    public function getServer(){
        return $this->server;
    }

}