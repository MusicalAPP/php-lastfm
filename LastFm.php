<?php

/**
*  classname:  LastFm
*  scope:    PUBLIC
*
* A crap-free and non-bloated PHP last.fm API.
* Unlike Matt Oakes' last.fm API which is inefficient, slow
* and bloated with unnecessary things, this one is guaranteed
* fast and lightweight.
*
* Flat-file caching is also supported.
*
* @package LastFm
* @author Galuh Utama <galuh.utama@gwutama.de>
* @version 
* @license GPL v3
*/


require_once('Net/Curl.php'); //to be replaced with normal curl method

require_once('Flatcache.php');


class LastFm  {

  const BASEURL = 'http://ws.audioscrobbler.com/2.0/';

  //set default values for parameters here
  private $params = array(
                        'cacheEnabled'  =>  false,
                        'cacheDir'      =>  null,
                        'cacheTimeout'  =>  3600, //in ms
                        'format'        => 'json'
                      );
  private $result;
  private $flatcache = null;


  public function __construct($params) {
    //at least method must be defined
    if(!$params['method']) {
      throw new Exception('Method must be defined.');
    }

    //cache enabled? then cacheDir must exist
    if($params['cacheEnabled']) {
      if(!dir_exists($params['cacheDir'])) {
        throw new Exception('Cache directory not available');
      }

      //setup flatcache
      $opts = array(
                 'cacheId'  => $this->concatParams(), 
                 'timeout'  => $params['cacheTimeout'],
                 'cacheDir' => $params['cacheDir']
               );
      $this->flatcache = new Flatcache($opts);
    }
    

    $this->setParams($params);
  }


  public function setParams($array){
    foreach($array as $key => $val) {
      $this->params[$key] = $val;
    }
  }


  private function concatParams() {
    //exclude cache options
    foreach($this->params as $key => $value) {
      if(!preg_match('/cache/', $key)) {
        $params[$key] = $value;
      }
    }

    $str = '?';
    foreach($params as $key => $val) {
      $str .= $key.'='.rawurlencode($val).'&';
    }
    //remove last question mark
    return substr($str, 0, strlen($str) - 1);
  }
  

 	public function request() {
 	  $data = null;
 	
 	  //check whether caching is enabled or not. If enabled, then read/write cache
 	  if($this->flatcache) {
 	    $data = $this->flatcache->read();
 	  } 	  
 	  elseif(!$data) {
	   	$userAgent = 'gwutama last.fm PHP API';
		  $curl = new Net_Curl(self::BASEURL . $this->concatParams(), $userAgent);
		  $curl->followLocation = true;
		  $curl->returnTransfer = true;
		  $curl->setOption('CURLOPT_REFERER', $URL);
		  $result = $curl->execute();
		  if (!PEAR::isError($result)) {
		    //if caching enabled then write cache
		    if($this->flatcache) {
		      $this->flatcache->write($result);
		    }
		    
			  return $result; //return it
		  }
		}
		else {}
		
 	}


}

?>
