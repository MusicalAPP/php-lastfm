<?php

/**
*  classname:  Flatcache
*  scope:    PUBLIC
*
* @package Flatcache
* @author Galuh Utama <galuh.utama@gwutama.de>
* @version 
*/

require_once('Storage.php');


class Flatcache  {

  //you can set default values here
  private $options = array(
                          'cacheDir'  => 'cache/', //must be writable
                          'timeout'   => 3600 //in ms
                        );
  

  public function __construct($options)  {
    //cacheId must be suppled
    if(!$options['cacheId']) {
      throw new Exception('Cache ID must be defined.');
    }
  
    //set options
    foreach($options as $key => $val) {
      $this->options[$key] = $val;
    }
  }  


  public function write($data) {
    //write $data and timestamp
    $now = time();
    //convert to json
    $foo = array(
      'timestamp' => $now,
      'data' => $data
    );
    
    Storage::write(self::CACHEDIR . md5($cacheId), json_encode($foo));
  }
  
  
  public static function read() {
    //cached file exists?
    $cache = $this->options['cacheDir'].md5($this->options['cacheId']);
    
    if(!file_exists($cache)) {
      throw new Exception('Cache file not exists. Supplied cache ID correct?');
    }
  
    $now = time();
    $timeout = $this->options['timeout'];
    
    //read timestamp
    $data = json_decode(Storage::read($cache), true);
    $timestamp = $data['timestamp'];

    if(($now - $timestamp) <= $timeout) {
      return $data['data'];
    }
    else { //cache expired 
      Storage::delete($cache);
      return null;
    }
  }


}

?>
