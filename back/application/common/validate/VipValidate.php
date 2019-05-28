<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-7
 * Time: 0:29
 */
namespace app\common\validate;

class VipValidate extends BaseValidate{
    static protected $rule = [
        'username'  => 'require',
        'mobile' => 'require',

    ];
    static protected $msg = [
        'username.require'  => '用户名不能为空',
        'mobile.require' => '手机号不能为空',
    ];

    static $scene = [
    ];
}
