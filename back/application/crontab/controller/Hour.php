<?php

namespace app\crontab\controller;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Hour extends Command {

    /**
     * 执行频率常量 1小时
     * @var int
     */
   protected function configure() {
       parent::configure(); // TODO: Change the autogenerated stub
       $this->setName('hour');
   }

    protected function execute(Input $input, Output $output) {
       //定时处理 已经完成的活动订单
        $order_model = db('order');
        $orders = $order_model->alias('a')->join('activity b','a.activity_id = b.activity_id')->field('a.order_id')->where('b.end_time','<',TIMESTAMP)->where([
            'a.order_state' => ORDER_STATE_NEW
        ])->limit(0, 50)->select();

        foreach ($orders as $k => $v) {
            $order_model->where(['order_id' => $v['order_id']])->update([
                'order_state' => ORDER_STATE_CANCEL
            ]);
        }
    }
}
?>