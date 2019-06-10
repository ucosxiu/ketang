<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-6-9
 * Time: 3:44
 */
namespace app\common\model;

use org\Tree;
use think\Lang;
use think\Model;

//报名模型
class Sign extends Model{
    protected $pk = 'sign_id';
    protected $name = 'sign';
    protected $resultSetType = 'collection';
}
