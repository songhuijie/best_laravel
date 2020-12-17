<?php

/** 企业-对公打款 */
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


$case = 3; // 1发起企业核身认证4要素 2查询银行打款信息 3发起随机金额打款认证 4查询打款进度状态 5随机金额校验 6查询认证信息
switch($case){
    case 1://发起企业核身认证4要素
        CommonHelper::printMsg('*****发起企业核身认证4要素*****');
        $contextId = 'xxx';
        $notifyUrl = 'xxxx';
        $res = $organAuthHelper->fourFactorsNoAccount($organName,$orgCode,$legalRepName,$legalRepCertNo,$contextId,$notifyUrl);
        if($res['code'] != 0){
            CommonHelper::printMsg('认证流程创建失败');
        }

        $flowId = $res['data']['flowId'];
        CommonHelper::printMsg('认证流程id:'.$flowId);
        break;
    case 2://查询银行打款信息
        $flowId = 'xxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****查询银行支行信息*****');
        $keyWord = '中国银行西安高新路支行';
        $res = $organAuthHelper->getSubBranch($flowId,$keyWord);
        CommonHelper::printMsg($res);
        break;
    case 3://发起随机金额打款认证
        $flowId = 'xxx'; //case =1.txt 时获取的flowId
        CommonHelper::printMsg('*****发起随机金额打款认证*****');
        $bank = '招商银行';
        $province = '浙江省';
        $city = '杭州市';
        $subbranch = '招商银行股份有限公司杭州高新支行'; // case =2 时获取的支行名称
        $cardNo = 'xx';
        $cnapsCode = 'xx'; // case =2 时获取的联行号
        $contextId = 'xxx'; //业务方业务上下文id，如果不填则取发起实名时的contextId
        $notifyUrl = 'xxxx'; //打款成功后异步通知地址,收到成功通知不代表真实打款成功，只表示银行已受理该笔业务

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








