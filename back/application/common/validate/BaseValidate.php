<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 21:32
 */
namespace app\common\validate;
use think\Validate;

//公共验证类
class BaseValidate{
    static $scene = [];

    //验证
    static function validate($data, $scene='') {
        $validate = new Validate(static::$rule, static::$msg);

        if (static::$scene) {
            $validate->setScene(static::$scene);
        }

        $result = $validate->check($data, [], $scene);
        if ($result) {
            return true;
        } else {
            return $validate->getError();
        }
    }
}
