<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-27
 * Time: 14:13
 */
namespace think;
if (ini_get('magic_quotes_gpc')) {
    function stripslashesRecursive(array $array)
    {
        foreach ($array as $k => $v) {
            if (is_string($v)) {
                $array[$k] = stripslashes($v);
            } else if (is_array($v)) {
                $array[$k] = stripslashesRecursive($v);
            }
        }
        return $array;
    }

    $_GET = stripslashesRecursive($_GET);
}

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

define('SCRIPT_DIR', rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/\\'));

//网站当前路径
define('SITE_PATH', dirname(__DIR__));

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';


// 支持事先使用静态方法设置Request对象和Config对象

// 执行应用并响应
Container::get('app')->bind('admin')->run()->send();
