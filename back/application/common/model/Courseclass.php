<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 6:07
 */
namespace app\common\model;
use think\Model;

//课程分类模型
class Courseclass extends Common
{
    protected $pk = 'cc_id';
    protected $name = 'course_class';
    protected $resultSetType = 'collection';
}
