<?php
/*
* модуль подменяет статические мета данные
*/

if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

class SeoShieldModule_static_meta extends seoShieldModule
{
    /**
     * метод для обновления данных
     */
    function update_data($data)
    {
        var_dump($data);
    }

    function html_out_buffer($out_html)
    {
        if(file_exists(SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php'))
        {
            $data = require(SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php');

            foreach($data AS $url => $r)
            {
                if($url == $GLOBALS['SEOSHIELD_CONFIG']['page_uri'])
                {
                    if(!empty($r[0]))
                    {
                        $title = trim($r[0]);
                    }elseif($r[0] == 'удалить'
                        || $r[0] == '')
                    {
                        $title = '';
                    }
                    if ($title != 'null')
                        $out_html = $this->replace_page_title($out_html, $title);

                    if(!empty($r[1]))
                    {
                        $description = trim($r[1]);
                    }elseif($r[1] == 'удалить'
                        || $r[1] == '')
                    {
                        $description = '';
                    }
                    if(strpos($description, 'Генерируется по формуле') === false
                      || $description != 'null')
                        $out_html = $this->replace_page_meta_description($out_html, $description);

                    if(!empty($r[2]))
                    {
                        $h1 = trim($r[2]);
                    }elseif($r[2] == 'удалить'
                        || $r[2] == '')
                    {
                        $h1 = '';
                    }
                    if ($h1 != 'null')
                        $out_html = $this->replace_page_h1($out_html, $h1);

                    if(!empty($r[3]))
                    {
                        $content = trim($r[3]);
                    }elseif($r[3] == 'удалить'
                        || $r[3] == '')
                    {
                        $content = '';
                    }
                    if ($content != 'null')
                        $out_html = $this->replace_page_content($out_html, $content);
                }
            }
        }
        else
        {
            $csv_file_name = SEOSHIELD_ROOT_PATH . '/data/static_meta.csv';

            if(file_exists($csv_file_name)
                && is_readable($csv_file_name))
            {
                $meta_data = $this->csv2array($csv_file_name);

                foreach($meta_data AS $cols)
                {
                    $url = preg_replace("#^https?://[^/]+#is","",trim($cols[0]));
                    if(substr($url, 0, 1) != '/')
                        continue;

                    if($url==$GLOBALS['SEOSHIELD_CONFIG']['page_uri']){
                        if(!empty($cols[1])){
                            $title=trim($cols[1]);
                        }elseif($cols[1]=="удалить"
                            || $cols[1]==""){
                            $title="";
                        }
                        $out_html=$this->replace_page_title($out_html,$title);

                        if(!empty($cols[2])){
                            $description=trim($cols[2]);
                        }elseif($cols[2]=="удалить"
                            || $cols[2]==""){
                            $description="";
                        }
                        if(strpos($description,"Генерируется по формуле") === false )
                            $out_html=$this->replace_page_meta_description($out_html,$description);

                        if(!empty($cols[3])){
                            $h1=trim($cols[3]);
                        }elseif($cols[3]=="удалить"
                            || $cols[3]==""){
                            $h1="";
                        }
                        $out_html = $this->replace_page_h1($out_html,$h1);
                    }
                }
            }
        }

        return $out_html;
    }
}
