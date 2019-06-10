<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 6:07
 */
namespace app\common\model;
use org\Tree;
use think\Model;

//课程分类模型
class Courseclass extends Common
{
    protected $pk = 'cc_id';
    protected $name = 'course_class';
    protected $resultSetType = 'collection';

    /**
     * 获取子分类
     * @param $pid
     * @param array $condition
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function getAllChildByParentId($pid, $condition = [], $order = 'cc_id desc', $limit='')
    {
        $condition['cc_parent_id'] = $pid;
        return $this->field('*, cc_parent_id pid, cc_id id')->where($condition)->limit($limit)->select();
    }

    function initTree($condition=[]) {
        $tree = new Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $categorys = static::where($condition)->field('*,cc_id id,cc_parent_id pid,cc_name, cc_state,cc_listorder')->select()->toArray();

        $_categorys = [];
        $statuses = [0=>lang('hide'), 1=>lang('show')];
        if(!empty($categorys)) {
            foreach ($categorys as $category) {
                if (!$category) {
                    continue;
                }
                $category['status'] = $statuses[$category['cc_state']];
                $_categorys[$category['id']] = $category;
            }
        }
        return $_categorys;
    }
}
