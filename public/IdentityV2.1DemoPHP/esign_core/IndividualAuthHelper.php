<?php

namespace tech\esign_core;

use tech\esign_utils\HttpHelper;

class IndividualAuthHelper extends BaseHelper
{

    /**
     * 发起运营商3要素实名认证（电子签名场景专用）,对个人运营商三要素信息进行核验，成功后向手机号发送验证码
     * @param $accountId
     * @param $mobileNo
     * @param $repetition
     * @param $contextId
     * @param $notifyUrl
     * @return mixed
     */
   public function telecom3Factors($accountId,$mobileNo,$repetition,$contextId,$notifyUrl){
       $data = [
           'mobileNo'=>$mobileNo,
           'repetition'=>$repetition,
           'contextId'=>$contextId,
           'notifyUrl'=>$notifyUrl,
       ];

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_TELECOM3FACTOR'],$accountId),$data);
       return json_decode($result,true);
   }

    /**
     * 手机号验证码校验,回填校验运营商三要素短信验证码
     * @param $flowId string 流程id
     * @param $authCode string 验证码
     * @return mixed
     */
   public function authTelecom3FactorsCode($flowId,$authCode){
       $data = [
           'authcode'=>$authCode
       ];
       $result = HttpHelper::doPut($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_TELECOM3FACTOR_CODE'],$flowId),$data);
       return json_decode($result,true);
   }


//    /**
//     * 查询认证信息，查询实名认证流程的流程详情，含认证状态、认证主体信息等
//     */
//   public function queryAuthDetail($flowId){
//       $result = HttpHelper::doGet($this->openApiUrl.sprintf($this->urlMap['API_QUERY_DETAIL'],$flowId),[]);
//       return json_decode($result,true);
//   }


    /**
     * 发起银行4要素实名认证（电子签名场景专用）,对个人银行卡四要素信息进行核验，成功后向手机号发送验证码
     * @param $accountId
     * @param $mobileNo
     * @param $cardNo
     * @param $repetition
     * @param $contextId
     * @param $notifyUrl
     * @return mixed
     */
   public function bankCard4Factors($accountId,$mobileNo,$cardNo,$repetition,$contextId,$notifyUrl){
       $data = [
           'mobileNo'=>$mobileNo,
           'bankCardNo'=>$cardNo,
           'repetition'=>$repetition,
           'contextId'=>$contextId,
           'notifyUrl'=>$notifyUrl,
       ];

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_BANKCARD4FACTORS'],$accountId),$data);
       return json_decode($result,true);
   }

    /**
     * 手机号验证码校验,回填校验银行卡四要素短信验证码
     * @param $flowId string 流程id
     * @param $authCode string 验证码
     * @return mixed
     */
   public function authBankCard4FactorsCode($flowId,$authCode){
       $data = [
           'authcode'=>$authCode
       ];
       $result = HttpHelper::doPut($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_BANKCARD4FACTORS_CODE'],$flowId),$data);
       return json_decode($result,true);
   }

    /**
     * 发起个人刷脸实名（电子签名场景专用）
     * @param $accountId string 待认证状态 ，e签宝账号id
     * @param $faceauthMode string 人脸认证方式,TENCENT腾讯微众银行认证,ZHIMACREDIT支付宝芝麻信用认证
     * @param $repetition bool 是否允许账号重复实名（默认允许重复实名)
     * @param $contextId string 对接方业务上下文id，将在异步通知及跳转时携带返回对接方
     * @param $notifyUrl string 认证结束后异步通知地址
     * @param $callbackUrl string 认证完成后业务重定向地址
     * @return mixed
     */
   public function face($accountId,$faceauthMode,$repetition,$contextId,$notifyUrl,$callbackUrl){
       $data = [
           'faceauthMode'=>$faceauthMode,
           'repetition'=>$repetition,
           'callbackUrl'=>$callbackUrl,
           'contextId'=>$contextId,
           'notifyUrl'=>$notifyUrl,
       ];

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_FACE'],$accountId),$data);
       return json_decode($result,true);
   }


    /**
     * 查询个人刷脸状态
     * @param $flowId string 发起刷脸时的流程id
     * @return mixed
     */
   public function queryFaceAuthDetail($flowId){
       $result = HttpHelper::doGet($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_FACE_QUERY'],$flowId),[]);
       return json_decode($result,true);
   }

    /**
     * 获取个人实名认证地址（电子签名场景专用）
     * @param $accountId string 待认证账号Id
     * @param array $contextInfo  指定认证类型
     * @param array $indivInfo  个人基本信息
     * @return mixed
     */
   public function web($accountId,$authType,$contextInfo,$indivInfo,$repeatIdentity){
       $data = [
           'authType'=>$authType,
           'contextInfo'=>$contextInfo,
           'indivInfo'=>$indivInfo,
           'repeatIdentity'=>$repeatIdentity
       ];

       foreach ($data as $k=>$v){
           if(empty($v) && $v !== false){
               unset($data[$k]);
           }
       }

       $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_WEB'],$accountId),$data);
       return json_decode($result,true);
   }


    public function webNoAccount($authType,$contextInfo,$indivInfo,$repeatIdentity){
        $data = [
            'authType'=>$authType,
            'contextInfo'=>$contextInfo,
            'indivInfo'=>$indivInfo,
            'repeatIdentity'=>$repeatIdentity
        ];

        foreach ($data as $k=>$v){
            if(empty($v) && $v !== false){
                unset($data[$k]);
            }
        }

        $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_WEB_NO_ACCOUNT']),$data);
        return json_decode($result,true);
    }


    /**
     * 发起个人刷脸核身
     * @param $name string 姓名
     * @param $idNo string 身份证号
     * @param $faceauthMode string 人脸认证方式,TENCENT腾讯微众银行认证,ZHIMACREDIT支付宝芝麻信用认证
     * @param $callbackUrl string 认证完成后业务重定向地址
     * @param $contextId string 对接方业务上下文id，将在异步通知及跳转时携带返回对接方
     * @param $notifyUrl string 认证结束后异步通知地址
     * @return mixed
     */
    public function faceNoAccount($name,$idNo,$faceauthMode,$contextId,$notifyUrl,$callbackUrl){
        $data = [
            'name'=>$name,
            'idNo'=>$idNo,
            'faceauthMode'=>$faceauthMode,
            'callbackUrl'=>$callbackUrl,
            'contextId'=>$contextId,
            'notifyUrl'=>$notifyUrl,
        ];

        $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_FACE_NO_ACCOUNT']),$data);
        return json_decode($result,true);
    }


    /**
     * 发起银行卡4要素核身认证,对个人银行卡四要素信息进行核验，成功后向手机号发送验证码
     * @param string $name
     * @param string $idNo
     * @param string $mobileNo
     * @param string $bankCardNo
     * @param $contextId
     * @param $notifyUrl
     * @return mixed
     */
    public function bankCard4FactorsNoAccount($name,$idNo,$mobileNo,$bankCardNo,$contextId,$notifyUrl){
        $data = [
            'name'=>$name,
            'idNo'=>$idNo,
            'mobileNo'=>$mobileNo,
            'bankCardNo'=>$bankCardNo,
            'contextId'=>$contextId,
            'notifyUrl'=>$notifyUrl,
        ];

        $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_BANKCARD4FACTORS_NO_ACCOUNT']),$data);
        return json_decode($result,true);
    }

    /**
     * 发起运营商3要素核身,对个人运营商三要素信息进行核验，成功后向手机号发送验证码
     * @param $name
     * @param $idNo
     * @param $mobileNo
     * @param $contextId
     * @param $notifyUrl
     * @return mixed
     */
    public function telecom3FactorsNoAccount($name,$idNo,$mobileNo,$contextId,$notifyUrl){
        $data = [
            'name'=>$name,
            'idNo'=>$idNo,
            'mobileNo'=>$mobileNo,
            'contextId'=>$contextId,
            'notifyUrl'=>$notifyUrl,
        ];

        $result = HttpHelper::doPost($this->openApiUrl.sprintf($this->urlMap['INDIVIDUAL_API_TELECOM3FACTOR_NO_ACCOUNT']),$data);
        return json_decode($result,true);
    }


}
