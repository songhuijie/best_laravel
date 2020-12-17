<?php

namespace tech\esign_core;

use tech\esign_utils\HttpHelper;

class OrganAuthHelper extends BaseHelper
{


    /**
     * 获取企业实名认证地址（电子签名场景专用）
     * @param $accountId string 	待认证e签宝企业账号Id
     * @param $agentAccountId string 经办人账号Id
     * @param $contextInfo array 业务方交互上下文信息
     * @param $orgEntity array 组织机构基本信息
     * @param $repeatIdentity bool 是否允许重复实名，默认允许
     * @return mixed
     */
   public function web($accountId,$agentAccountId,$contextInfo,$orgEntity,$repeatIdentity){
       $data = [
           'agentAccountId'=>$agentAccountId,
           'contextInfo'=>$contextInfo,
           'orgEntity'=>$orgEntity,
           'repeatIdentity'=>$repeatIdentity
       ];

       foreach ($data as $k=>$v){
           if(empty($v) && $v !== false){
               unset($data[$k]);
           }
       }

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_WEB'],$accountId),$data);
       return json_decode($result,true);
   }

    /**
     * 发起企业实名认证4要素校验（电子签名场景专用）
     * @param $accountId string 待认证e签宝企业账号Id
     * @param $agentAccountId string 代理人账号Id,要求经办人证账号id已完成个人实名认证
     * @param $repetition bool 是否允许账号重复实名（默认允许重复实名)
     * @param $contextId string 对接方业务上下文id，将在异步通知及跳转时携带返回对接方
     * @param $notifyUrl string 认证结束后异步通知地址,详见"异步通知"章节说明
     * @return mixed
     */
   public function fourFactors($accountId,$agentAccountId,$repetition,$contextId,$notifyUrl){
        $data = [
            'accountId'=>$accountId,
            'agentAccountId'=>$agentAccountId,
            'repetition'=>$repetition,
            'contextId'=>$contextId,
            'notifyUrl'=>$notifyUrl
        ];

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_FOUR_FACTORS'],$accountId),$data);
       return json_decode($result,true);
   }


    /**
     * 发起企业核身认证4要素
     * @param $name string 组织机构证件（如营业执照）上的组织机构名称
     * @param $orgCode string 组织机构证件号,支持统一社会信用代码号和工商注册号（部分个体工商户）
     * @param $legalRepName string 法定代表人名称
     * @param $legalRepIdNo string 法定代表人证件号
     * @param $contextId string 对接方业务上下文id，将在异步通知及跳转时携带返回对接方
     * @param $notifyUrl string 认证结束后异步通知地址
     * @return mixed
     */
   public function fourFactorsNoAccount($name,$orgCode,$legalRepName,$legalRepIdNo,$contextId,$notifyUrl){
       $data = [
           'name'=>$name,
           'orgCode'=>$orgCode,
           'legalRepName'=>$legalRepName,
           'legalRepIdNo'=>$legalRepIdNo,
           'contextId'=>$contextId,
           'notifyUrl'=>$notifyUrl
       ];

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_FOUR_FACTORS_NO_ACCOUNT']),$data);
       return json_decode($result,true);
   }

    /**
     * 查询打款银行信息,用于获取收款银行的银行联行号等信息，确保能正常打款，根据开户行名称查询，并返回最多20条与之匹配的的开户行/支行信息，用于获取收款银行，联行号信息
     * @param $flowId string 认证流程Id
     * @param $keyWord string 银行名称搜索关键字，建议输入完整的银行名称
     * @return mixed
     */
   public function getSubBranch($flowId,$keyWord){

       $result = HttpHelper::doGet($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_GET_SUBBRANCH'],$flowId,urlencode($keyWord)),[]);
       return json_decode($result,true);
   }


    /**
     * 发起随机金额打款认证
     * @param $flowId string 认证流程Id
     * @param $bank string 对公账号开户行总行名称
     * @param $province string 对公账号开户行所在省份名称
     * @param $city string 对公账号开户行所在城市名称
     * @param $subbranch string 对公账号开户行支行名称全称
     * @param $cardNo string 银行卡号信息
     * @param $cnapsCode string 联行号（名词解释） 可通过 查询打款银行信息 获取，或第三方数据库获取
     * @param $contextId string 业务方业务上下文id，如果不填则取发起实名时的contextId
     * @param $notifyUrl string 打款成功后异步通知地址
     * @return mixed
     */
    public function transferRandomAmount($flowId,$bank,$province,$city,$subbranch,$cardNo,$cnapsCode,$contextId,$notifyUrl){
        $data = [
            'bank'=>$bank,
            'province'=>$province,
            'city'=>$city,
            'subbranch'=>$subbranch,
            'cardNo'=>$cardNo,
            'cnapsCode'=>$cnapsCode,
            'contextId'=>$contextId,
            'notifyUrl'=>$notifyUrl
        ];

        foreach ($data as $k=>$v){
            if(empty($v) && $v !== false){
                unset($data[$k]);
            }
        }



        $result = HttpHelper::doPutNew($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_GET_TRANSFER_RANDOM_AMOUNT'],$flowId),$data);
        return json_decode($result,true);
    }

    /**
     * 随机金额校验,回填对公账号打款随机金额进行校验
     * @param $flowId string 认证流程Id
     * @param $amount double 对公账号收到的随机金额数值（0.01-0.99之间）
     * @return mixed
     */
    public function verifyRandomAmount($flowId,$amount){
        $data = [
            'amount'=>$amount,

        ];

        $result = HttpHelper::doPut($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_VERIFY_RANDOM_AMOUNT'],$flowId),$data);
        return json_decode($result,true);
    }

    /**
     * 发起授权签署实名认证（电子签名场景专用）
     * @param $flowId string 认证流程Id
     * @param $mobileNo string 法定代表人手机号，用于签署电子授权书
     * @param $legalRepIdNo string 法定代表人身份证号,如果企业账号中已设置，可为空；否则需传入
     * @return mixed
     */
    public function legalRepSignAuth($flowId,$mobileNo,$legalRepIdNo){
        $data = [
            'mobileNo'=>$mobileNo,
            'legalRepIdNo'=>$legalRepIdNo,
        ];

        $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_LEGAL_REP_SIGN_AUTH'],$flowId),$data);
        return json_decode($result,true);
    }

    /**
     * 查询授权书签署状态
     * @param $flowId string 认证流程Id
     * @return mixed
     */
    public function legalRepSignResult($flowId){
        $result = HttpHelper::doGet($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_LEGAL_REP_SIGN_RESULT'],$flowId),[]);
        return json_decode($result,true);
    }


    /**
     * 获取组织机构核身地址
     * @param $authType string 获取指定类型核身认证类型
     * @param $contextInfo array 业务方交互上下文信息
     * @param $orgEntity array 组织机构基本信息
     * @param $repeatIdentity bool 是否允许重复实名，默认允许
     * @return mixed
     */
    public function webNoAccount($authType,$contextInfo,$orgEntity,$repeatIdentity){
        $data = [
            'authType'=>$authType,
            'contextInfo'=>$contextInfo,
            'orgEntity'=>$orgEntity,
            'repeatIdentity'=>$repeatIdentity
        ];

        foreach ($data as $k=>$v){
            if(empty($v) && $v !== false){
                unset($data[$k]);
            }
        }

        $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['ORGANIZATION_API_WEB_NO_ACCOUNT']),$data);
        return json_decode($result,true);
    }

}
