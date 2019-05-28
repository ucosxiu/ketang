<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 21:28
 */
namespace app\admin\controller;

use app\common\model\Admin;
use think\Env;
use think\Request;

class Sign extends Adminbase
{
    //重载初始化函数
    function initialize() {
    }
    //登录页面
    public function login() {
        if (session('ADMIN_ID')){
            $this->redirect('index/index');
        }

        return $this->fetch();
    }

    public function dologin() {
        if (session('ADMIN_ID')){
            $this->redirect('index/index');
        }

        $username = $this->request->post('username');
        if (!$username) {
            $this->error('用户名不能为空');
        }

        $password = $this->request->post('password');
        if (!$password) {
            $this->error('密码不能为空');
        }

        $admin = Admin::get(['username' => $username]);
        if (!$admin) {
            $this->error('用户名或者密码错误');
        }

        $_password = $admin->password;
        $_salt = $admin->salt;

        if ($_password !== md5($password.$_salt)) {
            $this->error('用户名或者密码错误');
        }

        //添加登录记录
        $admin->lastloginip = request()->ip();
        $admin->lastlogintime = time();
        $admin->save();

        //记录用户ID
        session('ADMIN_ID', $admin->adminid);
        session('ADMIN', $admin);

        $this->success('登录成功', url('index/index'));
    }

    //退出
    function lagout() {
        session('ADMIN_ID', null);
        session('ADMIN', null);

        $this->redirect('sign/login');
    }
}
