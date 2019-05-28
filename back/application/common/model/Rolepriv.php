<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 23:48
 */
namespace app\common\model;

use org\Tree;
use think\Lang;
use think\Model;

//小区模型
class Rolepriv extends Model{
//    protected $pk = 'roleid';
    protected $name = 'admin_role_priv';

    protected $resultSetType = 'collection';
}