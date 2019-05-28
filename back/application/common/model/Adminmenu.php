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

//管理模型
class Adminmenu extends Common {
    protected $pk = 'menuid';
    protected $name = 'admin_menu';
//    protected $resultSetType = 'collection';
}
