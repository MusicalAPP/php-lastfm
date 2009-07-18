<?php

/**
*  classname:  Storage
*  scope:    PUBLIC
*
* @package Storage
* @author Galuh Utama <galuh.utama@gwutama.de>
* @version 
*/


class Storage
{

	public static function write($file, $data)
	{
		$fh = fopen($file, 'w+');
		fwrite($fh, $data);
		fclose($fh);
	}

  
	public static function read($file)
	{
  	$fh = fopen($file, 'r');
		$data = fread($fh, filesize($file));
		fclose($fh);
      
		return $data;
	}


  public static function delete($file)
  {
    if(!file_exists($file))
    {
      throw new exception('File doesn\'t exist.');
    }
    else
    {
      @unlink($file);
    }
  }

}
  

?>
