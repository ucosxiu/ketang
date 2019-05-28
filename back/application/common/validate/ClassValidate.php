<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-6
 * Time: 3:54
 */

namespace app\common\validate;


class ClassValidate extends BaseValidate{
    static protected $rule = [
        'class_name'  => 'require|max:25'

    ];
    static protected $msg = [
        'class_name.require'  => '班级名不能为空！',
    ];
}
