<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 6:08
 */
namespace app\common\model;
use think\Model;

//课程模型
class Course extends Common
{
    protected $pk = 'course_id';
    protected $name = 'course';
    protected $resultSetType = 'collection';
}
