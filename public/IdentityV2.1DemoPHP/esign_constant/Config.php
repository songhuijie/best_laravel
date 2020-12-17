<?php
namespace tech\esign_constant;

class Config{

public static $config = [
    'openApiUrl'=>'https://smlopenapi.esign.cn', // 模拟环境
//    'openApiUrl'=>'https://openapi.esign.cn', // 正式环境
    'appId'=>'xxxxx',
    'appSecret'=>'xxxx',
];

public static $proxyConf = [
    'ENABLE_HTTP_PROXY'=>false,
    'HTTP_PROXY_IP'=>'',
    'HTTP_PROXY_PORT'=>'',
];


public static $urlMap = [
    'TOKEN_URL'=>'/v1/oauth2/access_token', //获取鉴权token
    'CREATE_ACCOUNT_BY_THIRD_USER_ID'=>'/v1/accounts/createByThirdPartyUserId', //创建E签宝个人账号（电子签名场景专用）
    'CREATE_ORGAN_ACCOUNT_BY_THIRD_USER_ID'=>'/v1/organizations/createByThirdPartyUserId', //创建E签宝组织账号（电子签名场景专用）

    'INDIVIDUAL_BASE'=>'/v2/identity/verify/individual/base',//个人二要素信息比对
    'INDIVIDUAL_TELECOM3FACTORS'=>'/v2/identity/verify/individual/telecom3Factors', //个人运营商3要素信息比对
    'INDIVIDUAL_BANK3FACTORS'=>'/v2/identity/verify/individual/bank3Factors', //个人银行卡3要素信息比对
    'INDIVIDUAL_BANK4FACTORS'=>'/v2/identity/verify/individual/bank4Factors', //个人银行卡4要素信息比对

    'ORGANIZATION_ENTERPRISE_BASE'=>'/v2/identity/verify/organization/enterprise/base', //企业2要素信息比对
    'ORGANIZATION_ENTERPRISE_BUREAU3FACTORS'=>'/v2/identity/verify/organization/enterprise/bureau3Factors', //企业3要素信息比对
    'ORGANIZATION_ENTERPRISE_BUREAU4FACTORS'=>'/v2/identity/verify/organization/enterprise/bureau4Factors', //企业4要素信息比对
    'ORGANIZATION_LAWFIRM'=>'/v2/identity/verify/organization/lawFirm', //律所3要素信息比对
    'ORGANIZATION_VERIFY'=>'/v2/identity/verify/organization/verify', //组织机构3要素信息比对
    'ORGANIZATION_SOCIAL'=>'/v2/identity/verify/organization/social', //非工商组织3要素信息比对

    'INDIVIDUAL_API_TELECOM3FACTOR'=>'/v2/identity/auth/api/individual/%s/telecom3Factors',  //发起运营商3要素实名认证（电子签名场景专用）
    'INDIVIDUAL_API_TELECOM3FACTOR_CODE'=>'/v2/identity/auth/pub/individual/%s/telecom3Factors', //手机号验证码校验
    'API_QUERY_DETAIL'=>'/v2/identity/auth/api/common/%s/detail', //查询认证信息
    'INDIVIDUAL_API_BANKCARD4FACTORS'=>'/v2/identity/auth/api/individual/%s/bankCard4Factors',//发起银行4要素实名认证（电子签名场景专用）
    'INDIVIDUAL_API_BANKCARD4FACTORS_CODE'=>'/v2/identity/auth/pub/individual/%s/bankCard4Factors', //银行预留手机号验证码校验
    'INDIVIDUAL_API_FACE'=>'/v2/identity/auth/api/individual/%s/face', //发起个人刷脸实名（电子签名场景专用）
    'INDIVIDUAL_API_WEB'=>'/v2/identity/auth/web/%s/indivIdentityUrl', //获取个人实名认证地址（电子签名场景专用）

    'INDIVIDUAL_API_FACE_QUERY'=>'/v2/identity/auth/pub/individual/%s/face', //查询个人刷脸状态

    'ORGANIZATION_API_WEB'=>'/v2/identity/auth/web/%s/orgIdentityUrl', //获取组织机构实名认证地址（电子签名场景专用）
    'ORGANIZATION_API_FOUR_FACTORS'=>'/v2/identity/auth/api/organization/enterprise/%s/fourFactors', //发起企业实名认证4要素校验（电子签名场景专用）
    'ORGANIZATION_API_GET_SUBBRANCH'=>'/v2/identity/auth/pub/organization/%s/subbranch?keyWord=%s', //查询打款银行信息
    'ORGANIZATION_API_GET_TRANSFER_RANDOM_AMOUNT'=>'/v2/identity/auth/pub/organization/%s/transferRandomAmount', //发起随机金额打款认证
    'ORGANIZATION_API_VERIFY_RANDOM_AMOUNT'=>'/v2/identity/auth/pub/organization/%s/verifyRandomAmount', //随机金额校验

    'ORGANIZATION_API_FOUR_FACTORS_NO_ACCOUNT'=>'/v2/identity/auth/api/organization/enterprise/fourFactors', //发起企业核身认证4要素
    'ORGANIZATION_API_LEGAL_REP_SIGN_AUTH'=>'/v2/identity/auth/api/organization/%s/legalRepSignAuth', //发起授权签署实名认证（电子签名场景专用）
    'ORGANIZATION_API_LEGAL_REP_SIGN_RESULT'=>'/v2/identity/auth/pub/organization/%s/legalRepSignResult', //查询授权书签署状态

    'INDIVIDUAL_API_WEB_NO_ACCOUNT'=>'/v2/identity/auth/web/indivAuthUrl', //获取个人核身认证地址
    'INDIVIDUAL_API_FACE_NO_ACCOUNT'=>'/v2/identity/auth/api/individual/face', //发起个人刷脸核身

    'ORGANIZATION_API_WEB_NO_ACCOUNT'=>'/v2/identity/auth/web/orgAuthUrl', //获取组织机构核身认证地址
    'INDIVIDUAL_API_BANKCARD4FACTORS_NO_ACCOUNT'=>'/v2/identity/auth/api/individual/bankCard4Factors', //发起银行卡4要素核身认证
    'INDIVIDUAL_API_TELECOM3FACTOR_NO_ACCOUNT'=>'/v2/identity/auth/api/individual/telecom3Factors', //发起运营商3要素核身

];

}

