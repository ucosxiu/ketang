<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-5-24
 * Time: 3:44
 */
namespace app\api\controller;

use org\Emoji;
use org\Wechat;
use org\wechat\SDK;
use PHPZxing\PHPZxingDecoder;
use think\App;
use think\Controller;

class Apibase extends Controller
{
    public $weObj;

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $config = config('wechat');
        $config['token'] = 'simuquanyi';
        $this->weObj = new SDK($config);
        $this->decryptMsg();

        $this->loadMember();
    }

    private function decryptMsg() {
        $this->weObj->valid();
    }

    private function loadMember() {
        $wedata = $this->weObj->getRev()->getRevData();
        $openid = $wedata['FromUserName'];
        $member_model = model('member');
        $member = $member_model->get(['openid' => $openid]);
        if ($member) {
            $this->member = $member;
        } else {
            $userinfo = $this->weObj->getUserInfo($openid);
            if (!empty($userinfo) && !empty($userinfo['openid'])) {
                $emoji = new Emoji();
                $nickname = $emoji->emoji_unified_to_html($userinfo['nickname']);
                $nickname = preg_replace('/xE0[x80-x9F][x80-xBF]'.'|xED[xA0-xBF][x80-xBF]/S','?', $nickname );

                $nickname = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $nickname);

                $userinfo['nickname'] = strip_tags($nickname);//过滤emoji表情产生的html标签
                $userinfo['member_name'] = $userinfo['nickname'];
                $data = [
                    'openid' => $userinfo['openid'],
                    'member_name' => $userinfo['nickname'],
                    'nickname' => $userinfo['nickname'],
                    'sex' => $userinfo['sex'],
                    'headimgurl' => $userinfo['headimgurl'],
                    'city' => $userinfo['city'],
                    'province' => $userinfo['province'],
                    'country' => $userinfo['country'],
                ];
                update_wechat($userinfo);
                $member = $member_model->get(['openid' => $openid]);
                $this->member = $member;
            } else {
                exit;
            }
        }
    }
}
