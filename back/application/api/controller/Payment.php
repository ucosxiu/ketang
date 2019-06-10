<?php

/*
 * 支付相关处理
 */

namespace app\api\controller;

use think\Lang;
use think\Log;

class Payment {
    /**
     *
     * @param type $payment_code  共用回调方法
     * @param type $show_code  实际支付方式名称
     */
    public function notify($payment_code,$show_code='') {
        //创建支付接口对象
        require_once PLUGINS_PATH . '/payments/wxpay_native/wxpay_native.php';

        $payment_config = config('payment_config');

        $payment_api = new \wxpay_native(['payment_config' => [
            'wx_appid' => $payment_config['xcx_appid'],
            'wx_mch_id' => $payment_config['xcx_mch_id'],
            'wx_key' => $payment_config['xcx_key']
        ]]);

        //对进入的参数进行远程数据判断
        $verify = $payment_api->verify_notify();
        $out_trade_no = $verify['out_trade_no']; #内部订单号
        $trade_no = $verify['trade_no']; #交易订单号
        $order_type = $verify['order_type']; #交易类型

        $update_result = updateOrder($out_trade_no, $trade_no, $order_type);
        exit($update_result ? 'success' : 'fail');
    }


    /**
     * 支付接口同步返回路径
     */
    public function alipay_return() {
        $this->return_verify('alipay');
    }

    /**
     * 银联同步通知
     */
    public function unionpay_return() {
        $this->return_verify('unionpay');
    }


    public function return_verify($payment_code){

        $logic_payment = model('payment', 'logic');
        //取得支付方式
        $result = $logic_payment->getPaymentInfo($payment_code);
        if (!$result['code']) {
            $this->error($result['msg'], 'Memberorder/index');
        }
        $payment_info = $result['data'];

        //创建支付接口对象
        $payment_api = new $payment_info['payment_code']($payment_info);

        //返回参数判断
        $verify = $payment_api->return_verify();
        if (!$verify || $verify['trade_status']=='0') {
            $this->error(lang('payment_data_validation_failed'), 'Memberorder/index');
        }
        $order_type=$verify['order_type'];
        $out_trade_no=$verify['out_trade_no'];
        $order_amount=$verify['total_fee'];
        //支付成功后跳转
        if ($order_type == 'real_order') {
            $pay_ok_url = HOME_SITE_URL . '/buy/pay_ok?pay_sn=' . $out_trade_no . '&pay_amount=' . ds_price_format($order_amount);
        } elseif ($order_type == 'vr_order') {
            $pay_ok_url = HOME_SITE_URL . '/buyvirtual/pay_ok?order_sn=' . $out_trade_no . '&order_amount=' . ds_price_format($order_amount);
        } elseif ($order_type == 'pd_order') {
            $pay_ok_url = HOME_SITE_URL . '/predeposit/index';
        }
        header("Location:$pay_ok_url");
        exit;
    }

    /**
     * 银联异步通知
     */
    public function unionpay_notify(){
        $this->notify('unionpay');
    }
    /**
     * 微信扫码支付异步通知
     */
    public function wxpay_native_notify() {
        $this->notify('wxpay_native');
    }
     /**
     * 小程序支付异步通知
     */
    public function wxpay_minipro_notify() {
        $this->notify('wxpay_native','wxpay_minipro');
    }
     /**
     * 微信支付支付异步通知
     */
    public function wxpay_jsapi_notify() {
        $this->notify('wxpay_native','wxpay_jsapi');
    }
    /**
     * 微信H5支付异步通知
     */
    public function wxpay_h5_notify() {
        $this->notify('wxpay_native','wxpay_h5');
    }
    /**
     * 微信APP支付异步通知
     */
    public function wxpay_app_notify() {
        $this->notify('wxpay_native','wxpay_app');
    }
    /**
     * 通知处理(支付宝异步对账)
     */
    public function alipay_notify() {
        $this->notify('alipay');
    }
    /**
     * 支付宝APP支付异步通知
     */
    public function alipay_app_notify() {
        $this->notify('alipay_app');
    }
    /**
     * 支付宝wap支付异步通知
     */
    public function alipay_h5_notify() {
        $this->notify('alipay','alipay_h5');
    }


}

?>
