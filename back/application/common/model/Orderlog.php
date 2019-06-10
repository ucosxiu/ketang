<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//订单日志模型
class Orderlog extends Common
{
    protected $pk = 'log_id';
    protected $name = 'order_log';
    protected $resultSetType = 'collection';
}
