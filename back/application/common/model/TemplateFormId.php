<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8
 * Time: 11:20
 */
namespace app\common\model;

use think\db\Where;
use think\Model;

class TemplateFormId extends Common {
    protected $pk = 'fid';
    protected $name = 'template_formid';
    protected $createTime = 'add_time'; //添加时间

    //获取最新有效的fromid
    function getFirstFormid() {
        $where          = new Where();
        $where['status']    = 0;
        $where['add_time'] = ['>', time()  - 7 * 24 * 3600 - 12 * 3600];

        $re = $this->where($where)->find();
        return $re;
    }
}
