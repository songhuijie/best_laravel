<?php

/** 个人-运营商三要素实名 */
namespace tech;
use tech\esign_core\AccountHelper;
use tech\esign_core\FlowQueryHelper;
use tech\esign_core\IndividualAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$name = 'xxx';//姓名
$idNo = 'xxx';//证件号
$mobileNo = 'xxxx';//手机号

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$individualAuthHelper = new IndividualAuthHelper();
$flowQueryHelper = new FlowQueryHelper();

$result = $tokenHelper->getToken();

if($result['code'] == 0){
    //获取之后存到文件中,此处根据实际情况选择token处理方式，推荐redis或数据库
    $token = $result['data']['token'];
    $file = fopen(ESIGN_ROOT.'/token.txt', "w") or CommonHelper::printMsg('unable open file token.txt');
    fwrite($file, $token);
}else{
    CommonHelper::printMsg('token获取失败');
}


$case = 3; //1.txt 发起运营商3要素核身 2 验证码校验 3 查询认证信息
switch($case){
    case 1:
        $contextId = 'test';
        $notifyUrl = '';
        $res = $individualAuthHelper->telecom3FactorsNoAccount($name,$idNo,$mobileNo,$contextId,$notifyUrl);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);

        break;
    case 2:
        $flowId = 'xxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****手机号验证码校验（回填收到的验证码）*****');
        $auchCode = 'xxx'; //TODO  回填收到的验证码
        $res = $individualAuthHelper->authTelecom3FactorsCode($flowId,$auchCode);
        CommonHelper::printMsg($res);
        break;
    case 3:
        $flowId = 'xxx';//case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询认证信息*****');
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








