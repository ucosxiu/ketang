<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\model;


class Grade extends Common
{
    protected $name = 'school_grade';
    protected $pk = 'grade_id';

    protected $resultSetType = 'collection';

    /**
     * 所有班级
     */
    function classes() {
        return $this->hasMany('classes', 'school_id', 'school_id')->where(['classes.is_delete' => 0]);
    }


    /**
     * 所属学校
     * @return \think\model\relation\BelongsTo
     */
    function school() {
        return $this->belongsTo('school', 'school_id', 'school_id');
    }

    /**
     * 添加年级
     */
    function addgrade($data = []) {
        return $this->save($data);
    }
}
