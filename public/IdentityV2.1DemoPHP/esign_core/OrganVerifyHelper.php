<?php

namespace tech\esign_core;
use tech\esign_utils\HttpHelper;

class OrganVerifyHelper extends BaseHelper
{

    /**
     * 企业2要素信息比对,对工商体系中的企业名称和证件号的一致性进行检查校验
     * @param $name string 企业名称
     * @param $orgCode string 企业证件号,支持15位工商注册号或统一社会信用代码
     * @return mixed
     */
   public function enterpriseBase($name,$orgCode){
       $data = [
           'orgCode'=>$orgCode,
           'name'=>$name
       ];
       $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['ORGANIZATION_ENTERPRISE_BASE'],$data);
       return json_decode($result,true);
   }



    /**
     * 企业3要素信息比对,对工商体系中的企业名称、证件号和法定代表人姓名的一致性进行检查校验
     * @param $name string 姓名
     * @param $orgCode string 企业证件号,支持15位工商注册号或统一社会信用代码
     * @param $legalRepName string 手机号（中国大陆3大运营商）
     * @return mixed
     */
    public function enterpriseBureau3Factors($name,$orgCode,$legalRepName){
        $data = [
            'orgCode'=>$orgCode,
            'name'=>$name,
            'legalRepName'=>$legalRepName
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['ORGANIZATION_ENTERPRISE_BUREAU3FACTORS'],$data);
        return json_decode($result,true);
    }


    /**
     * 企业4要素信息比对,对工商体系中的企业名称、证件号和法定代表人信息的一致性进行检查校验
     * @param $name string 姓名
     * @param $orgCode string 企业证件号,支持15位工商注册号或统一社会信用代码
     * @param $legalRepName string 手机号（中国大陆3大运营商）
     * @param $legalRepCertNo string 企业法定代表人身份证号
     * @return mixed
     */
    public function enterpriseBureau4Factors($name,$orgCode,$legalRepName,$legalRepCertNo){
        $data = [
            'orgCode'=>$orgCode,
            'name'=>$name,
            'legalRepName'=>$legalRepName,
            'legalRepCertNo'=>$legalRepCertNo
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['ORGANIZATION_ENTERPRISE_BUREAU4FACTORS'],$data);
        return json_decode($result,true);
    }


    /**
     * 律所3要素信息比对,对律所注册信息正确性进行检查校验
     * @param $name string 律所名称
     * @param $codeUSC string 律所统一社会信用代码号
     * @param $legalRepName string 律所法定代表人姓名
     * @return mixed
     */
    public function lawFirm($name,$codeUSC,$legalRepName){
        $data = [
            'name'=>$name,
            'codeUSC'=>$codeUSC,
            'legalRepName'=>$legalRepName,
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['ORGANIZATION_LAWFIRM'],$data);
        return json_decode($result,true);
    }

    /**
     * 组织机构3要素信息比对,对社会组织的名称、证件号和法人三要素进行一致性比对
     * @param $name string 组织机构名称
     * @param $orgCode string 组织证件号:工商企业支持15位工商注册号或统一社会信用代码,非工商组织仅支持统一社会信用代码校验
     * @param $legalRepName string 组织法定代表人姓名
     * @return mixed
     */
    public function verify($name,$orgCode,$legalRepName){
        $data = [
            'name'=>$name,
            'orgCode'=>$orgCode,
            'legalRepName'=>$legalRepName,
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['ORGANIZATION_VERIFY'],$data);
        return json_decode($result,true);
    }

    /**
     * 非工商组织3要素信息比对
     * @param $name string 组织机构名称
     * @param $codeUSC string 社会组织统一社会信用代码证
     * @param $legalRepName string 社会组织法定代表人名称
     * @return mixed
     */
    public function social($name,$codeUSC,$legalRepName){
        $data = [
            'name'=>$name,
            'codeUSC'=>$codeUSC,
            'legalRepName'=>$legalRepName,
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['ORGANIZATION_SOCIAL'],$data);
        return json_decode($result,true);
    }




}
