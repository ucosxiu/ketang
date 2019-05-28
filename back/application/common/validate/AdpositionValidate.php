<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-7
 * Time: 0:29
 */
namespace app\common\validate;

class AdpositionValidate extends BaseValidate{
    static protected $rule = [
        'position_name'  => 'require|max:25',
        'ad_width'       =>  'require',
        'ad_height'       =>  'require',
        'position_model'       =>  'require',

    ];
    static protected $msg = [
        'position_name.require'  => '广告位名称不能为空！',
        'position_name.max'  => '广告位名称不能大于25个字符',
        'ad_width.require'       => '广告位宽度不能为空！',
        'ad_height.require'       => '广告位高度不能为空！',
        'position_model.require'       => '广告位结构不能为空！',
        'position_model.unique'       => '广告位结构已存在！',
    ];
}
