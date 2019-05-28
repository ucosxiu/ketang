<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//商家模型
class Company extends Common
{
    protected $pk = 'company_id';
    protected $name = 'company';
    protected $resultSetType = 'collection';

    function category() {
        return $this->belongsTo('category', 'cat_id', 'cat_id');
    }
}
