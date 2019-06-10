<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-6-6
 * Time: 20:45
 */
namespace app\api\controller;

use org\Http;
use org\Wechat;
use org\wechat\SDK;
use PHPZxing\PHPZxingDecoder;
use think\Controller;
use think\db\Where;

class Teacher extends Apibase
{
    public function info() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $teacher_model = model('teacher');
        $teacher = $teacher_model->get(['teacher_state' => 1, 'teacher_id' => $id]);
        if (!$teacher) {
            $this->result('', 0, lang('param_error'), 'json');
        }
        $where = new Where();
        $where['a.teacher_id'] = $teacher->teacher_id;
        $where['b.is_delete'] = 0;
        $where['b.goods_state'] = 1;

        $courses = model('goodsteacher')->alias('a')->leftJoin('goods b', 'b.goods_id = a.goods_id')->where($where)->field('b.*')->select();
        foreach ($courses as $k => $course) {
            $courses[$k]['goods_pic'] = request()->rootUrl(true).'/'.$course->goods_pic;
        }
        $teacher->courses = $courses;
        $teacher->teacher_pic = request()->rootUrl(true) . '/' .$teacher->teacher_pic;
        $this->result($teacher, 1, '数据初始化成功', 'json');
    }

    public function getlist() {
        $teacher_model = model('teacher');
        $where = [];
        $where['teacher_state'] = 1;
        $order = 'teacher_id desc';
        $paginate = $teacher_model->where($where)->order($order)->paginate()->toArray();
        foreach ($paginate['data'] as $k => $v) {
            $paginate['data'][$k]['teacher_pic'] = request()->rootUrl(true).'/'.$v['teacher_pic'];
        }
        $this->result($paginate, 1, '数据初始化成功', 'json');
    }
}
