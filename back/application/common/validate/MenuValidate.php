<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 10:16
 */
namespace app\common\validate;


class MenuValidate extends BaseValidate{
    static protected $rule = [
        'menuname'  => 'require|max:25',
        'm'   => 'require|max:60',
        'c' => 'require|max:60',
        'a' => 'require|max:60',

    ];
    static protected $msg = [
        'menuname.require'  => '菜单名称不能为空！',
        'm.require'       => '模块不能为空!',
        'c.require'         => '控制器不能为空!',
        'a.require'    => '方法不能为空!',
    ];
}