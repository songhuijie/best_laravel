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


$case = 1; //1.txt 获取组织结构实名认证地址 2 查询企业认证信息
switch($case){
    case 1:
        CommonHelper::printMsg('*****获取组织机构核身地址*****');
        $authType = null;
        $contextInfo = [
            'contextId'=>'xxx',
            'notifyUrl'=>'xxxx',
            'redirectUrl'=>'http://www.baidu.com',
        ];
        $orgEntity = [
            'name'=>$organName,
            'certNo'=>$orgCode,
            'certType'=>'ORGANIZATION_USC_CODE',
            'legalRepCertNo'=>$legalRepCertNo,
            'legalRepCertType'=>'INDIVIDUAL_CH_IDCARD',
            'legalRepName'=>$legalRepName

        ];
        $repeatIdentity = true;

        $res = $organAuthHelper->webNoAccount($authType,$contextInfo,$orgEntity,$repeatIdentity);
        CommonHelper::printMsg($res);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $authUrl = $res['data']['shortLink'];
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);
        CommonHelper::printMsg('核身认证地址(可直接在浏览器中访问)'.$authUrl);


        break;
    case 2:
        $flowId = 'xxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询企业实名状态*****');
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








