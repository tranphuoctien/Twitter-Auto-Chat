<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Serverchat extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('RunserverRC');		
	}

	public function index(){
		$this->load->view('welcome_message');
	}

	public function runserver(){

		//$this->load->library('Ratchetsv');	
		/*$this->ratchetsv->abcsss();
        $this->server = IoServer::factory(
                            new HttpServer(
                                new WsServer(
                                    new $this->ratchetsv()
                                )
                            ),9090);
         print 'We runing server chat!';
         $this->server->run();*/
		 $this->runserverrc->runIt();

	}

	public function testRedis(){
		echo "Test redis \n\r";
		$client = new Predis\Client();

		$client->executeRaw(array(
			'LPUSH','FUCKA','THIS IS VALUE FUCK'
		));

		$rowww = $client->executeRaw(array(
				'MGET', 'FUCKA'
		));

		//$client->set('foo', array('bar','TPT'));
		

		print_r($rowww);

	}


}
