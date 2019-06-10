<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-29
 * Time: 6:08
 */
namespace app\common\model;
use think\db\Where;
use think\Model;

//课程模型
class Goods extends Common
{
    protected $pk = 'goods_id';
    protected $name = 'goods';
    protected $resultSetType = 'collection';

    public function teacherids() {
        return $this->hasMany('goodsteacher', 'goods_id', 'goods_id');
    }

    public function teacher() {
        return $this->hasMany('goodsteacher', 'goods_id', 'goods_id')->join('teacher', model('goodsteacher')->getTable() . '.teacher_id = teacher.teacher_id');
    }

    public function chapter() {
        return $this->hasMany('goodschapter', 'goods_id', 'goods_id');
    }

    public function addGoods($param) {
        try {
            $goods_model = model('goods');
            $goods_model->startTrans();
            if ($param['is_free']) {
                //免费
                $param['goods_price'] = 0;
            }
            $param['add_time'] = TIMESTAMP;
            $goods_id = $goods_model->field('goods_name,cc_id,goods_pic,goods_price,goods_type,goods_intro,goods_state,goods_listorder,add_time')->insertGetId($param);
            if (!$goods_id) {
                exception('保存失败');
                return false;
            }

            $teacher_ids = explode(',', $param['teacher_ids']);
            $insert = $this->addCourseTeacher($goods_id, $teacher_ids);
            if (!$insert) {
                exception('保存失败');
                return false;
            }

            $goods_model->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }

    public function editGoods($param, $goods_id) {
        try {
            $goods_model = model('goods');
            $goods_model->startTrans();
            if ($param['is_free']) {
                //免费
                $param['goods_price'] = 0;
            }
           $update = $goods_model->field('goods_name,cc_id,goods_pic,goods_price,goods_type,goods_intro,goods_state,goods_listorder,add_time')->where(['goods_id' => $goods_id])->update($param);
            if (false === $update) {
                exception('保存失败');
                return false;
            }

            $teacher_ids = explode(',', $param['teacher_ids']);
            $insert = $this->editCourseTeacher($goods_id, $teacher_ids);
            if (!$insert) {
                exception('保存失败');
                return false;
            }

            $goods_model->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }

    public function addCourseTeacher($goods_id, $teacher_ids) {
        $teacher_ids = array_unique($teacher_ids);
        $rows = [];
        foreach ($teacher_ids as $k => $teacher_id) {
            array_push($rows, ['goods_id' => $goods_id, 'teacher_id' => $teacher_id]);
        }
        return model('goodsteacher')->insertAll($rows);
    }

    public function editCourseTeacher($goods_id, $teacher_ids) {
        $teacher_ids = array_unique($teacher_ids);
        $where = new Where();
        $where['goods_id'] = $goods_id;
        $where['teacher_id'] = ['not in', $teacher_ids];

        //删除不存在
        model('goodsteacher')->where($where)->delete();
        $rows = [];
        foreach ($teacher_ids as $k => $teacher_id) {
            if (!model('goodsteacher')->get(['goods_id' => $goods_id, 'teacher_id' => $teacher_id])) {
                array_push($rows, ['goods_id' => $goods_id, 'teacher_id' => $teacher_id]);
            }
        }
        if (!$rows) {
            return true;
        }
        return model('goodsteacher')->insertAll($rows);
    }

    public function getGoodsInfo($condition = []) {
        $goods = $this->where($condition)->find();
        if (!$goods) {
            return false;
        }

        return $goods;
    }
}
