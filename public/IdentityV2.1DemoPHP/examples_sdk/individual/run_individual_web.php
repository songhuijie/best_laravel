<?php

/** 个人-网页版 */
namespace tech;
use tech\esign_core\FlowQueryHelper;
use tech\esign_core\IndividualAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$name = 'xxx'; //姓名
$idNo = 'xxx'; //证件号
$mobileNo = 'xxx'; //手机号


CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$individualAuthHelper = new IndividualAuthHelper();
$flowQueryHelper = new FlowQueryHelper();

$result = $tokenHelper->getToken();
if($result['code'] == 0){

    //获取之后存到文件中,此处根据实际情况选择token处理方式，推荐redis或数据库
    $token = $result['data']['token'];
    CommonHelper::printMsg('token获取成功'.$token);

    $file = fopen(ESIGN_ROOT.'/token.txt', "w") or CommonHelper::printMsg('unable open file token.txt');
    fwrite($file, $token);
}else{
    CommonHelper::printMsg('token获取失败');
}


$case = 1; //1.txt 获取个人核身认证地址 2 查询个人刷脸状态
switch($case){
    case 1:
        CommonHelper::printMsg('*****获取个人核身认证地址*****');

        $authType = null;//指定认证类型
        $contextInfo = [ //业务方交互上下文信息
            'contextId'=>'xxx',//发起方业务上下文标识
            'notifyUrl'=>'', //发起方接收实名认证状态变更通知的地址
            'origin'=>'', //认证发起来源，BROWSER - 浏览器；APP - 移动端APP。不填默认为为BROWSER
            'redirectUrl'=>'',//认证结束后页面跳转地址
        ];
        $indivInfo = [
            'name'=>$name,
            'mobileNo'=>$mobileNo,
            'certNo'=>$idNo,
        ]; //个人基本信息
        $repeatIdentity = true;
        $res = $individualAuthHelper->webNoAccount($authType,$contextInfo,$indivInfo,$repeatIdentity);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $authUrl = $res['data']['shortLink'];
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);
        CommonHelper::printMsg($authUrl);

        break;
    case 2:
        $flowId = 'xxxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询个人实名状态*****');
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








