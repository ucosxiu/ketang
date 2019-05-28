<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\model;


class Member extends Common
{
    protected $name = 'member';
    protected $pk = 'member_id';

    protected $resultSetType = 'collection';

    /**
     * [login 登录]
     * @AuthorHTL
     * @DateTime  2017-02-10T22:37:49+0800
     * @param     [string]                   $u_username [账号]
     * @param     [string]                   $u_pwd      [密码]
     * @param     [string]                   $verifyCode [验证码]
     * @param     Boolean                  	 $isRemember [是否记住密码]
     * @param     Boolean                    $type       [是否重复登录]
     * @return    [type]                               [description]
     */
    public function login($username, $password, $verifyCode = '', $isRemember = false, $type = false)
    {
        if (!$username) {
            $this->error = '帐号不能为空';
            return false;
        }
        if (!$password){
            $this->error = '密码不能为空';
            return false;
        }

        $map['username'] = $username;
        $userInfo = $this->where($map)->find();
        if (!$userInfo) {
            $this->error = '帐号不存在';
            return false;
        }

        if (user_md5($password, $userInfo['salt']) !== $userInfo['password']) {
            $this->error = '密码错误';
            return false;
        }
        if ($userInfo['status'] === 0) {
            $this->error = '帐号已被禁用';
            return false;
        }

        if ($isRemember || $type) {
            $secret['username'] = $username;
            $secret['password'] = $password;
            $data['rememberKey'] = encrypt($secret);
        }

        // 保存缓存
        session_start();
        $info['userInfo'] = $userInfo;
        $info['sessionId'] = session_id();
        $authKey = user_md5($userInfo['username'].$userInfo['password'].$info['sessionId']);
        $info['authKey'] = $authKey;
        $info['_AUTH_LIST_'] = [];
        cache('Auth_'.$authKey, null);
        cache('Auth_'.$authKey, $info, config('LOGIN_SESSION_VALID'));
        // 返回信息
        $data['authKey']		= $authKey;
        $data['sessionId']		= $info['sessionId'];
        $data['userInfo']		= $userInfo;
        return $data;
    }

    function register($username, $email, $mobile, $password) {
        if (!$username) {
            $this->error = '用户名不能为空';
            return false;
        }
        if (!$email){
            $this->error = '邮箱不能为空';
            return false;
        }
        if (!$mobile) {
            $this->error = '手机号不能为空';
            return false;
        }
        if (!$password){
            $this->error = '密码不能为空';
            return false;
        }
        $map = [];
        $map['username'] = $username;
        $userInfo = $this->where($map)->find();
        if ($userInfo) {
            $this->error = '用户名已存在';
            return false;
        }
        $map = [];
        $map['email'] = $email;
        $userInfo = $this->where($map)->find();
        if ($userInfo) {
            $this->error = '邮箱已存在';
            return false;
        }
        $map = [];
        $map['mobile'] = $mobile;
        $userInfo = $this->where($map)->find();
        if ($userInfo) {
            $this->error = '手机已被注册';
            return false;
        }

        $data['username'] = $username;
        $data['email'] = $email;
        $data['mobile'] = $mobile;
        $salt = random_string(6, true);
        $data['salt'] = $salt;
        $data['password'] =  md5($password.$salt);
        $data['status'] =  1;
        return $this->data($data)->save();
    }

    /**
     * 会员相关的信息
     * @param $condition
     */
    public function getMemberInfo($condition)
    {
        return $this->get($condition);
    }

    /**
     * 编辑会员
     * @access public
     * @param array $condition 检索条件
     * @param array $data 数据
     * @return bool
     */
    public function editMember($condition, $data)
    {
        $update = model('member')->where($condition)->update($data);
        return $update;
    }
}
