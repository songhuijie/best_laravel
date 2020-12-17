<?php

/** 企业-网页版 */
namespace tech;
use tech\esign_core\AccountHelper;
use tech\esign_core\FlowQueryHelper;
use tech\esign_core\IndividualAuthHelper;
use tech\esign_core\OrganAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$username = 'xxx';  //姓名
$idNo = 'xxxx'; //身份证
$mobileNo = 'xxxx'; //手机号
$thirdPartyUserId = 'xx'; //第三方用户id
$idType = 'CRED_PSN_CH_IDCARD'; //证件类型
$email = 'xxxx'; //邮箱

$organName = 'xxxx';
$orgCode = 'xxxx';
$organIdType = 'CRED_ORG_USCC';
$legalRepName = 'xxxx';
$legalRepCertNo='xxxx';
$thirdPartyOrganId = 'xxxx';

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$organAuthHelper = new OrganAuthHelper();
$accountHelper = new AccountHelper();
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


$case = 2; //1.txt 获取组织结构实名认证地址 2 查询企业认证信息
switch($case){
    case 1:
        CommonHelper::printMsg('*****创建个人账号*****');
        $res = $accountHelper->createByThirdPartyUserId($thirdPartyUserId,$username,$idType,$idNo,$mobileNo,$email);
        CommonHelper::printMsg($res);
        if(!isset($res['data']['accountId'])){
            CommonHelper::printMsg('个人账号创建失败');exit;
        }
        $userAccountId = $res['data']['accountId'];

        CommonHelper::printMsg('*****创建组织机构*****');
        $res = $accountHelper->createOrganByThirdPartyUserId($thirdPartyOrganId,$userAccountId,$organName,$organIdType,$orgCode,$legalRepCertNo,$legalRepName);
        CommonHelper::printMsg($res);
        $orgAccountId = $res['data']['orgId'];
        if(!isset($res['data']['orgId'])){
            CommonHelper::printMsg('组织结构账号创建失败');exit;
        }

        CommonHelper::printMsg('*****获取组织机构实名认证地址（电子签名场景专用）*****');

        $agentAccountId = $userAccountId;
        $authType = null;//指定认证类型

        $contextInfo = [ //业务方交互上下文信息
            'contextId'=>'0001',//发起方业务上下文标识
            'notifyUrl'=>'', //发起方接收实名认证状态变更通知的地址
            'origin'=>'', //认证发起来源，BROWSER - 浏览器；APP - 移动端APP。不填默认为为BROWSER
            'redirectUrl'=>'',//认证结束后页面跳转地址
        ];
//        $orgEntity = [
//            'name'=>$organName,
//            'certNo'=>$orgCode,
//            'certType'=>'ORGANIZATION_USC_CODE'
//        ]; //企业基本信息
        $repeatIdentity = false;
        $res = $organAuthHelper->web($orgAccountId,$agentAccountId,$contextInfo,[],$repeatIdentity);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $authUrl = $res['data']['shortLink'];
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);
        CommonHelper::printMsg('实名认证地址(可直接在浏览器中访问)'.$authUrl);

        break;
    case 2:
        $flowId = 'xxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询企业实名状态*****');
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








