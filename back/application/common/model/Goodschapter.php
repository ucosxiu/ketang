<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 6:08
 */
namespace app\common\model;
use think\Model;

class Goodschapter extends Common
{
    protected $pk = 'chapter_id';
    protected $name = 'goods_chapter';
    protected $resultSetType = 'collection';
}
