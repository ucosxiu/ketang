<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-24
 * Time: 16:15
 */
namespace app\common\validate;

use think\Validate;

class TemplateCategory extends Validate {
    protected $rule = array(
        'tc_name'  		=> 'require'
    );
    protected $message = array(
        'tc_name.require'    	=> '分类名不能为空',
    );
}
