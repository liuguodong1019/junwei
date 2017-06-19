<?php
if (file_exists("data/conf/db.php")) {
    $db = include "data/conf/db.php";
} else {
    $db = array();
}
if (file_exists("data/conf/config.php")) {
    $runtime_config = include "data/conf/config.php";
} else {
    $runtime_config = array();
}

if (file_exists("data/conf/route.php")) {
    $routes = include 'data/conf/route.php';
} else {
    $routes = array();
}

$configs = array(
    "LOAD_EXT_FILE" => "extend",
    'UPLOADPATH' => 'data/upload/',
    //'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
    'SHOW_PAGE_TRACE' => false,
    'TMPL_STRIP_SPACE' => true,// 是否去除模板文件里面的html空格与换行
    'THIRD_UDER_ACCESS' => false, //第三方用户是否有全部权限，没有则需绑定本地账号
    /* 标签库 */
    'TAGLIB_BUILD_IN' => THINKCMF_CORE_TAGLIBS,
    'MODULE_ALLOW_LIST' => array('Admin', 'Portal', 'Asset', 'Api', 'User', 'Wx', 'Comment', 'Qiushi', 'Tpl', 'Topic', 'Install', 'Bug', 'Better', 'Pay', 'Cas', 'Houtai'),
    'TMPL_DETECT_THEME' => false,       // 自动侦测模板主题
    'TMPL_TEMPLATE_SUFFIX' => '.html',     // 默认模板文件后缀

    'DEFAULT_MODULE' => 'Admin',  // 默认模块
    'DEFAULT_CONTROLLER' => 'Index', // 默认控制器名称
    'DEFAULT_ACTION' => 'index', // 默认操作名称
    'DEFAULT_M_LAYER' => 'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER' => 'Controller', // 默认的控制器层名称
    'DEFAULT_FILTER' => 'htmlspecialchars', // 默认参数过滤方法 用于I函数...htmlspecialchars

    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'LANG_LIST' => 'zh-cn,en-us,zh-tw',
    'LANG_AUTO_DETECT' => true,
    'ADMIN_LANG_SWITCH_ON' => false,   // 后台开启语言包功能

    'VAR_MODULE' => 'g',     // 默认模块获取变量
    'VAR_CONTROLLER' => 'm',    // 默认控制器获取变量
    'VAR_ACTION' => 'a',    // 默认操作获取变量

    'APP_USE_NAMESPACE' => true, // 关闭应用的命名空间定义
    'APP_AUTOLOAD_LAYER' => 'Controller,Model', // 模块自动加载的类库后缀

    'SP_TMPL_PATH' => 'themes/',       // 前台模板文件根目录
    'SP_DEFAULT_THEME' => 'simplebootx',       // 前台模板文件
    'SP_TMPL_ACTION_ERROR' => 'error', // 默认错误跳转对应的模板文件,注：相对于前台模板路径
    'SP_TMPL_ACTION_SUCCESS' => 'success', // 默认成功跳转对应的模板文件,注：相对于前台模板路径
    'SP_ADMIN_STYLE' => 'flat',
    'SP_ADMIN_TMPL_PATH' => 'admin/themes/',       // 各个项目后台模板文件根目录
    'SP_ADMIN_DEFAULT_THEME' => 'simplebootx',       // 各个项目后台模板文件
    'SP_ADMIN_TMPL_ACTION_ERROR' => 'Admin/error.html', // 默认错误跳转对应的模板文件,注：相对于后台模板路径
    'SP_ADMIN_TMPL_ACTION_SUCCESS' => 'Admin/success.html', // 默认成功跳转对应的模板文件,注：相对于后台模板路径
    'TMPL_EXCEPTION_FILE' => SITE_PATH . 'public/exception.html',

    'AUTOLOAD_NAMESPACE' => array('plugins' => './plugins/'), //扩展模块列表

    'ERROR_PAGE' => '',//不要设置，否则会让404变302

    'VAR_SESSION_ID' => 'session_id',

    "UCENTER_ENABLED" => 0, //UCenter 开启1, 关闭0
    "COMMENT_NEED_CHECK" => 0, //评论是否需审核 审核1，不审核0
    "COMMENT_TIME_INTERVAL" => 60, //评论时间间隔 单位s

    /* URL设置 */
    'URL_CASE_INSENSITIVE' => true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL' => 3,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式，提供最好的用户体验和SEO支持
    'URL_PATHINFO_DEPR' => '/',    // PATHINFO模式下，各参数之间的分割符号
    'URL_HTML_SUFFIX' => '',  // URL伪静态后缀设置

    'VAR_PAGE' => "p",

    'URL_ROUTER_ON' => true,
    'URL_ROUTE_RULES' => $routes,

    /*性能优化*/
    'OUTPUT_ENCODE' => true,// 页面压缩输出

    'HTML_CACHE_ON' => false, // 开启静态缓存
    'HTML_CACHE_TIME' => 60,   // 全局静态缓存有效期（秒）
    'HTML_FILE_SUFFIX' => '.html', // 设置静态缓存文件后缀

    'TMPL_PARSE_STRING' => array(
        '__UPLOAD__' => __ROOT__ . '/data/upload/',
        '__STATICS__' => __ROOT__ . '/statics/',
        '__WEB_ROOT__' => __ROOT__
    ),

    'upload' => array(            //上传图片
        'maxSize' => 3145728,
        'savePath' => '',
        'saveName' => array('uniqid', ''),
        'exts' => array('jpg', 'gif', 'png', 'jpeg'),
        'autoSub' => false,
        'subName' => array('date', 'Ymd'),
    ),

    'status' => array(
        0 => 1,
        1 => 0,
        2 => 101,
        3 => 102,
    ),
    'msg' => array(
        0 => '调用成功',
        1 => '调用失败',
        2 => '参数错误',
        3 => '请求方式错误',
        4 => '没有更多数据',
        5 => '直播已结束',
    ),

    'alipay_config' => array(
        'app_id' => '2017031506232267', //支付宝分配给开发者的应用ID
        'notify_url' => 'http://kf.junweiedu.cn/junwei/api/alipay/notifyUrl',
        'partner' => '2088621452658674',
        'private_key' => 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCYNppGneL5uKNvPZu6NTF8y1BQMOK70y/8fcbWo7H1BFoOShbwdngZDqTlBIGuYaf0V8zFcANeYUf9GjTasf3Lh8Y5xM29ucjTRsn7vZtPfuBAqKsnHI3OOofS6X8eeNmoOrv6XjRIODY21nbY19wph9EklUJWzK3THR6GGXica8EQBpNZuelNvri01hS9gaXqurmDr/Z2gCzS5GFAYKQUxl/yEuT+hQr7Y/dLh5dodOVCn0U9gQzVSFjXSKV1RF9o939xk/ogf17ofne5MsSclMT9yvdvkI7reU2+/xy8IsA6N3NcTLZ5dvP8cqQ6mnD7dPwRxiDC9IW8srTzeqqZAgMBAAECggEAVKsy4AsdYbmCN/O2Nzs1nuxdbW30AXS1Iac5PtXpvJhCG+a8od/UaGPL95StKjoqOfHI9x6a0Rod+D1mnywZN+j7q9C8fUfl3RhobH8I8Ixr46uvIN9yRgAq43h8/I9Oy5R4Ugmq3W1fQtvDWlWgov1oqgfY0f2ix2dQPlnWzHMMGsY4lresPEr6R4ogrU1Md03UQUYmrEopBwatgeckC9JLq3ETnd0kZRVF+1h1ef8wD36MP4QqdNWEnPb1CHxshUpNQePjCf6c48eeFDezqB0vOx1CrtXFWSro1aq+TQ6h2wIzncDqjP3vhYyWodJutcX7KrFpMuNruSLzcVXobQKBgQDPmmNUZEVh4E3+9hDr8zyezZsZSEIgjSTBT/JldPL8PHijwEdgVV5J4rd8ywqhZzQ1kY8Iy7JaqlhrlC97kSpcKArUtzbfbr0et333lfig5AtcLi4En6uqy+YiBB+RTDB+S3doxlffnyLVOpWAM2WqP4Ct5w3xWe0qPcI2p/o6dwKBgQC7spbl6KM2xqaePfTKW4Nhg+3DZHGbYlblNSdV78HpBQpqVjUrTTr9q23lE2u6tkGbFx10OQH3ZOSVIpoSszkJlS/d9GjTOL62WMl9ByHX0xNKfIHO+fDXhMWyWDDmHk4MIT2RkBMwEMT+b965/NkEueKIPK24/0ggmG7QfyJ3bwKBgQCis1qZbyQ5OvOll+9XHAAsbPLa85hvrm5Z2nAcN3WfXT0nCLBnvT/yI+6trOsd56YxPWyd9hZHnC1D0pUMAI70PqWOZDBrF3y8MA5XFYAPh+mnSsHh+ckuSDKKAjVDSDTRJg/lW1zO8wb3mbxFBwLsVGxi3iw9NL78Vf55m1gurQKBgFvWO+Lt+r3YYLnYUqeYMyZJaNJLmRKQAThQ05hGoTgkUT5KQ+WV+iEX+cM1x2YputvpaW3uXrnvUbBup7gynNvFdRBCf++pOhb6Rku4a6SwECZH2TOuM1sgCaMDZ5mQhluFABzyw5CnA9wCXJXf00dutBo4pj94GJBqRP4a1oBdAoGBAKIHbQhCiUzPWs2I1fO7HPUh4GTcBI08NFPgM9whZDYlRrXhPR2WLoEVgWqldD2jmrAqzjQt8pmf3cnO/pLbr9RGjdpSjai5bFfFKHq+/Ac5G/NyTxoD4u90nchWmTszTLe2l++6p4rg5cBn3GxXETrfWi8+QeEDhjsqQ5y37Anu',
        'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyLaInmp+qGdnifvOXFXU0/66cBwdCmNjbLXUzU+MhODHTcn2y+QK1PAMgMf0pb1xmfZS2QzlswvElpSDyu/AjL+VtS+KeoCacySt5RSsqNFsynDzweFAYUYbw+rwhVgv274PnGQ+fJH+fy0hw/LMHskousFfJHj2oHfq4e/8+NzS0jxadtv/703WbVrUJUZfd0AQvFr54GnrS6uPeNH7JOsgJ8u88tdC7HbPB4fJdps+JKEwshJMkNLLSHDu4/po+Zsc7qdjVU6LAN259r3yFwkf1W/rtPYh5/kisT88S4Xyv1vH9vF3eds+y6iZUAxMIn6pfBRtBc8ZmXoi8j0LJQIDAQAB',
//        'private_key' => 'MIIEpAIBAAKCAQEA83gQBUnJcqFm7/C3nFeSAFTU/8MN0Rq6V6AfxPHFjpzRFJovX3bpsKRLGSul56hUvfC5TRECfb5mNXU6fApvsjqQOFF8WrLXj0NHfADZn+lv1DVCJ5AMvToWasCFjTFPjDd4H06LnAfeZNyWWtew7bZMBidROfQQLJISKJDH3d9xdWwYPPyvFRdR/oima6gu2wsKUGMfFQ8tM8gquZU8hEaifpY7qtlpKIFEDkEspGl5Ai9G+JaD8m4QMM6ddBl2b/rOJOI1HameYRUnaCBJZYz0GgSUMIHRDtJkKks/xa2gAFdIOw+izEVuX9gyKY6at5H740PgAvF/kZWYHyBbqQIDAQABAoIBACRnNEYGJVe9aE3B+UIoGc1w4bQoLv2v+GK3r2hcGgTbz6s77o9gkUp3Fj96NkEv+xO3VY1/WJcCi/e8QnVffhnxBbVWAbjx+qcSETMqUV/GoJDjMbQGptD4SXfGt3FICTPW62AK93bp+kesb9K/Y1X3puBYqp12r6Bqasqj8y5qOrxKfTyVScxK0RFh+wUFXVGBiTmtU/cB+J8nLf6NABfaRT+50ox+99zKmbFF09vwkqEZX+CLT5YKDnsYSny2SUC9dCWu7zMUesrYr3ZAbGflfSL8f6Pho5niNZwXNp7oAP5d36NGCntyEilnIufkIECbEVv//Y1cpfQ/gHy06c0CgYEA/Ei4TQ0IXttNUkbVotN+CKapPyEPe3ypR9Kt4EYAyH8Q+5mg+huwWSunh1JddMUZ6INk+9/dNOLp7nnZZ1cXEjSpLnH4aevsvXstZ5QPAJ5X7wPAu+WTKPUIlHxEb+tU9+OFKK/fyi21jAOc3tQh5RokFpzhTcrFGbQN7vPBP28CgYEA9w4am74CTnXO+rjztyb7CWsKH/SOj38O72h5pMXmeCA2pFw+UiPt7pR39uRKnyNNncaA6JS1cFxpZGHLwwGIGczyqXdR2mYTJZ/uAuSo8xRUyYJhWZNywj0Cisevo9Q3evwdmTsLHuIbbcBjPJP3o5h7t3nQ9iOHg5kzaguyimcCgYEA8Af4YkbuGeIplQiUJtF2bqCzEr9Pzbv0C8Plbybrg9dxvxCSWqSRiqXART4WcQ0+8zxgjkyWWMU0sZL5SBtSdh3ogaoqIg04N6fEsXHrPDlrjJtTevKYqzVHro71Rk7vjtLIVMfQ8rm+q7KcRF4syZ+vilxE+RDdqvDm+Nyulr0CgYEA0H2zkNlyQtqC2O36pwcbdaBChCMzp/+3D+1gMuDcFWZCFsTNxy4RHnVnJBEWtGPrnYmmwiC1WeRzAMbTWXdSb0uZQdCzYBcic28bQo/L0I/I2eHQ2/JmN2ubWJkaLazrQTvAks7UHLT+JsnkNXw90W7egUEpSn9DXpiMXiMPAmMCgYBWdZp+tVgUrXg0yyDsptWSNUOmOMwytwKNYuaGmFk17O8w+lS0d01UhyMtXB+wxNv5ZaJW8qoowyfFLn47n960u0svR7Wn/E2iJbjvGikyQ2WuoF1nFedOyYl6cK47TleMk4qHGu+wYZuZGWw6hNSDWKUSmzXx+xEzMnlGaIQjZw==',
//        'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyLaInmp+qGdnifvOXFXU0/66cBwdCmNjbLXUzU+MhODHTcn2y+QK1PAMgMf0pb1xmfZS2QzlswvElpSDyu/AjL+VtS+KeoCacySt5RSsqNFsynDzweFAYUYbw+rwhVgv274PnGQ+fJH+fy0hw/LMHskousFfJHj2oHfq4e/8+NzS0jxadtv/703WbVrUJUZfd0AQvFr54GnrS6uPeNH7JOsgJ8u88tdC7HbPB4fJdps+JKEwshJMkNLLSHDu4/po+Zsc7qdjVU6LAN259r3yFwkf1W/rtPYh5/kisT88S4Xyv1vH9vF3eds+y6iZUAxMIn6pfBRtBc8ZmXoi8j0LJQIDAQAB',
        'cacert' => getcwd() . '/application/Common/Conf/cacert.pem',//ca证书路径地址，用于curl中ssl校验
        'transport' => 'http',//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'seller_email' => 'junweifakao@126.com',
        'product_code' => 'QUICK_MSECURITY_PAY',
    ),
    'CRON_CONFIG_ON' => true, // 是否开启自动运行
    'CRON_CONFIG' => array(
        '测试定时任务' => array('Api/Course/reply', '60', ''),
    ),
    'wxPay' => array(
        'appid' => 'wx11367b4000cda893',
        'mch_id' => '1445302502',
        'key' => 'BEIJINGjunweideyuanjiaoyu2017524',
        'appsecret' => '7813490da6f1265e4901ffb80afaa36f',
        'notify_url' => 'http://kf.junweiedu.cn/junwei/api/wxPay/notify',
    ),
);

return array_merge($configs, $db, $runtime_config);
