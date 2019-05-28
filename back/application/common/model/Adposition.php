<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-7
 * Time: 0:20
 */
namespace app\common\model;

use think\Model;
class Adposition extends Model {
    protected $pk = 'position_id';
    protected $name = 'ad_position';
    protected $resultSetType = 'collection';

    function getAds($position_model) {
        $position = self::get(['position_model'=>$position_model]);
        if ($position) {
            $position_id = $position['position_id'];

            $ads = Ad::all(['position_id'=>$position_id, 'status'=>1]);
            return $ads;
        }

        return [];
    }

    function ads() {
        return $this->hasMany('Ad','position_id', 'position_id');
    }


}
