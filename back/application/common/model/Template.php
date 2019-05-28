<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-24
 * Time: 15:41
 */
namespace app\common\model;

use think\Model;

class Template extends Common
{
    protected $name = 'template';
    protected $pk = 'id';

    protected $resultSetType = 'collection';

    protected $autoWriteTimestamp = 'dateline';
    protected $createTime = 'dateline';
    protected $updateTime = 'update_time';

    function member() {
        return $this->belongsTo('member', 'uid', 'uid');
    }

    function templatepage() {
        return $this->hasMany('templagePage', 'templateid', 'id');
    }

    function joinuser() {
        return $this->belongsTo('member', 'uid', 'uid')->bind('username');
    }

    function joincat1() {
        return $this->belongsTo('category', 'cat_1', 'tc_id')->bind([
            'cat1'	=> 'tc_name'
        ]);
    }

    function joincat2() {
        return $this->belongsTo('category', 'cat_2', 'tc_id')->bind([
            'cat2'	=> 'tc_name'
        ]);
    }

    function setContentAttr($data)
    {
        return serialize($data);
    }

    function getContentAttr($value) {
        return unserialize($value);
    }

    /**
     * 创建模板
     */
    function addTemplate($data) {
        $result = $this->createData($data);
        if (!$result) {
            return false;
        }
        //模板ID
        return ['token'=>$this->token];
    }

    /**
     * 获取模板列表
     */
    function getTemplateList($condition, $field = '*', $order = 'id desc', $extend = array(), $master = false) {
        $condition['isactivity'] = 0;
        $with = isset($extend['with']) ? $extend['with'] : '';
        return $this->field($field)->where($condition)->with($with)->order($order)->paginate()->toArray();
    }

    /**
     * 获取活动列表
     */
    function getActivityList($condition, $field = '*', $order = 'id desc', $extend = array(), $master = false) {
        $condition['isactivity'] = 1;
        $with = isset($extend['with']) ? $extend['with'] : '';
        return $this->field($field)->where($condition)->with($with)->order($order)->paginate()->toArray();
    }

    function getList($condition, $field = '*', $order = 'id desc', $extend = array(), $master = false) {
        $op = $this->alias('a')->field($field)->where($condition);
        $op = isset($extend['with']) ? $op->with($extend['with']) : $op;
        return $op->order($order)->paginate()->toArray();
    }

    /**
     * @param $cat
     * @param array $extend
     * @param int $limit
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function getRecommendsbyCategory ($uid, $cat, $extend = [], $limit = 0) {
         $condition = [];
         $condition['cat_2'] = $cat;
         $condition['recommend'] = 1;
         $condition['isopen'] = 1;
         $op = $this->where($condition)->order('id desc');
         $op = $limit ? $op->limit($limit) : $op;
         $op = isset($extend['with']) ? $op->with($extend['with']) : $op;
         $list = $op->select();

         $fav_model = \model('fav');
         foreach ($list as $k => $v) {
             $list[$k]['fav_status'] = $fav_model->where(['term_id'=>$v->id, 'fav_type'=>1])->find() ? 1 : 0;
         }
         return $list;
    }

    /**
     * 取得模板详细信息
     * @param $condition
     */
    function detail($condition, $extend = []) {
        //获取订单
        $op = $this->where($condition);
        $op = isset($extend['with']) ? $op->with($extend['with']) : $op;
        $op = $op->find();
        if ($op) {
            return  $op->toArray();
        } else {
            return [];
        }

    }

    /**
     * 编辑模板
     */
    function editTemplate($param, $condition) {
        return $this->save($param, $condition);
    }

    /**
     * 删除模板
     * @param $condition
     */
    function delTemplate ($condition) {
        return $this->where($condition)->delete();
    }


    /**
     * @param $id
     */
    function addView ($id) {
        return $this->where(['id'=>$id])->setInc('view', 1);
    }
}
