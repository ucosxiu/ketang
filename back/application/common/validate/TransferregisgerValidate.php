<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-15
 * Time: 2:25
 */
namespace app\common\validate;

use think\Validate;

class TransferregisgerValidate extends BaseValidate {
    static protected $rule = [
        'username'  => 'require',
        'mobile' => 'require|mobile',

    ];
    static protected $msg = [
        'username.require'  => '姓名不能为空！',
        'mobile.require' => '电话号码不能为空',
        'mobile.mobile' => '请输入正确的手机号码',
    ];

    static $scene = [
        'add'  =>  ['username','mobile'],
    ];
}
