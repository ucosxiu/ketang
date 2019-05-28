<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-7
 * Time: 0:29
 */
namespace app\common\validate;

class BankValidate extends BaseValidate{
    static protected $rule = [
        'bank_no'  => 'require',
        'bank_user_name' => 'require',
        'bank_11' => 'require',

    ];
    static protected $msg = [
        'bank_no.require'  => '图片不能为空！',
        'bank_user_name.require' => '认证真实姓名不能为空',
    ];

    static $scene = [
        'add'  =>  ['bank_no','bank_user_name'],
        'edit'  =>  ['bank_no','bank_user_name'],
    ];
}
