<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-6
 * Time: 19:03
 */
namespace app\common\validate;
use think\Validate;

class MailerTemplateValidate extends BaseValidate{
    static $rule = [
        'template_subject'  => 'require|length:2,25',
        'template_code'     => 'require',
        'template_content'  => 'require',
    ];
    static $msg = [
        'template_code.require'             => '邮件模板不能为空',
        'template_code:unique'              => '邮件模板已存在',
        'template_subject.require'  => '邮件主题不能为空!',
        'template_subject.length'  => '邮件主题长度6-25位!',
        'template_content.require'  => '邮件不能为空',
        'template_subject.unique'   => '邮件主题已存在'

    ];
    static $scene =[
        'add'    => ['template_code'=>'unique:mailer_template']
    ];
}