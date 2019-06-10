<?php
namespace app\api\controller;

use org\Http;
use org\Wechat;
use PHPZxing\PHPZxingDecoder;
use think\Controller;

class Data extends Controller
{
    public function config() {
    }

    /**
     * 获取广告
     */
    public function getads() {
        $position_model = $this->request->param('model', '', 'trim');
        if (!$position_model) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $ads = model('adposition')->getAds($position_model);
        foreach ($ads as $k => $ad) {
            $ads[$k]['ad_img'] = request()->rootUrl(true) . '/' . $ad['ad_img'];
        }
        $this->result($ads, 1, '数据初始化成功', 'json');
    }

    function getOpenid() {
        $code = $this->request->param('code', '', 'trim');
        if (!$code) {
            $this->result([], 0, '错误请求1', 'json');
        }

        $wechat = new Wechat([]);
        $data = $wechat->jscode2session($code);
        if ($data && isset($data['openid'])) {
            $member_model = model('member');
            $member = $member_model->get(['openid'=>$data['openid']]);
            if (!$member) {
                //用户不存在保存用户

                $save = ['openid'=>$data['openid'], 'status' => 1];
                $member_model->data($save)->save();
            }
            $this->result(['openid' => $data['openid']], 1, '数据初始化成功', 'json');
        } else {
            $this->result([], 0, '获取用户信息失败', 'json');
        }
    }


    public function getindex() {
        $ads = model('adposition')->getAds('index_ad');
        foreach ($ads as $k => $ad) {
            $ads[$k]['ad_img'] = request()->rootUrl(true) . '/' . $ad['ad_img'];
        }


        $navs = model('adposition')->getAds('index_nav');
        foreach ($navs as $k => $ad) {
            $navs[$k]['ad_img'] = request()->rootUrl(true) . '/' . $ad['ad_img'];
        }

        $hots = model('goods')->where(['is_hot' => 1, 'is_delete' => 0])->order('goods_listorder desc')->select();
        foreach ($hots as $k => $hot) {
            $hots[$k]['goods_pic'] = request()->rootUrl(true) . '/' . $hot['goods_pic'];
        }

        $recommends = model('goods')->where(['is_recommend' => 1, 'is_delete' => 0])->order('goods_listorder desc')->select();
        foreach ($recommends as $k => $recommend) {
            $recommends[$k]['goods_pic'] = request()->rootUrl(true) . '/' . $recommend['goods_pic'];
        }

        $this->result(['ads' => $ads, 'navs' => $navs, 'hots' => $hots, 'recommends' => $recommends], 1, '数据初始化成功', 'json');
    }
}
