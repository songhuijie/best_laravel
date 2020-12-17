<?php

/** 个人-银行卡4要素实名 */
namespace tech;
use tech\esign_core\AccountHelper;
use tech\esign_core\FlowQueryHelper;
use tech\esign_core\IndividualAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据
$name = 'xx';//姓名
$idNo = 'xxx';//证件号
$mobileNo = 'xxx';//手机号
$cardNo = 'xxx';//银行卡号
$thirdPartyUserId = 'xx';//第三方用户id
$idType = 'CRED_PSN_CH_IDCARD';
$email = 'xx';//邮箱

CommonHelper::printMsg('*****开始获取鉴权token，全局获取，有效期120分钟*****');
$tokenHelper = new TokenHelper();
$individualAuthHelper = new IndividualAuthHelper();
$accountHelper = new AccountHelper();
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


$case = 2; //1.txt 创建流程 2 验证码校验 3 查询认证信息
switch($case){
    case 1:
        CommonHelper::printMsg('*****创建个人账号*****');
        $res = $accountHelper->createByThirdPartyUserId($thirdPartyUserId,$name,$idType,$idNo,$mobileNo,$email);
        CommonHelper::printMsg($res);
        $accountId = $res['data']['accountId'];
        CommonHelper::printMsg('*****发起银行4要素实名认证（电子签名场景专用），成功后向手机号发送验证码*****');
        $repetition = true; //是否允许账号重复实名
        $contextId = 'xx'; //对接方业务上下文id，将在异步通知及跳转时携带返回对接方
        $notifyUrl = 'xxxx'; //认证结束后异步通知地址
        $res = $individualAuthHelper->bankCard4Factors($accountId,$mobileNo,$cardNo,$repetition,$contextId,$notifyUrl);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }
        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);

        break;
    case 2:
        $flowId = 'xxx'; //获取case = 1.txt 的flowId
        CommonHelper::printMsg('*****手机号验证码校验（回填收到的验证码）*****');
        $auchCode = 'xxx'; //TODO  回填收到的验证码
        $res = $individualAuthHelper->authBankCard4FactorsCode($flowId,$auchCode);
        CommonHelper::printMsg($res);
        break;
    case 3:
        $flowId = 'xxxx';
        CommonHelper::printMsg('*****查询认证信息*****');
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








