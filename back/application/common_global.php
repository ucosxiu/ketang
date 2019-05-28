<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-4-11
 * Time: 2:48
 */
//获取URL访问的ROOT地址 网站的相对路径
define('BASE_SITE_ROOT', str_replace('/index.php', '', \think\facade\Request::instance()->root()));
define('PLUGINS_SITE_ROOT', BASE_SITE_ROOT.'/static/plugins');

define('ADMIN_SITE_ROOT', BASE_SITE_ROOT.'/static/admin');
define('API_SITE_ROOT', BASE_SITE_ROOT.'/static/api');

define("REWRITE_MODEL", FALSE); // 设置伪静态
if (!REWRITE_MODEL) {
    define('BASE_SITE_URL', \think\facade\Request::domain() . \think\facade\Request::baseFile());
} else {
    // 系统开启伪静态
    if (empty(BASE_SITE_ROOT)) {
        define('BASE_SITE_URL', \think\facade\Request::domain());
    } else {
        define('BASE_SITE_URL', \think\facade\Request::domain() . \think\facade\Request::root());
    }
}

define('API_SITE_URL', BASE_SITE_URL.'/api');
define('ADMIN_SITE_URL', BASE_SITE_URL.'/admin');
//define('MOBILE_SITE_URL', BASE_SITE_URL.'/mobile');
//define('WAP_SITE_URL', str_replace('/index.php', '', BASE_SITE_URL).'/wap');
define('UPLOAD_SITE_URL',str_replace('/index.php', '', BASE_SITE_URL).'/assets');
define('SESSION_EXPIRE',3600);

define('PUBLIC_PATH',ROOT_PATH.'public');
define('PLUGINS_PATH',ROOT_PATH.'plugins');
define('EXTEND_PATH',ROOT_PATH.'extend');
define('EXTEND_ORG_PATH',ROOT_PATH.'extend/org');
define('BASE_DATA_PATH',PUBLIC_PATH.'/data');
define('BASE_UPLOAD_PATH',PUBLIC_PATH.'/assets');

define('TIMESTAMP',time());
define('DIR_API','api');
define('DIR_ADMIN','admin');

define('DIR_UPLOAD','assets');

define('MD5_KEY', 'a2382918dbb49c8643f19bc3ab90ecf9');
define('CHARSET','UTF-8');
define('ALLOW_IMG_EXT','jpg,png,gif,bmp,jpeg');#上传图片后缀
define('HTTP_TYPE',  \think\facade\Request::isSsl() ? 'https://' : 'http://');#是否为SSL

//已取消 下架
define('ORDER_STATE_CANCEL', 0);
//已产生 待审核
define('ORDER_STATE_NEW', 10);
//已审核 出售中
define('ORDER_STATE_SALE', 20);
//出售完
define('ORDER_STATE_COMPLETE', 30);
