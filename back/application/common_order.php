<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-4-29
 * Time: 1:34
 */

/**
 * 返回某个订单可执行的操作列表，包括权限判断
 * @param   array   $order      订单信息 order_statw
 * @return  array   可执行的操作  audit, cancel
 * 格式 array('audit' => true)
 */
function operable_list($order) {
    $order_state = $order['order_state'];
    $in_issue = $order['in_issue'];
    $list = array();
    if ($in_issue == 0) {
        if ($order_state == ORDER_STATE_NEW) {
            //审核
            $list['audit'] = true;
        } elseif ($order_state == ORDER_STATE_SALE) {
            //出售中  直接完成 或者 纠纷中
            $list['complete'] = true;
            $list['issue'] = true;
        } elseif ($order_state == ORDER_STATE_CANCEL) {
            $list['delete'] = true;
        }
    }

    return $list;
}


// order_audit_agree 订单审核通过 非会员：添加冻结款 会员：添加可用余额
// order_complete 订单完成 非会员 解冻  会员：无
// order_issue_refuse 纠纷中拒绝  非会员：减少冻结款  会员：减少余额 （先判断余额是否足够）
//cash_apply申请提现冻结预存款,cash_pay提现成功 减少冻结款, cash_del取消提现申请，解冻预存款,


