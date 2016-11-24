<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Redis Service  
 *
 * A CodeIgniter library for a simple cache system using Redis Service .
 *
 * @package             CodeIgniter
 * @category            Libraries
 * @author              TPT-KALI
*/

use MessagePack\Packer;
use MessagePack\Unpacker;
class RedisSV {
    
    protected $CI;
    protected $client;
    protected $value;
    protected $packer;
    protected $unpacker;
    public function __construct(){
        $this->CI = &get_instance();
        $this->client = new Predis\Client();
        $this->packer = new Packer();
        $this->unpacker = new Unpacker();
    }
    /*Method Set Value*/
    public function setValue($value){
        $this->value = $value;
    }
    /*Method Get Value*/
    public function getValue(){
        return $this->value;
    }
    /*Method pack data mgs*/
    public function packData(){
         $this->value = $this->packer->packMap($this->value);
    }

    /*Method unpack data mgs*/
    public function unpackData($value){
          return $this->value = $this->unpacker->unpack($value);   
    }
    /*Method redis set data type String*/
    public function storeDataString($key){
        $value = $this->packer->packStr($this->getValue());
        return $this->client->set("string:$key",$value);
    }

    /*Method get data type string*/
    public function getDataString($key){
        return $this->client->get("string:$key");
    }

    /*Method set data json(array)*/
    public function storeDataJson($key){
        $this->client->executeRaw(array(
			'LPUSH',"json:$key",$this->packData()
		));
    }
    /*Method get data json (array)*/
    public function getDataJson($key,$option=array(0,-1)){
        return $this->client->executeRaw(array(
            'LRANGE',"json:$key",$option[0],$option[1]
        ));             
    }

}
