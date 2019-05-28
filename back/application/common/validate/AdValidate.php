<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-7
 * Time: 0:29
 */
namespace app\common\validate;

class AdValidate extends BaseValidate{
    static protected $rule = [
        'ad_img'  => 'require'

    ];
    static protected $msg = [
        'ad_img.require'  => '图片不能为空！',
    ];
}