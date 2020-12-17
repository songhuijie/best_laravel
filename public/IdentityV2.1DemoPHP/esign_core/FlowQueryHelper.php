<?php

namespace tech\esign_core;
use tech\esign_utils\HttpHelper;

class FlowQueryHelper extends BaseHelper
{

    /**
     * 查询认证信息，查询实名认证流程的流程详情，含认证状态、认证主体信息等
     * @param string flowId  流程id
     * @return mixed
     */
    public function queryAuthDetail($flowId){
        $result = HttpHelper::doGet($this->openApiUrl.sprintf($this->urlMap['API_QUERY_DETAIL'],$flowId),[]);
        return json_decode($result,true);
    }




}
