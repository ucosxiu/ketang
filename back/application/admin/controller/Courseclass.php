<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 17:41
 */
namespace app\admin\controller;

//课程分类模块
class Courseclass extends Adminbase
{
    function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
    }

    function index() {
        if (!$this->request->isAjax()) {
            //获取所有分类
            return $this->fetch();
        } else {
            $courseclass_model = model('courseclass');
            $courseclasss = $courseclass_model->initTree();
            echo $this->success('', '', $courseclasss);
            return ;
        }
    }

    function add() {
        if ($this->request->isAjax()) {
            $param = $this->request->param();

            $courseclass_model = model('courseclass');
            if ($courseclass_model->data($param)->allowField(true)->save()) {
                $this->success('添加成功', Url('courseclass/index'));
            }
            $this->error('添加失败');
        } else {
            $pid = $this->request->param('pid', 0, 'intval');
            $courseclass_model = model('courseclass');

            $courseclasss = $courseclass_model->getAllChildByParentId(0);
            $this->assign('courseclasss', $courseclasss);
            $this->assign('pid', $pid);
            return $this->fetch();
        }
    }

    //编辑
    function edit() {
        $id = $this->request->param('id', 0, 'intval');
        $courseclass_model = model('courseclass');
        $courseclass = $courseclass_model->get($id);
        if (!$courseclass) {
            $this->error('parameter_error');
        }
        $parentid = $this->request->param('parentid', 0, 'intval');

        if ($id == $parentid) {
            $this->error('不能选择自己作为父分类');
        }

        if ($this->request->isAjax()) {
            $param = $this->request->param();
            if ($courseclass->data($param)->allowField(true)->save()) {
                $this->success(lang('save_success'), Url('courseclass/index'));
            }
            $this->error(lang('save_error'));
        } else {

            $courseclasss = $courseclass_model->getAllChildByParentId(0);
            $this->assign('courseclasss', $courseclasss);
            $this->assign('courseclass', $courseclass);
            $this->assign('pid', $courseclass['parentid']);
            return $this->fetch();
        }
    }

    //删除
    function del() {
        $id = $this->request->param('id', 0, 'intval');
        $courseclass_model = model('courseclass');
        if ($courseclass_model->where(['tc_id'=>$id])->delete()) {
            $this->success(lang('delete_success'));
        }
        $this->error(lang('delete_error'));
    }

    //排序
    function listorders() {
        $id = input('param.id', 0, 'intval');
        if (!$id) {
            $this->error('parameter_error');
        }

        $val = input('param.val', 0, 'intval');
        if ($val > 10000 || $val < 0) {
            $this->error('parameter_error');
        }

        $courseclass_model = model('courseclass');
        $data['tc_listorder'] = $val;
        $status = $courseclass_model->allowField(true)->save($data, ['tc_id'=>$id]);

        if ($status !== false) {
            $this->success(lang('success'));
        } else {
            $this->error(lang('error'));
        }
    }

    /**
     * 设置
     */
    function setting() {
        if ($this->request->isAjax()) {
            $courseclass = $this->request->param('courseclass', 0, 'trim');
            $courseclass_model = model('courseclass');
            $courseclass_model->recommend($courseclass);
            $this->success(lang('success'));
        } else {
            $id = $this->request->param('id', 0, 'intval');
            $courseclass_model = model('courseclass');

            $courseclasss = $courseclass_model->getAllChildByParentId($id);
            $this->assign('courseclasss', $courseclasss);
            return $this->fetch();
        }
    }

    /**
     * 获取子分类
     */
    function getchild() {
        $pid = $this->request->param('id', 0, 'intval');

        $courseclass_model = model('courseclass');
        $courseclasss = $courseclass_model->getAllChildByParentId($pid);
        $this->success('', '', ['courseclasss' => $courseclasss]);
    }
}