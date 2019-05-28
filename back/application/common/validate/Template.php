<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-24
 * Time: 16:15
 */
namespace app\common\validate;

use think\Validate;

class Template extends Validate {
    protected $rule = array(
        'width'  		=> 'require|number|between:40,:5000',
        'height'      	=> 'require|number|between:40,:5000',
    );
    protected $message = array(
        'width.require'    	=> '宽度必须填写',
        'width.between'      => '宽度在40到5000之间',
        'height.require'    => '高度必须填写',
        'height.between'      => '高度在40到5000之间',
    );
}