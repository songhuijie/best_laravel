<?php
/**
 * Created by IntelliJ IDEA.
 * User: cmn
 * Date: 2019-12-16
 * Time: 15:00
 */


/** 企业-信息比对 */
namespace tech;
use tech\esign_core\OrganVerifyHelper;
use tech\esign_core\TokenHelper;
use tech\esign_core\IndividualVerifyHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$name = 'xxx';
$orgCode = 'xxxx';
$legalRepName = 'xxxx';
$legalRepCertNo='xxxxx';


CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$organHelper = new OrganVerifyHelper();
$result = $tokenHelper->getToken();

if($result['code'] == 0){
    //获取之后存到文件中,此处根据实际情况选择token处理方式，推荐redis或数据库
    $token = $result['data']['token'];
    $file = fopen(ESIGN_ROOT.'/token.txt', "w") or CommonHelper::printMsg('unable open file token.txt');
    fwrite($file, $token);
}else{
    CommonHelper::printMsg('token获取失败');
}

CommonHelper::printMsg('*****企业信息比对接口*****');
$case = 3;
switch($case){
    case 1:
        CommonHelper::printMsg('企业2要素信息比对:');
        $res = $organHelper->enterpriseBase($name,$orgCode);
        CommonHelper::printMsg($res);
        break;
    case 2:
        CommonHelper::printMsg('企业3要素信息比对:');
        $res = $organHelper->enterpriseBureau3Factors($name,$orgCode,$legalRepName);
        CommonHelper::printMsg($res);
        break;
    case 3:
        CommonHelper::printMsg('企业4要素信息比对:');
        $res = $organHelper->enterpriseBureau4Factors($name,$orgCode,$legalRepName,$legalRepCertNo);
        CommonHelper::printMsg($res);
        break;
    case 4:
        $codeUSC = 'xxxx'; //律所统一社会信用代码号
        CommonHelper::printMsg('律所3要素信息比对:');
        $res = $organHelper->lawFirm($name,$codeUSC,$legalRepName);
        CommonHelper::printMsg($res);
        break;
    case 5:

        CommonHelper::printMsg('组织机构3要素信息比对:');
        $res = $organHelper->verify($name,$orgCode,$legalRepName);
        CommonHelper::printMsg($res);
        break;
    case 6:
        $codeUSC = 'xxxx';
        CommonHelper::printMsg('非工商组织3要素信息比对:');
        $res = $organHelper->social($name,$codeUSC,$legalRepName);
        CommonHelper::printMsg($res);
        break;
    default:
        CommonHelper::printMsg('场景错误:');

}





