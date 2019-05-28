<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\model;


use org\Tree;
use think\facade\Lang;

class Articleclass extends Common
{
    protected $name = 'articleclass';
    protected $pk = 'ac_id';

    protected $resultSetType = 'collection';

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     */
    function selectTree($selectid = 0)
    {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $categorys = static::where([])->field('ac_id id, ac_name name, parentid, is_show')->select()->toArray();

        $_categorys = [];
        if (!empty($categorys)) {
            foreach ($categorys as $category) {
                if (!$category) {
                    continue;
                }
                $category['selected'] = $selectid == $category['id'] ? 'selected' : '';
                $_categorys[$category['id']] = $category;
            }
        }

        $tree->init($_categorys);

        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
        $options = $tree->get_tree(0, $str);
        return $options;
    }

    function initTree ($condition=[]) {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $categorys = static::where($condition)->field('*, ac_id id, ac_parent_id pid, ac_name, is_show, ac_listorder')->select()->toArray();

        $_categorys = [];
        $statuses = [0=>lang('hide'), 1=>lang('show')];
        if(!empty($categorys)) {
            foreach ($categorys as $category) {
                if (!$category) {
                    continue;
                }
                $category['status'] = $statuses[$category['is_show']];
                $_categorys[$category['id']] = $category;
            }
        }
        return $_categorys;
    }

    /**
     * 获取子分类
     * @param $pid
     * @param array $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function getAllChildByParentId($pid, $condition = [], $order = 'ac_id desc', $limit='')
    {
        $condition['ac_parent_id'] = $pid;
        return $this->field('*, ac_parent_id pid, ac_id id')->where($condition)->limit($limit)->select();
    }
}
