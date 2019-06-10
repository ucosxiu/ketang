<?php
namespace app\api\controller;

use think\App;
use think\Controller;

class Apibase extends Controller
{
    public $param;
    function __construct(App $app = null)
    {
        parent::__construct($app);
    }

    function initialize()
    {
        $this->checkSignature();
        $noAccess = ['data', 'article'];
        if (!in_array(strtolower($this->request->controller()), $noAccess)) {
            $this->checkAccessToken();
        }
    }

    public function checkSignature() {
        $signature = $this->request->param('signature', '', 'trim');
        if (!$signature) {
            $this->result('', '0', '获取用户信息失败', 'json');
        }
        $param = $this->request->param();
        unset($param['signature']);
        ksort($param);
        $r = [];
        $n = [];
        foreach ($param as $key => $value) {
            if (in_array($key, ['address', 'content', 'nickname', 'oss_img', 'avatar', 'back_img', 'font_color', ''])) {
                $r[$key] = urlencode($this->re($value));
            } else {
                $r[$key] = $value;
            }
            array_push($n, $key . '=' .$r[$key]);
        }

        $n = implode('&', $n);
        $n = strtoupper($n);

        $p = md5($n);
        $p .= "17378a5fe415fc1abf05b25f606392d7";
        $signature = aesDecrypt($signature);

        if ($p != $signature) {
            $this->result('', 0, '获取用户信息失败', 'json');
        }

        $this->param = $param;
    }

    /**
     * 检查用户是否存在
     */
    private function checkAccessToken() {
        $member_model = model('member');
        $member = $member_model->get(['openid' => $this->param['openid']]);
        if (!$member) {
            $this->result([], 0, '错误请求','json');
        }
        $this->member = $member;
    }

    function re($e) {
        $search = [
            '/!/g',
            '/~/g',
            '/\*/g',
            '/\'/g',
            '/\(/g',
            '/\)/g',
        ];
        $replace = [
            "%21",
            "%7E",
            "%2A",
            "%27",
            "%28",
            "%29"
        ];
        $e = str_replace($search, $replace, $e);
        return $e;
    }
}
