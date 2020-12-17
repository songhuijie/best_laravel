<?php
/**
 * Created by IntelliJ IDEA.
 * User: cmn
 * Date: 2019-12-16
 * Time: 15:00
 */


/** 个人-信息比对 */
namespace tech;
use tech\esign_core\TokenHelper;
use tech\esign_core\IndividualVerifyHelper;
use tech\esign_utils\CommonHelper;

include("eSignOpenAPI.php");

//全局测试数据
$name = 'xxxx';
$idNo = 'xxx';
$mobileNo = 'xxx';
$cardNo = 'xxxxxx';

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$indiviVerifyHelper = new IndividualVerifyHelper();
$result = $tokenHelper->getToken();

if($result['code'] == 0){
    //获取之后存到文件中,此处根据实际情况选择token处理方式，推荐redis或数据库
    $token = $result['data']['token'];
    $file = fopen(ESIGN_ROOT.'/token.txt', "w") or CommonHelper::printMsg('unable open file token.txt');
    fwrite($file, $token);
}else{
    CommonHelper::printMsg('token获取失败');
}


CommonHelper::printMsg('*****个人信息比对接口*****');
CommonHelper::printMsg('个人二要素比对:');
$res = $indiviVerifyHelper->individualBase($name,$idNo);
CommonHelper::printMsg($res);

CommonHelper::printMsg('个人运营商3要素信息比对:');
$res = $indiviVerifyHelper->individualTelecom3Factors($name,$idNo,$mobileNo);
CommonHelper::printMsg($res);

CommonHelper::printMsg('个人银行卡3要素信息比对:');
$res = $indiviVerifyHelper->individualBank3Factors($name,$idNo,$cardNo);
CommonHelper::printMsg($res);

CommonHelper::printMsg('个人银行卡4要素信息比对:');
$res = $indiviVerifyHelper->individualBank4Factors($name,$idNo,$cardNo,$mobileNo);
CommonHelper::printMsg($res);



