<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//邮件模型
class Mailer extends Common
{
    protected $pk = 'optionid';
    protected $name = 'option';
    protected $resultSetType = 'collection';
}
