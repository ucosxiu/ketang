<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-7
 * Time: 0:21
 */
namespace app\common\model;

use think\Lang;
use think\Model;
class Ad extends Model {
    protected $pk = 'ad_id';
    protected $name = 'ad';
    protected $resultSetType = 'collection';
    protected $insert = ['listorder'];
    protected $update = ['listorder'];

    function positonname() {
        return $this->belongsTo('Adposition', 'position_id', 'position_id')->bind([
            'position_name' //绑定属性
        ]);
    }

    function position() {
        return $this->belongsTo('Adposition', 'position_id', 'position_id');
    }

    function setListorderAttr($value) {
        return $value ? $value : 10000;
    }

    public function getstatustextAttr($value,$data)
    {
        $status = [0=>'关闭',1=>'开启'];
        return $status[$data['status']];
    }

    function getAdsbyModel() {

    }

}
