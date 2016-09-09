<?php
if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

include_once(SEOSHIELD_ROOT_PATH."/core/module.php");
include_once(SEOSHIELD_ROOT_PATH."/configs/main.php");

// загружаем остальные конфиги
foreach(glob(SEOSHIELD_ROOT_PATH."/configs/*.php") AS $config_file)
{
	if(basename($config_file)=="main.php")continue;
	include_once($config_file);
}

// метод который выполняется перед запуском CMS
function seo_shield_start_cms()
{
    list($usec,$sec)=explode(" ",microtime());
	$GLOBALS['SEOSHIELD_CMS_START_TIME']=(float)$usec+(float)$sec;

	seo_shield_load_modules();

	foreach($GLOBALS['SEOSHIELD_MODULES'] AS $module_name=>$module)
	{
        if(method_exists($module,"start_cms"))
            $module->start_cms();
	}
}

// метод который должен пропускать через себя итоговый HTML
function seo_shield_out_buffer($out_html)
{
	if(!isset($GLOBALS['SEOSHIELD_MODULES'])){
		seo_shield_load_modules();
	}

	foreach($GLOBALS['SEOSHIELD_MODULES'] AS $module_name=>$module)
	{
        if(method_exists($module,"html_out_buffer"))
		    $out_html=$module->html_out_buffer($out_html);
	}

	return $out_html;
}

// метод загружает модули
function seo_shield_load_modules()
{
	// подключаемся к базе
	if(isset($GLOBALS['SEOSHIELD_CONFIG']['mysql'])
	&& !empty($GLOBALS['SEOSHIELD_CONFIG']['mysql']['db'])){
		$GLOBALS['SEOSHIELD_CONFIG']['mysql']['link']=mysql_connect($GLOBALS['SEOSHIELD_CONFIG']['mysql']['host'],$GLOBALS['SEOSHIELD_CONFIG']['mysql']['user'],$GLOBALS['SEOSHIELD_CONFIG']['mysql']['password'],true);
		mysql_select_db($GLOBALS['SEOSHIELD_CONFIG']['mysql']['db'],$GLOBALS['SEOSHIELD_CONFIG']['mysql']['link']);
		mysql_set_charset(str_replace("utf-8","utf8",$GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']));
	}

	$GLOBALS['SEOSHIELD_MODULES']=array();

	$module_files=array();
	foreach(glob(SEOSHIELD_ROOT_PATH."/core/modules/*.php") AS $module_file)
	{
		$module_files[substr(basename($module_file),0,-4)]=$module_file;
	}

	$module_files_sorted=array();
	foreach($GLOBALS['SEOSHIELD_CONFIG']['modules_ordering'] AS $module_name)
	{
		if(!isset($module_files[$module_name]))continue;

		$module_files_sorted[$module_name]=$module_files[$module_name];
		unset($module_files[$module_name]);
	}
	$module_files_sorted=array_merge($module_files_sorted,$module_files);

	foreach($module_files_sorted AS $module_file)
	{
		if(is_array($GLOBALS['SEOSHIELD_CONFIG']['disabled_modules'])
			&& sizeof($GLOBALS['SEOSHIELD_CONFIG']['disabled_modules'])>0
			&& in_array(substr(preg_replace("#^[0-9]+\.#","",basename($module_file)),0,-4),$GLOBALS['SEOSHIELD_CONFIG']['disabled_modules']))continue;

		include_once($module_file);

		$module_file_name=basename($module_file);
		if(is_numeric(substr($module_file_name,0,1))){
			$module_file_name=preg_replace("#^[0-9]+\.#is","",$module_file_name);
		}

		if(substr($module_file_name,0,1)=="!")continue;

		$module_name=rtrim($module_file_name,".php");

		if(!class_exists("SeoShieldModule_".$module_name))continue;

		$module_config_file=SEOSHIELD_ROOT_PATH."/modules/".basename($module_file_name);
		
		if(file_exists($module_config_file)){
			include_once($module_config_file);

			$module_class_name="SeoShieldModule_".$module_name."_config";
		}else{
			$module_class_name="SeoShieldModule_".$module_name;
		}

		$GLOBALS['SEOSHIELD_MODULES'][$module_name]=new $module_class_name();
	}
}