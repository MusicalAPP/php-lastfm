<?php

require_once('LastFm.php');

$params = array(
  'api_key'       => 'b25b959554ed76058ac220b7b2e0a026',
  'method'        => 'artist.getInfo',
  'artist'        => 'Cher',
  'cacheEnabled'  => true,
  'cacheDir'      => 'cache/'
);

$lfm = new LastFm($params);

var_dump($lfm->request());

?>
