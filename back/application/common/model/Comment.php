<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-3-7
 * Time: 1:00
 */
namespace app\common\model;


class Comment extends Common
{
    protected $name = 'comment';
    protected $pk = 'comment_id';
    protected $resultSetType = 'collection';
}
