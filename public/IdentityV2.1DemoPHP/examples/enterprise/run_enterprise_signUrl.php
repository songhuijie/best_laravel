<?php

/** 企业-法人授权书 */
namespace tech;
use tech\esign_core\AccountHelper;
use tech\esign_core\FlowQueryHelper;
use tech\esign_core\OrganAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$username = 'xxxx';  //姓名
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


$case = 0; // 0创建经办人账号并实名 1发起企业实名认证4要素校验（电子签名场景专用） 2发起授权签署实名认证（电子签名场景专用） 3查询授权书签署状态 4查询认证信息
switch($case){
    case 0:
        CommonHelper::printMsg('*****创建个人账号*****');
        $res = $accountHelper->createByThirdPartyUserId($thirdPartyUserId,$username,$idType,$idNo,$mobileNo,$email);
        CommonHelper::printMsg($res);
        if(!isset($res['data']['accountId'])){
            CommonHelper::printMsg('个人账号创建失败');exit;
        }
        $agentAccountId = $res['data']['accountId'];
        CommonHelper::printMsg('个人账号创建成功：'.$agentAccountId.',需调用 examples/individual/run_individual_xxx.php 完成个人的实名认证');exit;
        break;
    case 1://发起企业实名认证4要素校验（电子签名场景专用）
        $agentAccountId = 'xxxx'; // case 1.txt 获取的经办人 accountId,需要先实名
        CommonHelper::printMsg('*****创建组织机构*****');
        $res = $accountHelper->createOrganByThirdPartyUserId($thirdPartyOrganId,$agentAccountId,$organName,$organIdType,$orgCode,$legalRepCertNo,$legalRepName);
        CommonHelper::printMsg($res);
        $orgAccountId = $res['data']['orgId'];
        if(!isset($res['data']['orgId'])){
            CommonHelper::printMsg('组织结构账号创建失败');exit;
        }

        CommonHelper::printMsg('*****发起企业实名认证4要素校验（电子签名场景专用）*****');
        $repetition = true; //是否允许账号重复实名（默认允许重复实名)
        $contextId = 'xxxx'; //对接方业务上下文id，将在异步通知及跳转时携带返回对接方
        $notifyUrl = 'xxxx'; //认证结束后异步通知地址

        $res = $organAuthHelper->fourFactors($orgAccountId,$agentAccountId,$repetition,$contextId,$notifyUrl);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);
        break;
    case 2://发起授权签署实名认证（电子签名场景专用）,接口调用成功后，mobileNo会收到一条短信
        $flowId = 'xxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****发起授权签署实名认证*****');

        $mobileNo = 'xxxxx'; //法定代表人手机号，用于签署电子授权书
        $legalRepIdNo= ''; //法定代表人身份证号,如果企业账号中已设置，可为空；否则需传入
        $res = $organAuthHelper->legalRepSignAuth($flowId,$mobileNo,$legalRepIdNo);
        CommonHelper::printMsg($res);
        break;
    case 3://查询授权书签署状态
        $flowId = 'xxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询授权书签署状态*****');

        $res = $organAuthHelper->legalRepSignResult($flowId);
        CommonHelper::printMsg($res);
        break;
    case 4: //查询认证信息
        $flowId = 'xxxx';
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








