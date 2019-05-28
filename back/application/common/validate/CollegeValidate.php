<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-6
 * Time: 3:54
 */

namespace app\common\validate;


class CollegeValidate extends BaseValidate{
    static protected $rule = [
        'college_name'  => 'require|max:25'

    ];
    static protected $msg = [
        'college_name.require'  => '学院名不能为空！',
    ];
}
