<?php
/**
 * уникальный ключ этого сайта
 */
$GLOBALS['SEOSHIELD_CONFIG']['access_key'] = '57d4bbf9c1d113ce01b6bbbe962bd3fd0755ef8febfa5b7e1d659d19a1d643c3e05eb76abba562e4f530bfd1411a5a70';

/**
 * параметры выборки контентной области сайта (то место где размещается основной текст)
 * контетных областей может быть несколько, поэтому в массиве мы перечисляем типы и параметры выборки
 */
$GLOBALS['SEOSHIELD_CONFIG']['content_area_selector'] = array(
    array(
        'type' => 'regex',
        'pattern' => '#<!-- seo_text -->.*?<!-- /seo_text -->#is',
    ),
);

// настройки для подключения к базе mysql
// $GLOBALS['SEOSHIELD_CONFIG']['mysql']=array(
// 	"host"=>"ilion2.mysql.ukraine.com.ua", // хост для подключения к mysql
// 	"db"=>"ilion2_mramor", // имя базы mysql
// 	"user"=>"ilion2_mramor", // пользователь mysql
// 	"password"=>"nnzn4ela" // пароль
// );


// настройки для подключения к базе mysql
$GLOBALS['SEOSHIELD_CONFIG']['mysql']=array(
	"host"=>"ilion2.mysql.ukraine.com.ua", // хост для подключения к mysql
	"db"=>"ilion2_mramor2", // имя базы mysql
	"user"=>"ilion2_mramor2", // пользователь mysql
	"password"=>"9vjmdtgw" // пароль
);



// URL текущей страницы (можно оставить как есть, если нет проблем)
$GLOBALS['SEOSHIELD_CONFIG']['page_uri']=$_SERVER['REQUEST_URI'];

// кодировка контента сайта по умолчанию
$GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']="utf-8";
