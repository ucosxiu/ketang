<?php
namespace app\api\controller;

use org\Oauth;
use think\Controller;
use think\db\Where;

class Order extends Apibase
{
    function getlist() {
        $type = $this->request->param('type', '', 'trim');
        $type = in_array($type, ['pay', 'cancel', 'complete']) ? $type : '';

        $where = new Where();
        switch ($type) {
            case 'pay':
                $where['order_state'] = [ORDER_STATE_NEW];
                break;
            case 'complete':
                $where['order_state'] = ['in', [ORDER_STATE_PAY, ORDER_STATE_SUCCESS]];
                break;
            case 'cancel':
                //待评价
                $where['order_state'] = ORDER_STATE_CANCEL;
                break;
        }

        $where['is_delete'] = 0;
        //用户删除  不可以见
        $where['buyer_delete'] = 0;
        $where['buyer_id'] = $this->member->member_id;

        $paginate = model('order')->with('goods')->where($where)->order('order_id desc')->paginate()->toArray();
        $order_model = model('order');
        foreach ($paginate['data'] as $k => $v) {
            $paginate['data'][$k]['format_order_state'] = $order_model->get_format_order_state($v);
            $paginate['data'][$k]['operable_list'] = operable_list_user($v);
        }
        $this->result($paginate, 1, '数据初始化成功', 'json');
    }

    /**
     * 未支付订单 支付
     */
    function repay() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result([], 0, lang('param_error'), 'json');
        }
        $order_model = model('order');
        $order = $order_model->get($id);
        if (!$order || $order->buyer_id != $this->member->member_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $condition = new Where();
        $condition['buyer_id'] = $this->member->member_id;
        $condition['goods_id'] = $id;
        $condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SUCCESS));
        $order = model('order')->getOrderInfo($condition);
        if ($order) {
            $this->result([], 0, lang('您已购买'), 'json');
        }

        $state = $order_model->getOrderOperateState('repay', $order->toArray());
        if ($state) {
            $goods_model = model('goods');
            $goods = $goods_model->get($order->goods_id);
            if (!$goods) {
                $this->result([], 0, '无效的订单', 'json');
            }
            if ($goods->is_delete) {
                $this->result([], 0, '无效的订单', 'json');
            }
            if (!$goods->goods_state) {
                $this->result([], 0, '无效的订单', 'json');
            }


            $this->result(['pay_sn' => $order->pay_sn], 1, '提交成功', 'json');
//            //重新唤起支付
//            $_GET['openid'] = $this->member->openid;
//            require_once PLUGINS_PATH . '/payments/wxpay_minipro/wxpay_minipro.php';
//            $payment_config = config('payment_config');
//            $pay = new \wxpay_minipro(['payment_config' => $payment_config]);
//            $payform = [];
//            $payform['order_type'] = 'real_order';
//            $payform['pay_sn'] = $order['pay_sn'];
//            $payform['api_pay_amount'] = $order['order_amount'];
//            $pay->get_payform($payform);


        } else {
            $this->result([], 0, lang('无权操作'), 'json');
        }
    }

    //取消订单
    function cancel() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $order_model = model('order');
        $order = $order_model->get($id);
        if (!$order || $order->buyer_id != $this->member->member_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $state = $order_model->getOrderOperateState('buyer_cancel', $order->toArray());
        if ($state) {
            if ($order_model->changeOrderStateCancel($order->toArray(), 'buyer', $this->member->member_name, '')) {
                $this->result([], 1, lang('success'), 'json');
            } else {
                $this->result([], 0, '取消订单失败', 'json');
            }
        } else {
            $this->result([], 0, lang('无权操作'), 'json');
        }
    }

    /**
     * 删除订单
     */
    function delete() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result([], 0, lang('param_error'), 'json');
        }
        $order_model = model('order');
        $order = $order_model->get($id);

        if (!$order || $order->buyer_id != $this->member->member_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $state = $order_model->getOrderOperateState('buyer_delete', $order->toArray());
        if (!$state) {
            $this->result([], 0, lang('无权操作'), 'json');
        }

        $data = [];
        $data['buyer_delete'] = 1;
        $order->save($data);
        $this->result([], 1, '数据初始化成功', 'json');
    }

    /**
     * 获取订单详情
     */
    function detail() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result([], 0, lang('param_error'), 'json');
        }
        $order = model('order')->with(['goods'])->get($id);
        if (!$order || $order->buyer_id != $this->member->member_id) {
            $this->result([], 0, lang('param_error'), 'json');
        }

        $order->format_add_time = date('Y-m-d H:i:s', $order->add_time);

        $this->result($order, 1, '数据初始化成功', 'json');
    }
}
