<?php

namespace tech\esign_core;
use tech\esign_utils\HttpHelper;

class AccountHelper extends BaseHelper
{

    public function createByThirdPartyUserId($thirdPartyUserId,$name,$idType,$idNumber,$mobile,$email){
        $data = [
            'thirdPartyUserId'=>$thirdPartyUserId,
            'name'=>$name,
            'idType'=>$idType,
            'idNumber'=>$idNumber,
            'mobile'=>$mobile,
            'email'=>$email
        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['CREATE_ACCOUNT_BY_THIRD_USER_ID'],$data);
        return json_decode($result,true);
    }


    public function createOrganByThirdPartyUserId($thirdPartyUserId,$creator,$name,$idType,$idNumber,$orgLegalIdNumber,$orgLegalName){
        $data = [
            'thirdPartyUserId'=>$thirdPartyUserId,
            'creator'=>$creator,
            'name'=>$name,
            'idType'=>$idType,
            'idNumber'=>$idNumber,
            'orgLegalIdNumber'=>$orgLegalIdNumber,
            'orgLegalName'=>$orgLegalName

        ];
        $result = HttpHelper::doPost($this->openApiUrl.$this->urlMap['CREATE_ORGAN_ACCOUNT_BY_THIRD_USER_ID'],$data);
        return json_decode($result,true);
    }

}
