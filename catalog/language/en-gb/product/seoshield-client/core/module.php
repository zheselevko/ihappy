<?php
if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

class seoShieldModule {
    function csv2array($csv_file_name)
	{
		$files_lines=file($csv_file_name);

		$data=array();
		foreach($files_lines AS $line)
		{
			if(strpos($GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding'],"1251")===false
				&& function_exists("iconv")){
				$line=iconv("windows-1251",$GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']."//IGNORE",$line);
			}
			if(function_exists("str_getcsv")){
				$data[]=str_getcsv(trim($line),";");
			}else{
				$data[]=explode(";",trim($line));
			}
		}

		return $data;
	}

	/**
	 * замена h1
	 */
    function replace_page_h1($out_html, $h1)
	{
		if(strpos($GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding'], "utf") === false
			&& function_exists("iconv")){
			$h1 = iconv("utf-8", $GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']."//IGNORE", $h1);
		}

		$new_h1 = '';
		if(isset($h1)){
			$find = preg_match('#<h1([^>]*)>(.(?!</h1>))*?.?</h1>#is', $out_html, $current_h1_pregs);
			if($h1 !== false)
			{
				$new_h1 = '<h1' . $current_h1_pregs[1] . '>' . $h1 . '</h1>';
			}

			if($find)
			{
				$out_html = str_replace($current_h1_pregs[0], $new_h1, $out_html);
			}
		}

		return $out_html;
	}

	function replace_page_meta_description($out_html,$description)
	{
		if(strpos($GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding'], "utf") === false
			&& function_exists("iconv")){
			$description = iconv("utf-8", $GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']."//IGNORE", $description);
		}

		// замена description
		$new_meta_description="";
		if(isset($description)){
			if($description!==false){
				$new_meta_description="<meta name=\"description\" content=\"".$description."\" />";
			}
			if(preg_match("#<meta[^>]*name *= *[\"']description[\"'][^>]*content *= *[\"']([^\"']*)[\"'][^>]*>#is",$out_html,$current_description_pregs)){
				$out_html=str_replace($current_description_pregs[0],$new_meta_description,$out_html);
			}else{
				$out_html=str_replace("</head>",$new_meta_description."</head>",$out_html);
			}
		}

		return $out_html;
	}

	function replace_page_meta_keywords($out_html,$keywords)
	{
		if(strpos($GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding'], "utf") === false
			&& function_exists("iconv")){
			$keywords = iconv("utf-8", $GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']."//IGNORE", $keywords);
		}

		// замена keywords
		$new_meta_keywords="";
		if(isset($keywords)){
			if($keywords!==false){
				$new_meta_keywords="<meta name=\"keywords\" content=\"".$keywords."\" />";
			}
			if(preg_match("#<meta[^>]*name *= *[\"']keywords[\"'][^>]*content *= *[\"']([^\"']*)[\"'][^>]*>#is",$out_html,$current_keywords_pregs)){
				$out_html=str_replace($current_keywords_pregs[0],$new_meta_keywords,$out_html);
			}else{
				$out_html=str_replace("</head>",$new_meta_keywords."</head>",$out_html);
			}
		}

		return $out_html;
	}

	function replace_page_title($out_html,$title)
	{
		if(strpos($GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding'], "utf") === false
			&& function_exists("iconv")){
			$title = iconv("utf-8", $GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']."//IGNORE", $title);
		}

		// замена title
		$new_title="";
		if(isset($title)){
			$find=preg_match("#<title([^>]*)>([^>]*)</title>#is",$out_html,$current_title_pregs);
			if($title!==false){
				$new_title="<title".$current_title_pregs[1].">".$title."</title>";
			}
			if($find){
				$out_html=str_replace($current_title_pregs[0],$new_title,$out_html);
			}else{
				$out_html=str_replace("</head>",$new_title."</head>",$out_html);
			}
		}

		return $out_html;
	}

    function replace_page_content($out_html, $content)
    {
    	if(strpos($GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding'], "utf") === false
			&& function_exists("iconv")){
			$content = iconv("utf-8", $GLOBALS['SEOSHIELD_CONFIG']['site_default_encoding']."//IGNORE", $content);
		}
		
        if(isset($GLOBALS['SEOSHIELD_CONFIG']['content_area_selector']))
        {
            foreach($GLOBALS['SEOSHIELD_CONFIG']['content_area_selector'] AS $selector)
            {
                switch($selector['type'])
                {
                    case'regex':
                        if(preg_match($selector['pattern'], $out_html, $pregs))
                        {
                            if(sizeof($pregs) == 4)
                            {
                                $out_html = str_replace($pregs[0], $pregs[1] . $content . $pregs[3], $out_html);
                            }
                            else
                            {
                                $out_html = str_replace($pregs[0], $content, $out_html);
                            }
                            break;
                        }
                        break;
                }
            }
        }

        return $out_html;
    }
}