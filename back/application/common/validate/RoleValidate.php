<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 10:16
 */
namespace app\common\validate;


class RoleValidate extends BaseValidate{
    static protected $rule = [
        'rolename'  => 'require|max:25'

    ];
    static protected $msg = [
        'rolename.require'  => '角色名不能为空！',
    ];
}