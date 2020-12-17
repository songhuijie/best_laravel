<?php
/**
 * Created by IntelliJ IDEA.
 * User: cmn
 * Date: 2019-10-16
 * Time: 20:01
 */

namespace tech\esign_utils;

class CommonHelper
{
    public static function printMsg($msg){
        echo "<pre>";
        if(is_array($msg)){
            var_dump($msg);
        }else{
            echo $msg . "\n";
        }
    }

    static function writeLog($text) {
        if(is_array($text) || is_object($text)){
            $text = json_encode($text);
        }

        file_put_contents ( "log/log.txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
    }

    static function getRealFileIgnore($filePath)
    {
        if (class_exists('\CURLFile')) {
            return new \CURLFile($filePath);

        } else {
            return '@' . $filePath;
        }
    }


    // 判断是网络路径还是文件路径
    static function isUrl($url){
        $parse = parse_url($url);
        $scheme = $parse['scheme'];
        $scheme = strtolower($scheme);
        if("http" == $scheme){
            return true;
        }

        if("https" == $scheme){
            return true;
        }
        return false;
    }


}
