<?php
// скрипт занимается обслуживанием запросов от seoshield
error_reporting(0);
ini_set("display_errors",0);

if(!defined("SEOSHIELD_ROOT_PATH"))define("SEOSHIELD_ROOT_PATH",rtrim(realpath(dirname(__FILE__)),"/"));

include_once(SEOSHIELD_ROOT_PATH."/core/module.php");
include_once(SEOSHIELD_ROOT_PATH."/configs/main.php");

/**
 * загружаем остальные конфиги
 */
foreach(glob(SEOSHIELD_ROOT_PATH."/configs/*.php") AS $config_file)
{
    if(basename($config_file)=="main.php")continue;
    include_once($config_file);
}

function error_response($error)
{
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Content-type: application/json");

    $data = base64_decode($_POST['seoshield_query']);
    $data = json_decode($data);

    print json_encode(array(
        'errors' => is_array($error) ? $error : array($error),
        'query' => $data,
    ));
    exit;
}

function success_response($data)
{
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Content-type: application/json");

    print json_encode(array(
        "data"=>$data
    ));
    exit;
}

function getDirInfo($dir_path, &$data = array())
{
    $dir_path=rtrim($dir_path,"/")."/";

    if(is_dir($dir_path)){
        if($dh=opendir($dir_path)){
            while (($file=readdir($dh))!==false)
            {
                if($file=="." 
                    || $file==".."
                    || $file==".DS_Store"
                    || $file==".git")continue;

                $type=filetype($dir_path.$file);

                if($type=="dir"){
                    getDirInfo($dir_path.$file,$data);
                }
                $data[]=array(
                    "path"=>str_replace(SEOSHIELD_ROOT_PATH."/","",$dir_path.$file),
                    "type"=>$type,
                    "perms"=>substr(sprintf("%o",fileperms($dir_path.$file)),-4)
                );
            }
            closedir($dh);
        }
    }

    return $data;
}

function update_files_structure($files, $remove_not_exists = false)
{
    if(!is_array($files)){
        error_response("BAD_QUERY");
    }

    /**
     * сначала обновляем структуру директорий
     */
    foreach($files AS $r)
    {
        if($r->type != 'dir')continue;

        if(file_exists(SEOSHIELD_ROOT_PATH . '/' . $r->path))
            continue;

        if(!mkdir(SEOSHIELD_ROOT_PATH . '/' . $r->path))
        {
            error_response('CANT_CREATE_DIR');
        }
        chmod(SEOSHIELD_ROOT_PATH . '/' . $r->path, 0755);


    }

    /**
     * теперь обновляем файлы
     */
    foreach($files AS $r)
    {
        if($r->type != 'file'
            || $r->path == 'config.php')
            continue;

        if(file_put_contents(SEOSHIELD_ROOT_PATH . '/' . $r->path, $r->content) === false)
        {
            error_response('CANT_WRITE_TO_FILE');
        }
    }
}

// $content=<<<EOF
// /;title-1;description-1;h1-1
// http://jmason.loc/?test=1232;title-2;description-2;h1-2
// /?test=444;title-3;description-3;h1-3
// /?test=555;title-4;description-4;h1-4
// /?test=666;title-5;description-5;h1-5
// EOF;
// // тело запроса
// $_POST['seoshield_query']=base64_encode(json_encode(array(
//  "command"=>"upload_data",
//  "client_files_structure"=>json_encode(array(
//      array(
//          "path"=>"data/static_meta2.csv",
//          "type"=>"file",
//          "perms"=>"0755",
//          "md5_hash"=>md5($content),
//          "content"=>$content
//      )
//  ))
// )));
// // формируем проверочный ключ запроса
// $_POST['seoshield_query_hash']=md5($_POST['seoshield_query']).md5("34nh34x34d64bz3346hzh6346nxg346");

if (!empty($_POST['check_connection']))
{
    $data = array();
    $data['status'] = 'success';
    $data['access_key'] = false;
    $data['file_access'] = false;
    $data['directory_access'] = false;
    if (!empty($_POST['access_key'])
            && ($_POST['access_key'] == $GLOBALS['SEOSHIELD_CONFIG']['access_key']))
        $data['access_key'] = true;
    if (is_dir(SEOSHIELD_ROOT_PATH . '/data'))
    {
        if (is_writable(SEOSHIELD_ROOT_PATH . '/data'))
            $data['directory_access'] = true;
        else
        {
            chmod(SEOSHIELD_ROOT_PATH . '/data', 777);
            if (is_writable(SEOSHIELD_ROOT_PATH . '/data'))
                $data['directory_access'] = true;
            else
                $data['directory_access'] = 'no access';   
        }
    }
    else
        $data['directory_access'] = 'no directory';
    if (is_file(SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php'))
    {
        if (is_writable(SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php'))
            $data['file_access'] = true;
        else
        {
            chmod(SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php', 777);
            if (is_writable(SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php'))
                $data['file_access'] = true;
            else
                $data['file_access'] = 'no access';   
        }
    }
    else
        $data['file_access'] = 'no file';
    success_response($data);
}

if(!empty($_POST['seoshield_query'])
    && !empty($_POST['seoshield_query_hash'])){

    $current_query_hash=md5($_POST['seoshield_query'] . $GLOBALS['SEOSHIELD_CONFIG']['access_key']);
    if($current_query_hash == $_POST['seoshield_query_hash'])
    {
        $data = json_decode(base64_decode($_POST['seoshield_query']));
        switch($data->command)
        {
            /**
             * синхронизирует данные модулей с seoshield
             */
            case 'sync_data':
                switch($data->destination)
                {
                    case 'static_meta':
                    case 'static_meta:by_id':
                        $cache_file = SEOSHIELD_ROOT_PATH . '/data/static_meta.cache.php';

                        $cache_file_time = 0;
                        if(file_exists($cache_file))
                            $cache_file_time = filemtime($cache_file);

                        $data->data = json_decode($data->data);

                        /**
                         * кеш файл на сервере новее того что мы
                         * последний раз синхронизировали с шилдом
                         *
                         * проверяем что изменилось для ручной синхронизации
                         */
                        $save_data_from_shield = true;

                        /**
                         * TODO: реализовать 2х стороннюю синхронизацию
                         */
                        // if($cache_file_time > $data->data->cache_file_time
                        //     && file_exists($cache_file))
                        // {
                        //     $save_data_from_shield = false;

                        //     $current_data = require($cache_file);

                        //     if(is_array($current_data)
                        //         && sizeof($current_data) > 0)
                        //     {
                        //         $result = array(
                        //             'different_on_site' => array(),
                        //             'not_exists_on_site' => array(),
                        //             'different_on_shield' => array(),
                        //             'not_exists_on_shield' => array(),
                        //         );

                        //         foreach($data->data AS $url => $r)
                        //         {
                        //             if(isset($current_data[$url]))
                        //             {
                        //                 if($current_data[$url] != $r)
                        //                     $result['different_on_site'][$url] = $r;
                        //             }
                        //             else
                        //             {
                        //                 $result['not_exists_on_site'][$url] = $r;
                        //             }
                        //         }

                        //         foreach($current_data AS $url => $r)
                        //         {
                        //             if(isset($data->data->{$url}))
                        //             {
                        //                 if($data->data->{$url} != $r)
                        //                     $result['different_on_shield'][$url] = $r;
                        //             }
                        //             else
                        //             {
                        //                 $result['not_exists_on_shield'][$url] = $r;
                        //             }
                        //         }

                        //         success_response(array(
                        //             'check_result' => $result,
                        //             'complete' => 'sync_problem',
                        //             'cache_file_time' => $cache_file_time,
                        //         ));
                        //     }
                        // }

                        $cache_new_data = (array)$data->data->data;

                        if($data->destination == 'static_meta:by_id'
                            && file_exists($cache_file))
                        {
                            $current_data = require($cache_file);
                            $current_data = array_merge($current_data, $cache_new_data);

                            $cache_new_data = $current_data;
                            
                            unset($current_data);
                        }

                        /**
                         * похоже все ок,
                         * просто сохраняем данные которые пришли в кеш файл
                         */
                        if($save_data_from_shield)
                        {
                            file_put_contents(
                                $cache_file,
                                '<?php return ' . var_export($cache_new_data, true) . ';'
                            );

                            $cache_file_time = filemtime($cache_file);

                            success_response(array(
                                'complete' => 'success',
                                'cache_file_time' => $cache_file_time,
                            ));
                        }

                        break;

                    default:
                        error_response("SYNC_DESTINATION_NOT_FOUND");
                        break;
                }
                break;

            /**
             * обновляем данные по клиенту сеошилда
             */
            case 'upload_data':
                $client_files_structure = json_decode($data->client_files_structure);

                update_files_structure($client_files_structure,true);

                success_response(array(
                    'complete' => 'success'
                ));
            break;

            /**
             * метод для обновления клиента
             */
            case'upload_files':
                $client_files_structure = json_decode($data->client_files_structure);
                
                update_files_structure($client_files_structure, true);

                success_response(array(
                    'complete' => 'success'
                ));
            break;

            /**
             * метод для создания бекапа клиента
             */
            case'get_client_backup':
                $client_files_structure = getDirInfo(SEOSHIELD_ROOT_PATH);
                $files_data = array();
                foreach($client_files_structure AS $i => $r)
                {
                    if($r['type'] == 'file')
                    {
                        $content = file_get_contents($r['path']);
                        $client_files_structure[$i]['content'] = $content;
                        $client_files_structure[$i]['md5_hash'] = md5($content);
                    }
                }
                success_response(array(
                    "client_files_structure" => $client_files_structure
                ));
            break;

            /**
             * метод для получения информации о системе
             */
            case 'get_info':
                $data = array();

                /**
                 * структура, размер, и права на файлы
                 */
                $data['client_files_structure'] = getDirInfo(SEOSHIELD_ROOT_PATH);

                /**
                 * время на сервере
                 */
                $data['server_unix_time'] = time();
                $data['server_time_zone'] = date_default_timezone_get();

                /**
                 * информация о РНР
                 */
                if(function_exists("phpversion"))
                {
                    $data['php_version'] = phpversion();
                }

                if(function_exists("get_loaded_extensions"))
                {
                    $data['php_extensions'] = get_loaded_extensions();
                }

                /**
                 * информация о апаче
                 */
                if(function_exists("apache_get_modules"))
                {
                    $data['apache_modules'] = apache_get_modules();
                }

                /**
                 * стандартные переенные РНР
                 */
                $data['server_var'] = $_SERVER;

                /**
                 * конфиг клиента
                 */
                $config = $GLOBALS['SEOSHIELD_CONFIG'];
                unset($config['access_key']);
                $data['seoshield_config'] = $config;
                
                success_response($data);
            break;
            default:
                error_response("COMMAND_NOT_FOUND");
            break;
        }
    }else{
        error_response("BAD_ACCESS");
    }
}