<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-24
 * Time: 15:41
 */
namespace app\common\model;

class TemplatePage extends Common
{
    protected $name = 'template_page';
    protected $pk = 'pageid';

    protected $resultSetType = 'collection';

    protected $autoWriteTimestamp = 'dateline';
    protected $createTime = 'dateline';
}