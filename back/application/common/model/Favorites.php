<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-6-7
 * Time: 14:31
 */
namespace app\common\model;
use think\Model;

//收藏模型
class Favorites extends Common
{
    protected $pk = 'fav_id';
    protected $name = 'favorites';
    protected $resultSetType = 'collection';
}
