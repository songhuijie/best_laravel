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
$organName = 'xxx'; //企业名称
$orgCode = 'xxxx'; //统一社会信用代码
$legalRepName = 'xxx'; //法人姓名
$legalRepCertNo='xxxx'; //法人证件号

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$organAuthHelper = new OrganAuthHelper();
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


$case = 1; // 1发起企业核身认证4要素 2发起授权签署实名认证（电子签名场景专用） 3查询授权书签署状态 4查询认证信息
switch($case){
    case 1://发起企业核身认证4要素
        CommonHelper::printMsg('*****发起企业核身认证4要素*****');
        $contextId = 'orderNo111';
        $notifyUrl = 'xxxx';
        $res = $organAuthHelper->fourFactorsNoAccount($organName,$orgCode,$legalRepName,$legalRepCertNo,$contextId,$notifyUrl);
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








