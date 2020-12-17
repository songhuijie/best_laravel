<?php
/**
 * Created by PhpStorm.
 * User: songhuijie
 * Date: 2020-09-17
 * Time: 14:46
 */




/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
if(!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if(isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}



/**
 * 把Tree转换为有序列表
 * @return array
 */
if(!function_exists('tree_to_ordered_list')) {
    function tree_to_ordered_list($arr,$level=0,$field='name',$child='_child') {
        static $tree=array();
        if(is_array($arr)) {
            foreach ($arr as $key=>$val) {
                $val[$field] = str_repeat('—', $level).$val[$field];
                $tree[]=$val;
                if(isset($val[$child])) {
                    tree_to_ordered_list($val[$child],$level+1,$field,$child);
                }
            }
        }
        return $tree;
    }
}

/**
 * 记录日志
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('action_log')) {
    function action_log($objectId = 0,$remark = '',$type='USER') {
        if(empty($data['url'])) {
            $data['url'] = $_SERVER['REQUEST_URI'];
        }

        $data['type'] = $type;
        $data['object_id'] = $objectId;

        if(!empty(auth()->user())) {
            $data['object_id'] = auth()->user()->id;
        }

        $data['remark'] = $remark;
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
//        ActionLog::create($data);
    }
}


/**
 * 改变env文件
 *
 * @param  Request  $request
 * @return Response
 */

if(!function_exists('modify_env')) {
    function modify_env(array $data)
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

        $contentArray->transform(function ($item) use ($data){
            foreach ($data as $key => $value){
                if(Str::contains($item, $key)){
                    return $key . '=' . $value;
                }
            }

            return $item;
        });

        $content = implode($contentArray->toArray(), "\n");

        \File::put($envPath, $content);
    }
}

/**
 * 循环删除目录和文件函数
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('del_folder_anything')) {
    function del_folder_anything($dirPath)
    {
        if(is_file($dirPath)) {
            $result = unlink($dirPath);
        } else {
            if ($handle = opendir($dirPath)) {
                while (false !== ($item = readdir($handle))) {
                    if ($item != "." && $item != "..") {
                        if (is_dir("$dirPath/$item")) {
                            del_folder_anything("$dirPath/$item");
                        } else {
                            if(!unlink("$dirPath/$item")) {
                                return 'error';
                            }
                        }
                    }
                }
                closedir($handle);
                if (!rmdir($dirPath)) {
                    return 'error';
                }
            }
        }
    }
}

/**
 * 循环删除文件并不删除文件夹
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('del_folder_files')) {
    function del_folder_files($dirPath)
    {
        if(is_file($dirPath)) {
            $result = unlink($dirPath);
        } else {
            if ($handle = opendir($dirPath)) {
                while (false !== ($item = readdir($handle))) {
                    if ($item != "." && $item != "..") {
                        if (is_dir("$dirPath/$item")) {
                            del_folder_files("$dirPath/$item");
                        } else {
                            if(!unlink("$dirPath/$item")) {
                                return 'error';
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }
    }
}


/**
 * 判断文件夹是否为空
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('is_empty_folder')) {
    function is_empty_folder($path)
    {
        $handler = @opendir($path);
        $i=0;
        while($_file=readdir($handler)) {
            $i++;
        }
        closedir($handler);
        if($i>2) {
            return false;
        } else {
            return true;  //文件夹为空
        }
    }
}

/**
 * 复制文件到文件夹
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('copy_file_to_folder')) {
    function copy_file_to_folder($sourceFile, $dir)
    {
        if(is_dir($sourceFile)){ // 如果你希望同样移动目录里的文件夹
            return copy_dir_to_folder($sourceFile, $dir);
        }
        if(!file_exists($sourceFile)) {
            return 'error';
        }
        $filename = basename($sourceFile);
        return copy($sourceFile, $dir .'/'. $filename);
    }
}

/**
 * 复制目录到文件夹
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('copy_dir_to_folder')) {
    function copy_dir_to_folder($sourceDir, $dir)
    {
        if((!is_dir($sourceDir)) || (!is_dir($dir))){
            return 'error';
        }
        // 要复制到新目录x`x
        $newPath = $dir.'/'.basename($sourceDir);
        if(!realpath($newPath)){ //
            mkdir($newPath);
        }
        foreach(glob($sourceDir.'/*') as $filename)
        {
            copy_file_to_folder($filename, $newPath);
        }
    }
}


/**
 * 获取文件Mime
 * @author tangtanglove <dai_hang_love@126.com>
 */
if(!function_exists('get_file_mime')) {
    function get_file_mime($fileName='')
    {
        if(!function_exists('mime_content_type')) {
            $mimeTypes = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',

                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',

                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',

                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            );
            $fileExt = explode('.',$fileName);
            $ext = strtolower(array_pop($fileExt));
            if (array_key_exists($ext, $mimeTypes)) {
                return $mimeTypes[$ext];
            } elseif (function_exists('finfo_open')) {
                $fileInfo = finfo_open(FILEINFO_MIME);
                $mimeType = finfo_file($fileInfo, $fileName);
                finfo_close($fileInfo);
                return $mimeType;
            } else {
                return 'application/octet-stream';
            }
        } else {
            return mime_content_type($fileName);
        }
    }
}



