<?php

namespace tech\esign_core;

use tech\esign_utils\HttpHelper;

class IndividualVerifyHelper extends BaseHelper
{

    /**
     * 个人二要素,对个人姓名和身份证号的一致性进行检查校验,仅支持中国大陆信息核验
     * @param $name string 姓名
     * @param $idNo string 身份证号（大陆二代身份证）
     * @return mixed
     */
   public function individualBase($name,$idNo){
       $data = [
           'idNo'=>$idNo,
           'name'=>$name
       ];
       $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['INDIVIDUAL_BASE'],$data);
       return json_decode($result,true);
   }



    /**
     * 个人运营商3要素信息比对,对个人姓名、身份证号和手机号码的一致性进行检查校验
     * @param $name string 姓名
     * @param $idNo string 身份证号（大陆二代身份证）
     * @param $mobileNo string 手机号（中国大陆3大运营商）
     * @return mixed
     */
    public function individualTelecom3Factors($name,$idNo,$mobileNo){
        $data = [
            'idNo'=>$idNo,
            'name'=>$name,
            'mobileNo'=>$mobileNo
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['INDIVIDUAL_TELECOM3FACTORS'],$data);
        return json_decode($result,true);
    }


    /**
     * 个人银行卡3要素信息比对,对个人姓名、身份证号、银行卡号的一致性进行检查校验
     * @param $name string 姓名
     * @param $idNo string 身份证号（大陆二代身份证）
     * @param $cardNo string 银行卡号（银联卡号）
     * @return mixed
     */
    public function individualBank3Factors($name,$idNo,$cardNo){
        $data = [
            'idNo'=>$idNo,
            'name'=>$name,
            'cardNo'=>$cardNo
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['INDIVIDUAL_BANK3FACTORS'],$data);
        return json_decode($result,true);
    }

    /**
     * 个人银行卡4要素信息比对,对个人姓名、身份证号、银行卡号和银行预留手机号的一致性进行检查校验
     * @param $name string 姓名
     * @param $idNo string 身份证号（大陆二代身份证）
     * @param $cardNo string 银行卡号（银联卡号）
     * @param $mobileNo string 银行预留手机号（非短信通知手机号）
     * @return mixed
     */
    public function individualBank4Factors($name,$idNo,$cardNo,$mobileNo){
        $data = [
            'idNo'=>$idNo,
            'name'=>$name,
            'cardNo'=>$cardNo,
            'mobileNo'=>$mobileNo
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['INDIVIDUAL_BANK4FACTORS'],$data);
        return json_decode($result,true);
    }





}
