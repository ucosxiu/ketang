<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\model;


class Classes extends Common
{
    protected $name = 'school_class';
    protected $pk = 'class_id';

    protected $resultSetType = 'collection';

    /**
     * 年级
     * @return \think\model\relation\BelongsTo
     */
    function grade() {
        return $this->belongsTo('grade', 'grade_id', 'grade_id');
    }

    /**
     * 所属学校
     * @return \think\model\relation\BelongsTo
     */
    function school() {
        return $this->belongsTo('school', 'school_id', 'school_id');
    }
}
