<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-6
 * Time: 2:07
 */
namespace app\common\validate;

class StudentValidate extends BaseValidate{
    static protected $rule = [
        'student_name'  => 'require|max:25'

    ];
    static protected $msg = [
        'student_name.require'  => '学生名不能为空！',
    ];
}
