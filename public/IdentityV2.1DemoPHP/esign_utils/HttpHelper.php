<?php
/**
 * Created by IntelliJ IDEA.
 * User: cmn
 * Date: 2019-09-07
 * Time: 11:41
 */

namespace tech\esign_utils;

use tech\esign_constant\Config;
use tech\esign_core\TokenHelper;

class HttpHelper
{
    public static $connectTimeout = 30;//30 second
    public static $readTimeout = 80;//80 second

    public static function doPost($url, $data) {
        echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_URL, $url);
//        if(!empty($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
        $appId = Config::$config['appId'];
        $token = TokenHelper::getFileToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Tsign-Open-App-Id:".$appId, "X-Tsign-Open-Token:".$token, "Content-Type:application/json" ));
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        var_dump($return_code);
        return $return_content;
    }




    /**
     * @param $url
     * @param $data
     * @return mixed
     */
    public static function doGetCommon($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json" ));
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return  $return_content;
    }


    public static function doGet($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $appId = Config::$config['appId'];
        $token = TokenHelper::getFileToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Tsign-Open-App-Id:".$appId, "X-Tsign-Open-Token:".$token, "Content-Type:application/json" ));
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        return $return_content;
    }



    public static function sendHttpPUT($uploadUrls, $contentMd5, $fileContent){
        $header = array(
            'Content-Type:application/pdf',
            'Content-Md5:' . $contentMd5
        );

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $uploadUrls);
        curl_setopt($curl_handle, CURLOPT_FILETIME, true);
        curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
        curl_setopt($curl_handle, CURLOPT_HEADER, true); // 输出HTTP头 true
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 5184000);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'PUT');

        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $fileContent);
        $result = curl_exec($curl_handle);
        $status = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

        if ($result === false) {
            $status = curl_errno($curl_handle);
            $result = 'put file to oss - curl error :' . curl_error($curl_handle);
        }
        curl_close($curl_handle);
//    $this->debug($url, $fileContent, $header, $result);
        return $status;
    }


    public static function doPut($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 跳过检查
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $appId = Config::$config['appId'];
        $token = TokenHelper::getFileToken();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Tsign-Open-App-Id:".$appId, "X-Tsign-Open-Token:".$token, "Content-Type:application/json" ));
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
//        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $return_content;
    }

    public static function doPutNew($url,$data)
    {

//        $data = [
//            "province"=> "浙江省",
//        ];
//        $data = (is_array($data)) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
//        $ch = curl_init(); //初始化CURL句柄
//        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1.txt); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式
//
////        $appId = '4438771123';
////        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJnSWQiOiI4N2I4YmJhNGY2N2U0ZjRiODQ3Njc2M2FmNTRjZGYxYSIsImFwcElkIjoiNDQzODc3MTEyMyIsIm9JZCI6ImJiZDNlYTExZWY0ZDQyNmI4NTYzNDhmYjg1MDYxM2ZmIiwidGltZXN0YW1wIjoxNTc3MjcxNjQwNzg4fQ.OTEH83gqIREiE76bfx0qVf4wsrre_AwKCMfxlOA1Vek';
//        $appId = Config::$config['appId'];
//        $token = TokenHelper::getFileToken();
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Tsign-Open-App-Id:" . $appId, "X-Tsign-Open-Token:" . $token, "Content-Type:application/json"));
//
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
//        $output = curl_exec($ch);
//        var_dump($output);exit;
//        curl_close($ch);
//        return $output;

        $data = (is_array($data)) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;

        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        $appId = Config::$config['appId'];
        $token = TokenHelper::getFileToken();
//        curl_setopt($ch, CURLOPT_PUT, true); //设置为PUT请求
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Tsign-Open-App-Id:" . $appId, "X-Tsign-Open-Token:" . $token, "Content-Type:application/json"));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
        $res = curl_exec($ch);
        return $res;
    }

}
