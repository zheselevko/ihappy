<?php
/*
* отвечает за 301 редиректы
*/
if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

class SeoShieldModule_redirect_301 extends seoShieldModule
{
	function start_cms()
	{
		$csv_file_name=SEOSHIELD_ROOT_PATH."/data/redirect_301.csv";
		if(file_exists($csv_file_name)
			&& is_readable($csv_file_name)){
			$redirect_data=$this->csv2array($csv_file_name);

			foreach($redirect_data AS $cols)
			{
				$url=preg_replace("#^https?://[^/]+#is","",trim($cols[0]));
				$url=current(explode('#',$url));
				if(substr($url,0,1)!="/")continue;

				if($url==$GLOBALS['SEOSHIELD_CONFIG']['page_uri'] && $cols[0]!=$cols[1]){
					header("HTTP/1.1 301 Moved Permanently");
					header("Location: ".$cols[1]);
					exit();
				}
			}
		}
	}
}