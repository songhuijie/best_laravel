<?php


namespace tech\esign_core;
use tech\esign_utils\HttpHelper;
class TokenHelper extends BaseHelper
{
    //获取鉴权token
    public function getToken(){
        echo '获取鉴权Token----------------开始';
        $appId = $this->config['appId'];
        $secret = $this->config['appSecret'];
        $getToken=$this->openApiUrl.$this->urlMap['TOKEN_URL'] . "?appId=$appId&secret=$secret&grantType=client_credentials";
        $return_content = HttpHelper::doGetCommon($getToken,[]);
        $result = (array)json_decode($return_content,true);

        return $result;

    }

    public static function getFileToken(){
//echo ESIGN_ROOT.'/token.txt';exit;
        $file = ESIGN_ROOT.'/token.txt'; ///Users/cmn/Sites/eqianbao/IdentityV2.1DemoForIndividualAuth/token.txt
        $myfile = fopen($file, "r") or die("Unable to open file!");
        $token = fread($myfile,filesize($file));
        fclose($myfile);
        return $token;
    }

}
