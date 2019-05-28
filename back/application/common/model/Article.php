<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//文章模型
class Article extends Common
{
    protected $pk = 'article_id';
    protected $name = 'article';
    protected $resultSetType = 'collection';

    protected $autoWriteTimestamp = 'add_time';
    protected $createTime = 'add_time';
    function articleclass() {
        return $this->belongsTo('articleclass', 'ac_id', 'ac_id');
    }
}
