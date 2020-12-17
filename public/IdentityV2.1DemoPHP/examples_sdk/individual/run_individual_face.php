<?php

/** 个人-刷脸实名 */
namespace tech;
use tech\esign_core\AccountHelper;
use tech\esign_core\IndividualAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$name = 'xxx'; //姓名
$idNo = 'xxxxx'; //证件号

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$individualAuthHelper = new IndividualAuthHelper();
$result = $tokenHelper->getToken();

if($result['code'] == 0){
    //获取之后存到文件中,此处根据实际情况选择token处理方式，推荐redis或数据库
    $token = $result['data']['token'];
    $file = fopen(ESIGN_ROOT.'/token.txt', "w") or CommonHelper::printMsg('unable open file token.txt');
    fwrite($file, $token);
}else{
    CommonHelper::printMsg('token获取失败');
}


$case = 1; //1.txt 创建流程 2 查询个人刷脸状态
switch($case){
    case 1:
        CommonHelper::printMsg('*****发起个人刷脸核身*****');
        $contextId = 'xxx'; //对接方业务上下文id，将在异步通知及跳转时携带返回对接方
        $notifyUrl = ''; //认证结束后异步通知地址
        $callbackUrl = 'http://www.baidu.com'; //认证完成后业务重定向地址
        $faceauthMode = 'TENCENT'; //人脸认证方式 TENCENT腾讯微众银行认证 , ZHIMACREDIT支付宝芝麻信用认证

        $res = $individualAuthHelper->faceNoAccount($name,$idNo,$faceauthMode,$contextId,$notifyUrl,$callbackUrl);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $authUrl = $res['data']['authUrl'];
        var_dump($authUrl);
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);

        break;
    case 2:
        $flowId = 'xxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询个人刷脸状态*****');
        $res = $individualAuthHelper->queryFaceAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








