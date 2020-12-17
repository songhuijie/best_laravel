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
$idNo = 'xxx'; //证件号
$mobileNo = 'xxx'; //手机号
$thirdPartyUserId = 'xxxx'; //第三方用户id
$idType = 'CRED_PSN_CH_IDCARD';
$email = 'xx';//邮箱

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$individualAuthHelper = new IndividualAuthHelper();
$accountHelper = new AccountHelper();
$result = $tokenHelper->getToken();

if($result['code'] == 0){
    //获取之后存到文件中,此处根据实际情况选择token处理方式，推荐redis或数据库
    $token = $result['data']['token'];
    $file = fopen(ESIGN_ROOT.'/token.txt', "w") or CommonHelper::printMsg('unable open file token.txt');
    fwrite($file, $token);
}else{
    CommonHelper::printMsg('token获取失败');
}


$case = 2; //1.txt 创建流程 2 查询个人刷脸状态
switch($case){
    case 1:
        CommonHelper::printMsg('*****创建个人账号*****');
        $res = $accountHelper->createByThirdPartyUserId($thirdPartyUserId,$name,$idType,$idNo,$mobileNo,$email);
        CommonHelper::printMsg($res);
        $accountId = $res['data']['accountId'];
        CommonHelper::printMsg('*****发起个人刷脸实名（电子签名场景专用），申请刷脸地址进行人脸识别认证*****');
        $repetition = true; //是否允许账号重复实名
        $contextId = 'xxx'; //对接方业务上下文id，将在异步通知及跳转时携带返回对接方
        $notifyUrl = ''; //认证结束后异步通知地址
        $callbackUrl = 'xxxx'; //认证完成后业务重定向地址
        $faceauthMode = 'TENCENT'; //人脸认证方式 TENCENT腾讯微众银行认证 , ZHIMACREDIT支付宝芝麻信用认证

        $res = $individualAuthHelper->face($accountId,$faceauthMode,$repetition,$contextId,$notifyUrl,$callbackUrl);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $authUrl = $res['data']['authUrl'];
        var_dump($authUrl);
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);

        break;
    case 2:
        $flowId = 'xx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询个人刷脸状态*****');
        $res = $individualAuthHelper->queryFaceAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








