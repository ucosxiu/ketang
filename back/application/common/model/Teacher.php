<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-7
 * Time: 1:00
 */
namespace app\common\model;


class Teacher extends Common
{
    protected $name = 'teacher';
    protected $pk = 'teacher_id';
    protected $resultSetType = 'collection';
}
