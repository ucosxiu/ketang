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
class Admin extends Common {
    protected $pk = 'adminid';
    protected $name = 'admin';
//    protected $resultSetType = 'collection';

    protected $auto = [];
    protected $insert = ['salt','password'];

    public function role()
    {
        return $this->belongsTo('Role','roleid');
    }

    public function setSaltAttr()
    {
        return random_string(6, true);
    }


    public function setPasswordAttr($value) {
        //添加isset函数，修复不存在属性访问失败bug
        //if ($this->adminid) {
        if (isset($this->adminid)) {
            if (!$value) {
                return $this->password;
            }
        } else {
            if (!$value) {
                $value = '123456';
            }
        }

        return md5($value.$this->getAttr('salt'));
    }
}
