<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-6
 * Time: 2:07
 */
namespace app\common\validate;

class TeacherValidate extends BaseValidate{
    static protected $rule = [
        'teacher_name'  => 'require|max:25'

    ];
    static protected $msg = [
        'teacher_name.require'  => '教师名不能为空！',
    ];
}
