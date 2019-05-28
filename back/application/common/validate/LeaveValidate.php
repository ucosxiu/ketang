<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-6
 * Time: 2:07
 */
namespace app\common\validate;

class LeaveValidate extends BaseValidate{
    static protected $rule = [
        'student_id'  => 'require',
        'start_time'    => 'require',
        'end_time'      => 'require',
        'duration'      => 'require',
        'mask'      => 'require',

    ];
    static protected $msg = [
        'student_id.require'  => '请选择申请人',
        'start_time.require'    => '请选择开始时间',
        'end_time.require'      => '请选择结束时间',
        'duration.require'      => '请输入请假天数',
        'mask.require'      => '请输入请假理由',
    ];
}
