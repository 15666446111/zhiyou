<?php

require_once '../AopClient.php';
require_once '../AopCertification.php';
require_once '../request/AlipayTradeQueryRequest.php';
require_once '../request/AlipayTradeWapPayRequest.php';
require_once '../request/AlipayTradeAppPayRequest.php';


/**
 * 证书类型AopClient功能方法使用测试
 * 1、execute 调用示例
 * 2、sdkExecute 调用示例
 * 3、pageExecute 调用示例
 */


//1、execute 使用
$aop = new AopClient ();

$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2016101400681518';
$aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEAsN/gNIn1d0Iasoy3Yb/kW89UcnWJx/dsYAlIPWnBoU1lk47Jp0TPT/oQ2XsQYvUSdky9rmh9jZoL+FwxUDBDryoupTASk5+aNE31tgxt1oOnCaEuXMzdyXmVnxBZo6uqP+TRIIJcwr+qZKwiB4v0Shuszk1T6+rOlLc2/XOAnXhEY6RkiamTrs5gM4Vebhu2E5acmNu1azJ5/G/Ywl8LZlQs8Td+FRNE+B/mWe1EdKexuKGnyH2z0wlzSQi0GTUxA1W1XRdSVl0mdQ7KmTLi4A1/0o3XP9tu3OHaxD3XkclBNPaVZS2gJYpFdFzPD/1SBMA5dq9d/uDpkCjgmq3UbQIDAQABAoIBAETo5BP0D9NHyNj5Y9TMiy6NxyvUyTpEFlAQLbnngw3R9poXjk8ByvcwyPHCNW8oZen7GgdiJFBPzQwU9w1z0FrlR0kKQqKLEuv/15P2obBqmGBzEHoNQvdkzDstho7yqaC9CBDvWS5yt/MC8TsZdrKMt7WkgOawE43zF5SFNH2koKu5/pcj9qmLpSD61tPd/lcaL31EJC+MYzjPP326gxh9UQxbrWhcC/oqZIyY6aEXcI43tFwyFr+UqR9v8FhKg13jgwtnujIJFzFvpDhCdxWJh2aplUmmhC4m6fduMaQS0kLEdenmUwDQCbYAj3z7L4v9h38z07w956y1TAfAPS0CgYEA3O5aHcR9d++Cu8hBTgQ4ATBlKHavhrIdPkzhzTMF2/IrvAbo+GeJLZG6J5PZCvnK5BERx0ZYhqCYHNB5izWapMhleH2LAk1DBQn/fw6KKT0kAVQ10h1Jh91bXajdj+PVHWimLigyFS0/TCNspOa4IeeFhkloYHNVBPmqsA1b5Y8CgYEAzPNDYA4WbHF/sRX+NpYjFyTHkhPMdRw8mTVj87Uv+CEBmBIsftbxd/XoyoEp/T4DN6fHrWAkSOchrBgTemuPmkC+rcJvJnqqmztTk8WL3b2n7FGtVXaC9wX9x5vFQOi7nlpbzmol/rOQBPTLVVlMmiE/wh5gBDtexpK7vk1zQEMCgYEAguf4HYs/jMEuBXYyu7dkN9AlIESy3GzNRwzm/l8tKZXktBSkFgvDDG08kBbak9ZOkbRLhHf6HEsr1mbwnbu1Vc4n0a0RoNuGyoWSDgbfdm/z1ZXADi+sUgSnDmfwYEHvFO3dCZxnkISu7L5QsaTDldNJGriEUCIGTlxg9s/zk6ECgYAsEBjqK90yT2yOnBXdkoXU0fl+NHd+riVxIwNsQAYiKKZ5FV8vnfKoQdxcvUxTv+NdMdtCMStb6SVaJIr7hOiI12anYOgYs8K+QREE8jHR6JaTvjOv7hzWExmuspTEEcVUlESsqjGlAuHxztdwcBSCBqf4iiNGhaUouB9ZGLmwlQKBgQClBALVRJFJ9m8VKBWS8cX9NRTDrWpf4D6d7f4Pt4b16x50PLyq5REgcqySItVZ4GiEahfvC/3LAoGOQUVbnbHYToml1ZH63f4008GKJkUC2KmL6plvcSNZ/+OTYIKpUWcHPPWwSfYgMDSLLbZTHeSlgFw/mNKXsx3/DyeRnI/drA==';
$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhWJZRItQzrzMBPrOGLEwrMTCJL4d5KdlnEQ2k4cxMDLDTWICZ+F/keixnG+BPJeCzW7g57oUIyD4TlBfCXuVTdAf0ZGCr+FIqQk7GXJCkeTYuyP7AnqsJVwLm/BmEn4s61Gp/qloIpMm4AjopWUaKl9EROdHKYtW6KPbak3HDLWD+RjfERz3nZI+mqHsii5XlzxMxzEPk47esEndg5Vw3N7Z9bRJCuhXDYKPmADMW8TTqtxLl6z7qzrHVXBZnyLqux3jrgR8keIVP7rZmofIUdras6KrJM4ca/h2zeaLaDIbtpjY+TdddCwuIyoEKfLDwgvMLl4U57lcd17OHJFcrQIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset = 'utf-8';
$aop->format = 'json';

$request = new AlipayTradeQueryRequest ();
$request->setBizContent("{" .
    "\"out_trade_no\":\"20150320010101001\"," .
    "\"trade_no\":\"2014112611001004680 073956707\"," .
    "\"org_pid\":\"2088101117952222\"," .
    "      \"query_options\":[" .
    "        \"TRADE_SETTE_INFO\"" .
    "      ]" .
    "  }");
$result = $aop->execute($request);
var_dump($result);


//2、sdkExecute 测试
$aop = new AopClient ();

$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2016101400681518';
$aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEAsN/gNIn1d0Iasoy3Yb/kW89UcnWJx/dsYAlIPWnBoU1lk47Jp0TPT/oQ2XsQYvUSdky9rmh9jZoL+FwxUDBDryoupTASk5+aNE31tgxt1oOnCaEuXMzdyXmVnxBZo6uqP+TRIIJcwr+qZKwiB4v0Shuszk1T6+rOlLc2/XOAnXhEY6RkiamTrs5gM4Vebhu2E5acmNu1azJ5/G/Ywl8LZlQs8Td+FRNE+B/mWe1EdKexuKGnyH2z0wlzSQi0GTUxA1W1XRdSVl0mdQ7KmTLi4A1/0o3XP9tu3OHaxD3XkclBNPaVZS2gJYpFdFzPD/1SBMA5dq9d/uDpkCjgmq3UbQIDAQABAoIBAETo5BP0D9NHyNj5Y9TMiy6NxyvUyTpEFlAQLbnngw3R9poXjk8ByvcwyPHCNW8oZen7GgdiJFBPzQwU9w1z0FrlR0kKQqKLEuv/15P2obBqmGBzEHoNQvdkzDstho7yqaC9CBDvWS5yt/MC8TsZdrKMt7WkgOawE43zF5SFNH2koKu5/pcj9qmLpSD61tPd/lcaL31EJC+MYzjPP326gxh9UQxbrWhcC/oqZIyY6aEXcI43tFwyFr+UqR9v8FhKg13jgwtnujIJFzFvpDhCdxWJh2aplUmmhC4m6fduMaQS0kLEdenmUwDQCbYAj3z7L4v9h38z07w956y1TAfAPS0CgYEA3O5aHcR9d++Cu8hBTgQ4ATBlKHavhrIdPkzhzTMF2/IrvAbo+GeJLZG6J5PZCvnK5BERx0ZYhqCYHNB5izWapMhleH2LAk1DBQn/fw6KKT0kAVQ10h1Jh91bXajdj+PVHWimLigyFS0/TCNspOa4IeeFhkloYHNVBPmqsA1b5Y8CgYEAzPNDYA4WbHF/sRX+NpYjFyTHkhPMdRw8mTVj87Uv+CEBmBIsftbxd/XoyoEp/T4DN6fHrWAkSOchrBgTemuPmkC+rcJvJnqqmztTk8WL3b2n7FGtVXaC9wX9x5vFQOi7nlpbzmol/rOQBPTLVVlMmiE/wh5gBDtexpK7vk1zQEMCgYEAguf4HYs/jMEuBXYyu7dkN9AlIESy3GzNRwzm/l8tKZXktBSkFgvDDG08kBbak9ZOkbRLhHf6HEsr1mbwnbu1Vc4n0a0RoNuGyoWSDgbfdm/z1ZXADi+sUgSnDmfwYEHvFO3dCZxnkISu7L5QsaTDldNJGriEUCIGTlxg9s/zk6ECgYAsEBjqK90yT2yOnBXdkoXU0fl+NHd+riVxIwNsQAYiKKZ5FV8vnfKoQdxcvUxTv+NdMdtCMStb6SVaJIr7hOiI12anYOgYs8K+QREE8jHR6JaTvjOv7hzWExmuspTEEcVUlESsqjGlAuHxztdwcBSCBqf4iiNGhaUouB9ZGLmwlQKBgQClBALVRJFJ9m8VKBWS8cX9NRTDrWpf4D6d7f4Pt4b16x50PLyq5REgcqySItVZ4GiEahfvC/3LAoGOQUVbnbHYToml1ZH63f4008GKJkUC2KmL6plvcSNZ/+OTYIKpUWcHPPWwSfYgMDSLLbZTHeSlgFw/mNKXsx3/DyeRnI/drA==';
$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhWJZRItQzrzMBPrOGLEwrMTCJL4d5KdlnEQ2k4cxMDLDTWICZ+F/keixnG+BPJeCzW7g57oUIyD4TlBfCXuVTdAf0ZGCr+FIqQk7GXJCkeTYuyP7AnqsJVwLm/BmEn4s61Gp/qloIpMm4AjopWUaKl9EROdHKYtW6KPbak3HDLWD+RjfERz3nZI+mqHsii5XlzxMxzEPk47esEndg5Vw3N7Z9bRJCuhXDYKPmADMW8TTqtxLl6z7qzrHVXBZnyLqux3jrgR8keIVP7rZmofIUdras6KrJM4ca/h2zeaLaDIbtpjY+TdddCwuIyoEKfLDwgvMLl4U57lcd17OHJFcrQIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset = 'utf-8';
$aop->format = 'json';

$request = new AlipayTradeAppPayRequest ();
$request->setBizContent("{" .
    "\"timeout_express\":\"90m\"," .
    "\"total_amount\":\"9.00\"," .
    "\"product_code\":\"QUICK_MSECURITY_PAY\"," .
    "\"body\":\"Iphone6 16G\"," .
    "\"subject\":\"大乐透\"," .
    "\"out_trade_no\":\"70501111111S001111119\"," .
    "\"time_expire\":\"2016-12-31 10:05\"," .
    "\"goods_type\":\"0\"," .
    "\"promo_params\":\"{\\\"storeIdType\\\":\\\"1\\\"}\"," .
    "\"passback_params\":\"merchantBizType%3d3C%26merchantBizNo%3d2016010101111\"," .
    "\"extend_params\":{" .
    "\"sys_service_provider_id\":\"2088511833207846\"," .
    "\"hb_fq_num\":\"3\"," .
    "\"hb_fq_seller_percent\":\"100\"," .
    "\"industry_reflux_info\":\"{\\\\\\\"scene_code\\\\\\\":\\\\\\\"metro_tradeorder\\\\\\\",\\\\\\\"channel\\\\\\\":\\\\\\\"xxxx\\\\\\\",\\\\\\\"scene_data\\\\\\\":{\\\\\\\"asset_name\\\\\\\":\\\\\\\"ALIPAY\\\\\\\"}}\"," .
    "\"card_type\":\"S0JP0000\"" .
    "    }," .
    "\"merchant_order_no\":\"20161008001\"," .
    "\"enable_pay_channels\":\"pcredit,moneyFund,debitCardExpress\"," .
    "\"store_id\":\"NJ_001\"," .
    "\"specified_channel\":\"pcredit\"," .
    "\"disable_pay_channels\":\"pcredit,moneyFund,debitCardExpress\"," .
    "      \"goods_detail\":[{" .
    "        \"goods_id\":\"apple-01\"," .
    "\"alipay_goods_id\":\"20010001\"," .
    "\"goods_name\":\"ipad\"," .
    "\"quantity\":1," .
    "\"price\":2000," .
    "\"goods_category\":\"34543238\"," .
    "\"categories_tree\":\"124868003|126232002|126252004\"," .
    "\"body\":\"特价手机\"," .
    "\"show_url\":\"http://www.alipay.com/xxx.jpg\"" .
    "        }]," .
    "\"ext_user_info\":{" .
    "\"name\":\"李明\"," .
    "\"mobile\":\"16587658765\"," .
    "\"cert_type\":\"IDENTITY_CARD\"," .
    "\"cert_no\":\"362334768769238881\"," .
    "\"min_age\":\"18\"," .
    "\"fix_buyer\":\"F\"," .
    "\"need_check_info\":\"F\"" .
    "    }," .
    "\"business_params\":\"{\\\"data\\\":\\\"123\\\"}\"," .
    "\"agreement_sign_params\":{" .
    "\"personal_product_code\":\"CYCLE_PAY_AUTH_P\"," .
    "\"sign_scene\":\"INDUSTRY|DIGITAL_MEDIA\"," .
    "\"external_agreement_no\":\"test20190701\"," .
    "\"external_logon_id\":\"13852852877\"," .
    "\"access_params\":{" .
    "\"channel\":\"ALIPAYAPP\"" .
    "      }," .
    "\"sub_merchant\":{" .
    "\"sub_merchant_id\":\"2088123412341234\"," .
    "\"sub_merchant_name\":\"滴滴出行\"," .
    "\"sub_merchant_service_name\":\"滴滴出行免密支付\"," .
    "\"sub_merchant_service_description\":\"免密付车费，单次最高500\"" .
    "      }," .
    "\"period_rule_params\":{" .
    "\"period_type\":\"DAY\"," .
    "\"period\":3," .
    "\"execute_time\":\"2019-01-23\"," .
    "\"single_amount\":10.99," .
    "\"total_amount\":600," .
    "\"total_payments\":12" .
    "      }" .
    "    }" .
    "  }");
$result = $aop->sdkExecute($request);

$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
echo $responseNode;
$resultCode = $result->$responseNode->code;
if (!empty($resultCode) && $resultCode == 10000) {
    echo "成功";
} else {
    echo "失败";
}


//3、pageExecute 测试
$aop = new AopClient ();

$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2016101400681518';
$aop->rsaPrivateKey = 'MIIEpAIBAAKCAQEAsN/gNIn1d0Iasoy3Yb/kW89UcnWJx/dsYAlIPWnBoU1lk47Jp0TPT/oQ2XsQYvUSdky9rmh9jZoL+FwxUDBDryoupTASk5+aNE31tgxt1oOnCaEuXMzdyXmVnxBZo6uqP+TRIIJcwr+qZKwiB4v0Shuszk1T6+rOlLc2/XOAnXhEY6RkiamTrs5gM4Vebhu2E5acmNu1azJ5/G/Ywl8LZlQs8Td+FRNE+B/mWe1EdKexuKGnyH2z0wlzSQi0GTUxA1W1XRdSVl0mdQ7KmTLi4A1/0o3XP9tu3OHaxD3XkclBNPaVZS2gJYpFdFzPD/1SBMA5dq9d/uDpkCjgmq3UbQIDAQABAoIBAETo5BP0D9NHyNj5Y9TMiy6NxyvUyTpEFlAQLbnngw3R9poXjk8ByvcwyPHCNW8oZen7GgdiJFBPzQwU9w1z0FrlR0kKQqKLEuv/15P2obBqmGBzEHoNQvdkzDstho7yqaC9CBDvWS5yt/MC8TsZdrKMt7WkgOawE43zF5SFNH2koKu5/pcj9qmLpSD61tPd/lcaL31EJC+MYzjPP326gxh9UQxbrWhcC/oqZIyY6aEXcI43tFwyFr+UqR9v8FhKg13jgwtnujIJFzFvpDhCdxWJh2aplUmmhC4m6fduMaQS0kLEdenmUwDQCbYAj3z7L4v9h38z07w956y1TAfAPS0CgYEA3O5aHcR9d++Cu8hBTgQ4ATBlKHavhrIdPkzhzTMF2/IrvAbo+GeJLZG6J5PZCvnK5BERx0ZYhqCYHNB5izWapMhleH2LAk1DBQn/fw6KKT0kAVQ10h1Jh91bXajdj+PVHWimLigyFS0/TCNspOa4IeeFhkloYHNVBPmqsA1b5Y8CgYEAzPNDYA4WbHF/sRX+NpYjFyTHkhPMdRw8mTVj87Uv+CEBmBIsftbxd/XoyoEp/T4DN6fHrWAkSOchrBgTemuPmkC+rcJvJnqqmztTk8WL3b2n7FGtVXaC9wX9x5vFQOi7nlpbzmol/rOQBPTLVVlMmiE/wh5gBDtexpK7vk1zQEMCgYEAguf4HYs/jMEuBXYyu7dkN9AlIESy3GzNRwzm/l8tKZXktBSkFgvDDG08kBbak9ZOkbRLhHf6HEsr1mbwnbu1Vc4n0a0RoNuGyoWSDgbfdm/z1ZXADi+sUgSnDmfwYEHvFO3dCZxnkISu7L5QsaTDldNJGriEUCIGTlxg9s/zk6ECgYAsEBjqK90yT2yOnBXdkoXU0fl+NHd+riVxIwNsQAYiKKZ5FV8vnfKoQdxcvUxTv+NdMdtCMStb6SVaJIr7hOiI12anYOgYs8K+QREE8jHR6JaTvjOv7hzWExmuspTEEcVUlESsqjGlAuHxztdwcBSCBqf4iiNGhaUouB9ZGLmwlQKBgQClBALVRJFJ9m8VKBWS8cX9NRTDrWpf4D6d7f4Pt4b16x50PLyq5REgcqySItVZ4GiEahfvC/3LAoGOQUVbnbHYToml1ZH63f4008GKJkUC2KmL6plvcSNZ/+OTYIKpUWcHPPWwSfYgMDSLLbZTHeSlgFw/mNKXsx3/DyeRnI/drA==';
$aop->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhWJZRItQzrzMBPrOGLEwrMTCJL4d5KdlnEQ2k4cxMDLDTWICZ+F/keixnG+BPJeCzW7g57oUIyD4TlBfCXuVTdAf0ZGCr+FIqQk7GXJCkeTYuyP7AnqsJVwLm/BmEn4s61Gp/qloIpMm4AjopWUaKl9EROdHKYtW6KPbak3HDLWD+RjfERz3nZI+mqHsii5XlzxMxzEPk47esEndg5Vw3N7Z9bRJCuhXDYKPmADMW8TTqtxLl6z7qzrHVXBZnyLqux3jrgR8keIVP7rZmofIUdras6KrJM4ca/h2zeaLaDIbtpjY+TdddCwuIyoEKfLDwgvMLl4U57lcd17OHJFcrQIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA2';
$aop->postCharset = 'utf-8';
$aop->format = 'json';

$request = new AlipayTradeWapPayRequest ();
$request->setBizContent("{" .
    "    \"body\":\"对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。\"," .
    "    \"subject\":\"测试\"," .
    "    \"out_trade_no\":\"70501111111S001111119\"," .
    "    \"timeout_express\":\"90m\"," .
    "    \"total_amount\":9.00," .
    "    \"product_code\":\"QUICK_WAP_WAY\"" .
    "  }");
$result = $aop->pageExecute($request);
echo $result;


