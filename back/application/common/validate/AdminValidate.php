<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 16:02
 */
namespace app\common\validate;
use think\Validate;

class AdminValidate extends BaseValidate{
    static $rule = [
        'username'  => 'require|max:25',
    ];
    static $msg = [
        'username.require'  => '用户名不能为空!',
    ];
}