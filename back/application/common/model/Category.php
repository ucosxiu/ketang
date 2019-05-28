<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\model;


use org\Tree;
use think\facade\Lang;

class Category extends Common
{
    protected $name = 'category';
    protected $pk = 'cat_id';

    protected $resultSetType = 'collection';

    function getCache() {
        $data = rkcache('category');
        if (!$data) {
            $categorys = static::where('is_show',1)->field('cat_id, cat_name, parentid, is_show')->select()->toArray();
            $data = [];
            if(!empty($categorys)) {
                foreach ($categorys as $category) {
                    if (!$category) {
                        continue;
                    }
                    $id = $category['cat_id'];
                    $pid = $category['parentid'];
                    $data['data'][$id] = $category;
                    $data['parent'][$id] = $pid;
                    $data['children'][$pid][] = $id;
                }
                foreach ((array)@$data['children'][0] as $id) {
                    if (!empty($data['children'][$id])) {
                        foreach ((array)$data['children'][$id] as $cid) {
                            if (!empty($data['children'][$cid])) {
                                foreach ((array)$data['children'][$cid] as $ccid) {
                                    $data['children2'][$id][] = $ccid;
                                }
                            }
                        }
                    }
                }
            }
//            wkcache('category', $data);
        }
        return $data;
    }

    function getAllChildByParentId($pid)
    {
        return $this->field('*, parentid pid, cat_id id')->where(['parentid' => $pid])->select();
    }

    /**
     * 按父ID查找菜单子项
     * @param integer $parentid   父菜单ID
     */
    static function selectTree($selectid = 0)
    {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $categorys = static::where([])->field('cat_id, cat_name, parentid, is_show')->select()->toArray();

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

        $categorys = static::where($condition)->field('cat_id id, parentid pid, cat_name, is_show, src, listorder')->select()->toArray();

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

    function getCategoryList ($pid=0) {
        $categorys = $this->field('cat_id id, parentid pid, cat_name')->where(['parentid'=>$pid])->order('listorder desc, cat_id desc')->select()->toArray();
        foreach ($categorys as $k => $category) {
            $categorys[$k]['childs'] = $this->getCategoryList($category['id']);
        }
        return $categorys;
    }


    /**
     * 获取列表
     * @param array $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function getList($condition=[]) {
        return $this->where($condition)->select();
    }
}
