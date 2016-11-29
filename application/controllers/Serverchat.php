<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use MessagePack\Packer;
class Serverchat extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('RunserverRC');		
	}

	public function index(){
		$this->load->view('welcome_message');
	}

	public function runserver(){
		 $this->runserverrc->runIt();
	}

	public function testRedis(){
		echo "Test redis \n\r";

		$client = new Predis\Client();

		$client->executeRaw(array(
			'LPUSH','FUCKA','THIS IS VALUE FUCK'
		));
		
		$client->executeRaw(array(
			'LPUSH','FUCKA','THIS IS VALUE FUCK22222'
		));

		//$sjhshj = $client->get('FUCKA');

		/*$client->executeRaw(array(
			'del','FUCKA'
		));*/

		$rowww = $client->executeRaw(array(
				'LRANGE', 'FUCKA',0,1
		));

		//$client->set('foo', array('bar','TPT'));
		
		print_r($rowww);

		$packer = new Packer();

		$mpMap3 = $packer->pack(['foo' => 1, 'bar' => 2]);

		print_r($mpMap3);

	}


}
