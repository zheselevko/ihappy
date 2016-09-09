<?php
/*
* модуль подменяет статические мета данные
*/

if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

class SeoShieldModule_links_block extends seoShieldModule
{
    var $links_block_csv = "";
	function html_out_buffer($out_html)
	{
		$this->links_block_csv = SEOSHIELD_ROOT_PATH."/data/links_block.csv";
		mysql_query("CREATE TABLE IF NOT EXISTS `links_block` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `url` tinytext NOT NULL,
		  `ancor` tinytext NOT NULL,
		  `shows` int(11) DEFAULT '0',
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `id` (`id`)
		)");
		
		mysql_query("CREATE TABLE IF NOT EXISTS `links_block_pages` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `url` tinytext NOT NULL,
		  `date_add` int(11) NOT NULL,
		  `selected_links` text NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `id` (`id`)
		)");

		if(file_exists($this->links_block_csv)
			&& is_readable($this->links_block_csv)){
			if($GLOBALS['SEOSHIELD_CONFIG']['links_block_csv_update_time']=="" || $GLOBALS['SEOSHIELD_CONFIG']['links_block_csv_update_time']<filemtime($this->links_block_csv)){
				$this->links_sync(); // синхронизируем ссылки из csv с базой
				$this->csv_time_rewrite(); // перезаписываем в конфиге дату изменения csv файла
			}
		}

		$links_block_template=$this->links_block(); 
		if(!empty($GLOBALS['SEOSHIELD_CONFIG']['html_comment_to_replace']) 
			&& !empty($links_block_template)){
			$out_html=str_replace($GLOBALS['SEOSHIELD_CONFIG']['html_comment_to_replace'],$links_block_template,$out_html);
		}

		return $out_html;
	}

	function links_block_template($selected_links)
	{
		$links_block_template='<div class="links_block"><ul>';
		foreach($selected_links as $i=>$s){
			$i++;
			$links_block_template.='<li class="links_block_link"><a href="'.$s['url'].'#'.$s['id'].'-'.$i.'">'.$s['ancor'].'</a></li>';
		}
		$links_block_template.="</ul></div>";

		return $links_block_template;
	}

	function links_block()
	{
		// выбираем id ссылок
		$selected_ids=mysql_query("SELECT selected_links FROM `links_block_pages` WHERE url='".$GLOBALS['SEOSHIELD_CONFIG']['page_uri']."'");
		
		if(mysql_num_rows($selected_ids)==0){
			if(empty($GLOBALS['SEOSHIELD_CONFIG']['links_count'])){
				$GLOBALS['SEOSHIELD_CONFIG']['links_count']="4";
			}
			$links_ids=mysql_query("SELECT id FROM `links_block` GROUP BY `url` ORDER BY `shows` ASC LIMIT  ".$GLOBALS['SEOSHIELD_CONFIG']['links_count']);
			$selected_ids=array();
			while($res=mysql_fetch_array($links_ids))
			{
				$selected_ids[]=$res['id'];
			}

			mysql_query("INSERT INTO `links_block_pages` (url,date_add,selected_links) VALUES ('".$GLOBALS['SEOSHIELD_CONFIG']['page_uri']."','".time()."','".implode(',',$selected_ids)."')");
			$selected_ids=implode(',',$selected_ids);
		}else{
			$selected_ids=mysql_fetch_array($selected_ids);
			$selected_ids=$selected_ids[0];
		}

		if(!empty($selected_ids)){

			mysql_query("UPDATE `links_block` SET `shows`=`shows`+1 WHERE id IN (".$selected_ids.")");
			$result=mysql_query("SELECT id,url,ancor FROM `links_block` WHERE id IN (".$selected_ids.")");
			$selected_links=array();
			while($row=mysql_fetch_array($result))
			{
				$selected_links[]=$row;
			}

			$links_block_template=$this->links_block_template($selected_links);

			return $links_block_template;
		}
	}

	function csv_time_rewrite()
	{
		$config_file=file_get_contents(SEOSHIELD_ROOT_PATH."/configs/links_block.php");
		$config_file=str_replace(array('<?php','?>'),'',str_replace("\$GLOBALS['SEOSHIELD_CONFIG']","\$tmp_global",$config_file));
		eval($config_file);
		foreach($tmp_global as $t=>$v){
			if($t=="links_block_csv_update_time"){
				$tmp_global[$t]=filemtime($this->links_block_csv);
			}
		}
		$new_config_file="<?php\r\n";
		foreach($tmp_global as $t=>$v){
			$new_config_file.="\$GLOBALS['SEOSHIELD_CONFIG']['".$t."']"."=\"".$v."\";\r\n";
		}
		$new_config_file.="?>";
		file_put_contents(SEOSHIELD_ROOT_PATH."/configs/links_block.php",$new_config_file);
	}

	function links_sync()
	{	
		$links_data=$this->csv2array($this->links_block_csv);
		$links_block_result=mysql_query("SELECT ancor,url FROM `links_block`");
		if(mysql_num_rows($links_block_result)==0){
			foreach($links_data as $l){
				$url=preg_replace("#^https?://[^/]+#is","",trim($l[1]));
				if($url=="") $url="/";
				mysql_query("INSERT INTO links_block (url,ancor) VALUES ('".$url."','".$l[0]."')");
			}
		}else{
			$links_block_db=array();
			while($res=mysql_fetch_array($links_block_result))
			{
				$links_block_db[]=$res;
			}
			$links_data_diff=array();
			foreach($links_data AS $l=>$v){
				$links_data_diff[]=$v[0].";".$v[1];
			}
			$links_block_db_diff=array();
			foreach($links_block_db AS $l=>$v){
				$links_block_db_diff[]=$v[0].";".$v[1];
			}
			// удаляем из базы записи которые отсутствуют в csv
			$array_diff=array_diff($links_block_db_diff,$links_data_diff);
			foreach($array_diff AS $a){
				$a=explode(';',$a);
				mysql_query("DELETE FROM `links_block` WHERE `ancor`='".$a[0]."' && `url`='".$a[1]."'");
			}
			// записываем в базу новые записи из csv
			$array_diff=array_diff($links_data_diff,$links_block_db_diff);
			foreach($array_diff AS $a){
				$a=explode(';',$a);
				$url=preg_replace("#^https?://[^/]+#is","",trim($a[1]));
				if($url=="") $url="/";
				mysql_query("INSERT INTO `links_block` (ancor,url) VALUES('".$a[0]."','".$url."')");
			}
		}
	}


}