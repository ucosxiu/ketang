<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//订单模型
class Order extends Common
{
    protected $pk = 'order_id';
    protected $name = 'order';
    protected $resultSetType = 'collection';

    public function goods() {
        return $this->belongsTo('goods', 'goods_id', 'goods_id');
    }
    /**
     * 取单条订单信息
     * @access public
     * @author csdeshang
     * @param type $condition 条件
     * @param type $fields 字段
     * @param type $master 主服务器
     * @return type
     */
    public function getOrderInfo($condition = array(), $fields = '*', $master = false) {
        $order_info = model('order')->field($fields)->where($condition)->master($master)->find();

        if (empty($order_info)) {
            return array();
        }

        return $order_info;
    }

    /**
     * @param $order_info
     * @return string
     */
    function get_format_order_state($order_info) {
        $msg = '';
        switch ($order_info['order_state']) {
            case ORDER_STATE_NEW:
                $msg = '待支付';
                break;
            case ORDER_STATE_PAY:
            case ORDER_STATE_SUCCESS:
                $msg = '已完成';
                break;
            case ORDER_STATE_CANCEL:
                $msg = '已取消';
                break;
        }

        return $msg;
    }

    /**
     * 返回是否允许某些操作
     * @access public
     * @author csdeshang
     * @param type $operate 操作
     * @param type $order_info 订单信息
     * @return boolean
     */
    public function getOrderOperateState($operate, $order_info) {
        if (!is_array($order_info) || empty($order_info))
            return false;
        switch ($operate) {
            case 'buyer_cancel':
                //买家取消订单
                $state = $order_info['order_state'] == ORDER_STATE_NEW;
                break;
            case 'buyer_delete':
                $state = $order_info['order_state'] == ORDER_STATE_CANCEL && $order_info['buyer_delete'] == 0;
            case 'repay':
                $state = $order_info['order_state'] == ORDER_STATE_NEW && $order_info['buyer_delete'] == 0 && $order_info['is_delete'] == 0;
                break;
        }

        return $state;
    }

    /**
     * @param $order_info
     * @param $role
     * @param string $user
     * @param string $msg
     * @param bool $if_update_account
     * @return bool
     * @throws \Exception
     */
    public function changeOrderStateCancel($order_info, $role, $user = '', $msg = '', $if_update_account = true) {
        try {
            $order_model = model('order');
            $order_model->startTrans(); //开始事务
            $order_id = $order_info['order_id'];

            $update_order = array('order_state' => ORDER_STATE_CANCEL);

            $update = $this->editOrder($update_order, array('order_id' => $order_id));
            if (!$update) {
                exception('保存失败');
            }

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_msg'] = '下架了订单';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( ' . $msg . ' )';
            }
            $data['log_orderstate'] = ORDER_STATE_CANCEL;

            $this->addOrderlog($data);
            $order_model->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }

    }

    /**
     * 添加订单日志
     * @access public
     * @author csdeshang
     * @param type $data 数据信息
     * @return type
     */
    public function addOrderlog($data) {
        $data['log_role'] = str_replace(array('buyer', 'seller', 'system', 'admin'), array('买家', '商家', '系统', '管理员'), $data['log_role']);
        $data['log_time'] = TIMESTAMP;
        return model('orderlog')->insertGetId($data);
    }

    /**
     * 更改订单信息
     * @access public
     * @author csdeshang
     * @param array $data 数据
     * @param array $condition 条件
     * @param int $limit 限制
     * @return bool
     */
    public function editOrder($data, $condition, $limit = '') {
        $update = model('order')->where($condition)->limit($limit)->update($data);
        return $update;
    }
}
