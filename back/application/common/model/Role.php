<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28
 * Time: 14:42
 */
namespace app\common\model;

use org\Tree;
use think\Lang;
use think\Model;

//角色模型
class Role extends Model{
    protected $pk = 'roleid';
    protected $name = 'admin_role';
//    protected $resultSetType = 'collection';

}