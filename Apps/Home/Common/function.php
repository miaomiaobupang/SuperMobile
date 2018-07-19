<?php
	function http_down($url,$filename,$timeout =60) {
		$path = dirname($filename);
		if (!is_dir($path) && !mkdir($path,0755, true)) {
			return false;
		}
		$fp = fopen($filename,'w');
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		return $path ;
	}

	function download($url,$path){
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	  $file = curl_exec($ch);
	  curl_close($ch);
	  $filename = pathinfo($url, PATHINFO_BASENAME);
	  $resource = fopen($path . $filename, 'a');
	  fwrite($resource, $file);
	  fclose($resource);
	}
?>