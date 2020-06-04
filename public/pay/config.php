<?php

// +----------------------------------------------------------------------
// | pay-php-sdk
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/pay-php-sdk
// +----------------------------------------------------------------------

return [
    // 微信支付参数
    'wechat' => [
        // 沙箱模式
        'debug'      => false,
        // 应用ID
        'app_id'     => 'wx06a50e013a5c6b6c',
        // 微信支付商户号
        'mch_id'     => '1555600481',
        /*
         // 子商户公众账号ID
         'sub_appid'  => '子商户公众账号ID，需要的时候填写',
         // 子商户号
         'sub_mch_id' => '子商户号，需要的时候填写',
        */
        // 微信支付密钥
        'mch_key'    => 'OVzFt0KuzPsgbAq8LLVSSJSmg3T5k92A',
        // 微信证书 cert 文件
        'ssl_cer'    => '',
        // 微信证书 key 文件
        'ssl_key'    => '',
        // 缓存目录配置
        'cache_path' => '',
        // 支付成功通知地址
        'notify_url' => '',
        // 网页支付回跳地址
        'return_url' => '',
    ],
    // 支付宝支付参数
    'alipay' => [
        // 沙箱模式
        'debug'       => false,
        // 应用ID
        'app_id'      => '2019061465583027',
        // 支付宝公钥(1行填写)
        'public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtiLu+K+cd3FDuNNqDGKhUs8C5UUJF7m7xKGyC4zGCaKkNccGRbtI6rRMQzmPUkK4s49VOZCYzIRiSJDJhg73D2qkXbmObgw75lFyupMo9XjG6hKq6j3QGstKz8IvOP1t+b4Z7GFapZJqp6mckgiGawuKmo00SlERHeJ0FFCZtsOFVgSwxwcqWdw/AOcfpYNJgq6ahz7lzoF/al03x0dan0QnqE0DmfyJE5vxtp6w1w4ntBHCgnvsfPfjnjSOopW7PnNikliNWfhn6hECAQUOpEjtZOYKbJ+OclE1jVdNOy8V+lEsE9x29XLP/aVzEbIQLmoJ8x4YnUtr5O5dbQBEwwIDAQAB',
        // 支付宝私钥(1行填写)
        'private_key' => 'MIIEpQIBAAKCAQEAtiLu+K+cd3FDuNNqDGKhUs8C5UUJF7m7xKGyC4zGCaKkNccGRbtI6rRMQzmPUkK4s49VOZCYzIRiSJDJhg73D2qkXbmObgw75lFyupMo9XjG6hKq6j3QGstKz8IvOP1t+b4Z7GFapZJqp6mckgiGawuKmo00SlERHeJ0FFCZtsOFVgSwxwcqWdw/AOcfpYNJgq6ahz7lzoF/al03x0dan0QnqE0DmfyJE5vxtp6w1w4ntBHCgnvsfPfjnjSOopW7PnNikliNWfhn6hECAQUOpEjtZOYKbJ+OclE1jVdNOy8V+lEsE9x29XLP/aVzEbIQLmoJ8x4YnUtr5O5dbQBEwwIDAQABAoIBAQCxUUMrsvP2SLuwpYo58o+yKb1c7Z/TjEvkO7M1kWB/ouqRPGi73IA5lzwjulbws+pTTXigKUjI2x1AHk1DiTA3vw+z+2FRe/GY4bR7NaeLi4DEA1aH45PFciMsLpWAuyGlINDmE0WHqgrRncvDVC9g6YSPwGam71Nlr8BowqtThFOcz1OS+y0JqlDbomqSbTLSED0MwkC5yAl8AQ/sVoHCE/EiX0bTDCACttPyP6v+WP/tgrj7D9O6UwPhej12bIhug97Pf+7RvOJp37h0cTTi0PhgeJznw1Bgl++miOUAe8BL6GO2DYArAV+NtacnnSR0D5s9C5mXqnZV3eTpd3BxAoGBAN5I6eJWXbWPf/X3UWoG5EKljGNZHaY01wf5LDFLVDHAV0XzRs35bcNrkeAFLd8dZ7RdGvk68S2zTiT4OKHHuw5D6zF/YOMLnzCgS3STbL9oy+tRkPxJhzGrGLyu0X7XDBwkg5P/P3QT61+Q3jhR9CwXJs6erE85oUgS4v0J6HOJAoGBANHDGeXoNwYeUk751dFl0sLVVmExiw1bgvKnO04lrK+0GDz/tmctrBomLR5MtEMJtGNqqXt6Ef2LWCH31u11RgbWy/qjFYYdsBpR6YafXItSPSmt/gq4mC7s6U63tHvW7EW8+NAH7e96rqx3JC0XAlp7g9VadasG29U3m6+DYQbrAoGBAJfJkmxMLZFdfDOpvp54NkaMZWEx6V0ll/CJ5fODTOrsPKw3g5IDUUCwo9wlrT0+ByLSsSifZzGdzy7PkVxn1wPWvTP/l1lzBjFeRChGw2uxVA5MlISycO8ptqqhdcz3a/2LsY3OLrZFI+UON7Cdxe6VoJpXH7K517gvrXC8406BAoGAZ8P/97+cGKa/SNpGb8PTMxaeGI6Nnxn5+VfI+qugdXq90s8cyIXLcVQZVbfUJKWUWunU1YRZifd4rbUQ2X7+GTwAxAgRCbt99kc3IqWK/lC8ePXdiigvAGeqMoql+e0kDIui+iYyChnCdwapGrUbMO+RE+Yf9275KMzNc1GmmekCgYEA158oSqSQpBHiNKzRpybTU+TBRWh3OhIqkEbzFtyh9ot6BnFM2CUhgBHAODAuiaMM/lTvJidmrGxKFtwMpIkbx43vgTw/FJNp+46zlekEBX1kuYuD/ZSwbTdWbUkDC13M3qWpUIGO9mNd/mqv1Y9a1TMgkwjZr7E5EJVLWnwzGdk=',
        // 缓存目录配置
        'cache_path'  => '',
        // 支付成功通知地址
        'notify_url'  => '',
        // 网页支付回跳地址
        'return_url'  => '',
    ],
];