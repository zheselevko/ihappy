<?php
/*
* модуль подменяет статические мета данные
*/

if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

class SeoShieldModule_replace_tag extends seoShieldModule
{
	function html_out_buffer($out_html)
	{
		if(!empty($GLOBALS['SEOSHIELD_CONFIG']['replace_tag'])){
			foreach($GLOBALS['SEOSHIELD_CONFIG']['replace_tag'] as $tag){
				$out_html=str_replace("<".$tag.">","<span class=\"stag".$tag."\">",$out_html);
    			$out_html=preg_replace("#<(".$tag." )(((.(?!class))*?)(class *= *[\"']([^\"']*)[\"'])?)?([^>]*)>#is","<span \\3 \\7 class=\"\\6 stag".$tag."\">",$out_html);
				$out_html=str_replace("</".$tag.">","</span>",$out_html);
			}
		}
		
		return $out_html;
	}
}