<?php

class image
{

	public function __construct()
	{
		require_once NEMESIS_PROCESS_PATH.'lib/ImageGD.php';
	}

	public function create ($arguments=array())
	{
		extract($arguments);

		if (file_exists($destination))
			@unlink($destination);

		$obj = new ImageGD($source);
		$obj->open();

		if (!isset($option))
			$option = '';

		if (is_array($option))
		{
			$obj->cropStartX = (isset($option['cropStartX']))? $option['cropStartX']:null;
			$obj->cropStartY = (isset($option['cropStartY']))? $option['cropStartY']:null;
		}
		$obj->resize($width, $height, ((!is_array($option))? $option:'crop'));

		if (file_exists($destination))
			@unlink($destination);

		$obj->save($destination);

		return $destination;
	}

	public function display($file){
		$s = getimagesize($file);
		if(! ($s && $s['mime'])){
			return false;
		}
		header ('Content-Type: ' . $s['mime']);
		header ('Content-Length: ' . filesize($file) );
		header ('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header ("Pragma: no-cache");
		$bytes = @readfile($file);
		if($bytes > 0){
			return true;
		}
		$content = @file_get_contents ($file);
		if ($content != FALSE){
			echo $content;
			return true;
		}
		return false;

	}

}
