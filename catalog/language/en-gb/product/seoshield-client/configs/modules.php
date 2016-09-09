<?php
// модули которые необходимо отключить
$GLOBALS['SEOSHIELD_CONFIG']['disabled_modules']=array(
	"add_to_ititle",
	"add_to_noindex",
	"generate_content",
	 "genereate_meta",
	"links_block",
//	"redirect_301",
	"replace_tag",
	"run_404",
//	"static_meta"
);

// позволяем задать порядок загрузки модулей (надо указать имя модулей, без .php)
$GLOBALS['SEOSHIELD_CONFIG']['modules_ordering']=array(
	"redirect_301",
//	"genereate_meta",
	"static_meta",

);