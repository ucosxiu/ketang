<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-6-6
 * Time: 3:40
 */
namespace app\api\controller;

use app\admin\controller\Adminbase;
use org\Http;
use org\Wechat;
use PHPZxing\PHPZxingDecoder;
use think\Controller;
use think\db\Where;

class Course extends Apibase
{
    public function detail() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $course_model = model('goods');
        $course = $course_model->with(['chapter', 'teacher'])->get(['is_delete' => 0, 'goods_state' => 1, 'goods_id' => $id]);
        if (!$course) {
            $this->result('', 0, lang('param_error'), 'json');
        }
        $course->goods_pic = request()->rootUrl(true) . '/' .$course->goods_pic;
        $if_have_buy = $this->_check_buy_goods($course->goods_id);
        $course->if_has_buy = $if_have_buy;

        $course->is_fav =  $this->_check_fav_goods($course->goods_id);
        if ($course->goods_type == 0) {
            //检查是否已报名
            $course->if_has_sign = $this->_check_sign_goods($course->goods_id);
        }
        $course->if_has_join = $this->_check_join_goods($course->goods_id);

        $this->result($course, 1, '数据初始化成功', 'json');
    }

    public function join() {
        $goods_id = $this->request->param('id', 0, 'intval');
        if (!$goods_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $goods_model = model('goods');
        $goods = $goods_model->getGoodsInfo([
            'goods_state' => 1,
            'is_delete' => 0,
            'goods_id' => $goods_id
        ]);

        $is_join =  $this->_check_join_goods($goods_id);
        if ($is_join) {
            $this->result([], 1, lang('加入成功'), 'json');
        }

        $data = [];
        $data['goods_id'] = $goods_id;
        $data['member_id'] = $this->member->member_id;
        $goods_model = model('goods');
        $goods = $goods_model->get($goods_id);
        $data['goods_name'] = $goods['goods_name'];
        $data['add_time'] = TIMESTAMP;
        $join_model = model('goodsjoin');

        $where = new Where();
        $insert = model('goodsjoin')->insertGetId($data);
        if ($insert) {
            $this->result([], 1, '加入成功', 'json');
        } else {
            $this->result([], 0, '加入失败', 'json');
        }
    }

    //收藏
    public function fav() {
        $goods_id = $this->request->param('id', 0, 'intval');
        if (!$goods_id) {
            $this->result('', 0, lang('param_error'), 'json');
        }
        $goods = model('goods')->get($goods_id);
        if (!$goods) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $condition = new Where();
        $condition['member_id'] = $this->member->member_id;
        $condition['goods_id'] = $goods_id;
        $find = model('favorites')->where($condition)->find();
        if ($find) {
            //取消收藏
            $find->delete();
            $this->result('cancel', 1, '保存成功', 'json');
        } else {
            //添加收藏
            $data = [];
            $data['member_id'] = $this->member->member_id;
            $data['member_name'] = $this->member->member_name;
            $data['fav_type'] = 'goods';
            $data['goods_id'] = $goods_id;
            $data['goods_name'] =  $goods->goods_name;
            $data['goods_image'] =  $goods->goods_pic;
            $data['fav_time'] = TIMESTAMP;
            $data['fav_price'] = $goods->goods_price;

            model('favorites')->data($data)->save();
            $this->result('fav', 1, '保存成功', 'json');
        }


    }

    /**
     * 检测当前用户是否购买此商品
     */
    private function _check_buy_goods($goods_id) {
        $if_have_buy = false;
        if ($this->member->member_id) {
            $condition = new Where();
            $condition['buyer_id'] = $this->member->member_id;
            $condition['goods_id'] = $goods_id;
            $condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SUCCESS));
            $order = model('order')->getOrderInfo($condition);
            if (!empty($order)) {
                $if_have_buy = true;
            }
        }
        return $if_have_buy;
    }

    /**
     * @param $goods_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _check_sign_goods($goods_id) {
        $if_have_sign = false;
        if ($this->member->member_id) {
            $condition = new Where();
            $condition['member_id'] = $this->member->member_id;
            $condition['goods_id'] = $goods_id;
            $order = model('sign')->where($condition)->find();
            if (!empty($order)) {
                $if_have_sign = true;
            }
        }
        return $if_have_sign;
    }

    /**
     * 检测当前用户是否收藏此商品
     */
    private function _check_fav_goods($goods_id) {
        $condition = new Where();
        $condition['member_id'] = $this->member->member_id;
        $condition['goods_id'] = $goods_id;
        $find = model('favorites')->where($condition)->find();
        if (!empty($find)) {
            return true;
        }

        return false;
    }

    private function _check_join_goods($goods_id) {
        $condition = new Where();
        $condition['member_id'] = $this->member->member_id;
        $condition['goods_id'] = $goods_id;
        $find = model('goodsjoin')->where($condition)->find();
        if (!empty($find)) {
            return true;
        }

        return false;
    }

    /*
     * 购买
     */
    public function buy() {
        $goods_id = $this->request->param('id', 0, 'intval');
        if (!$goods_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $goods_model = model('goods');
        $goods = $goods_model->getGoodsInfo([
            'goods_state' => 1,
            'is_delete' => 0,
            'goods_id' => $goods_id
        ]);

        $is_buy =  $this->_check_buy_goods($goods_id);
        if ($is_buy) {
            $this->result([], 0, lang('您已购买'), 'json');
        }

        $order = [];
        $order['order_sn'] = makeOrderSn();
        $order['pay_sn'] = makePaySn($this->member->member_id);
        $order['goods_id'] = $goods_id;
        $order['goods_price'] = $goods->goods_price;
        $order['goods_number'] = 1;
        $order['order_amount'] =  $goods->goods_price * $order['goods_number'];
        $order['buyer_id'] = $this->member->member_id;
        $order['buyer_name'] = $this->member->member_name;
        $order['order_sn'] = makeOrderSn();
        $order['order_state'] = ORDER_STATE_NEW;
        $order['add_time'] = TIMESTAMP;

        $oder_model = model('order');
        if ($oder_model->data($order)->allowField(true)->save()) {
            $this->result(['pay_sn' => $order['pay_sn']], 1, '提交成功', 'json');
//            $_GET['openid'] = $this->member->openid;
//            require_once PLUGINS_PATH . '/payments/wxpay_minipro/wxpay_minipro.php';
//            $payment_config = config('payment_config');
//            $pay = new \wxpay_minipro(['payment_config' => $payment_config]);
//            $payform = [];
//            $payform['pay_sn'] = $order['pay_sn'];
//            $payform['api_pay_amount'] = $order['order_amount'];
//            $pay->get_payform($payform);
        } else {
            $this->result([], 0, '提交失败', 'json');
        }
    }

    public function pay() {
        $pay_sn = $this->request->param('pay_sn', '', 'trim');
        if (!$pay_sn) {
            $this->result([], 0, lang('param_error'), 'json');
        }
        $order_model = model('order');
        $condition = [];
        $condition['pay_sn']= $pay_sn;

        $data = [];
        $data['order_state'] = ORDER_STATE_PAY;
        $data['payment_time'] = request()->time();
        if ($order_model->save($data, $condition)) {
            $this->result([], 1, '支付成功', 'json');
        } else {
            $this->result([], 0, '支付失败', 'json');
        }
    }

    /**
     * 报名
     */
    public function sign() {
        $goods_id = $this->request->param('id', 0, 'intval');
        if (!$goods_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $goods_model = model('goods');
        $goods = $goods_model->getGoodsInfo([
            'goods_state' => 1,
            'is_delete' => 0,
            'goods_id' => $goods_id
        ]);

        $sign_model = model('sign');
        $order = [];
        $order['add_time'] = TIMESTAMP;
        $order['goods_id'] = $goods_id;
        $order['member_id'] = $this->member->member_id;
        $order['realname'] = $this->request->param('realname', '', 'trim');
        $order['mobile'] = $this->request->param('mobile', '', 'trim');
        if ($sign_model->data($order)->allowField(true)->save()) {
            $this->result([], 1, '报名成功', 'json');
        } else {
            $this->result([], 0, '报名失败', 'json');
        }
    }

    public function joinlist() {
        $where = new Where();
        $where['a.member_id'] = $this->member->member_id;
        $where['b.is_delete'] = 0;

        $fav_model = model('goodsjoin');
        $paginate = $fav_model->alias('a')->leftJoin('goods b', 'a.goods_id = b.goods_id')->field('b.*')->where($where)->order('a.join_id desc')->paginate()->toArray();
        foreach ($paginate['data'] as $k => $v) {
            $paginate['data'][$k]['goods_pic'] = request()->rootUrl(true).'/'.$v['goods_pic'];
        }
        $this->result($paginate, 1, '数据初始化成功', 'json');
    }

    public function delJoin() {
        $goods_id = $this->request->param('id', 0, 'intval');
        if (!$goods_id) {
            $this->result('', 0, lang('param_error'), 'json');
        }
        $goods = model('goods')->get($goods_id);
        if (!$goods) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $condition = new Where();
        $condition['member_id'] = $this->member->member_id;
        $condition['goods_id'] = $goods_id;
        if (model('goodsjoin')->where($condition)->delete()) {
            $this->result([], 1, '删除成功', 'json');
        } else {
            $this->result([], 0, '删除失败', 'json');
        }
    }
}
