<?php

/** 企业-对公打款 */
namespace tech;
use tech\esign_core\AccountHelper;
use tech\esign_core\FlowQueryHelper;
use tech\esign_core\OrganAuthHelper;
use tech\esign_core\TokenHelper;
use tech\esign_utils\CommonHelper;

include("../../eSignOpenAPI.php");

//全局测试数据,建议用真实数据做测试
$username = 'xxx';  //姓名
$idNo = 'xxx'; //身份证
$mobileNo = 'xxx'; //手机号
$thirdPartyUserId = 'xx'; //第三方用户id
$idType = 'CRED_PSN_CH_IDCARD'; //证件类型
$email = 'xxxx'; //邮箱

$organName = '杭州天谷信息科技有限公司';
$orgCode = 'xxx';
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


$case = 0; // 0创建经办人账号并实名 1发起企业实名认证4要素校验（电子签名场景专用） 2查询银行打款信息 3发起随机金额打款认证 4查询打款进度状态 5随机金额校验 6查询认证信息
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
    case 1://发起企业实名认证4要素校验（电子签名场景专用）
        $agentAccountId = 'xxxxx'; // case 1.txt 获取的经办人 accountId,需要先实名
        CommonHelper::printMsg('*****创建组织机构*****');
        $res = $accountHelper->createOrganByThirdPartyUserId($thirdPartyOrganId,$agentAccountId,$organName,$organIdType,$orgCode,$legalRepCertNo,$legalRepName);
        CommonHelper::printMsg($res);
        $orgAccountId = $res['data']['orgId'];
        if(!isset($res['data']['orgId'])){
            CommonHelper::printMsg('组织结构账号创建失败');exit;
        }

        CommonHelper::printMsg('*****发起企业实名认证4要素校验（电子签名场景专用）*****');
        $repetition = true; //是否允许账号重复实名（默认允许重复实名)
        $contextId = 'xxx'; //对接方业务上下文id，将在异步通知及跳转时携带返回对接方
        $notifyUrl = 'http://xxxx'; //认证结束后异步通知地址

        $res = $organAuthHelper->fourFactors($orgAccountId,$agentAccountId,$repetition,$contextId,$notifyUrl);
        var_dump($res);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);
        break;
    case 2://查询银行打款信息
        $flowId = 'xxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询银行支行信息*****');
        $keyWord = 'xxxx';
        $res = $organAuthHelper->getSubBranch($flowId,$keyWord);
        CommonHelper::printMsg($res);

        break;

    case 3://发起随机金额打款认证
        $flowId = 'xxxxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****发起随机金额打款认证*****');
        $bank = '招商银行';
        $province = '浙江省';
        $city = '杭州市';
        $subbranch = '招商银行股份有限公司杭州高新支行'; // case =2 时获取的支行名称
        $cardNo = 'xxxx';
        $cnapsCode = 'xxxx'; // case =2 时获取的联行号
        $contextId = 'xxx'; //业务方业务上下文id，如果不填则取发起实名时的contextId
        $notifyUrl = 'http://xxxx'; //打款成功后异步通知地址,收到成功通知不代表真实打款成功，只表示银行已受理该笔业务

        $res = $organAuthHelper->transferRandomAmount($flowId,$bank,$province,$city,$subbranch,$cardNo,$cnapsCode,$contextId,$notifyUrl);
        CommonHelper::printMsg($res);
        break;
    case 4: //随机金额校验
        $flowId = 'xx';
        $amount = 'xxx'; // case 3 打款后，对公账户收到的真实金额，一般到款时间是15-30分钟
        $res = $organAuthHelper->verifyRandomAmount($flowId,$amount);
        CommonHelper::printMsg($res);

        break;
    case 5: //查询认证信息
        $flowId = 'xxx';
        $res = $flowQueryHelper->queryAuthDetail($flowId);
        CommonHelper::printMsg($res);

        break;
    default:
        CommonHelper::printMsg('场景有误');

}








