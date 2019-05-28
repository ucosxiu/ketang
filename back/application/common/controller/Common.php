<?php
namespace app\common\controller;

use think\Controller;

class Common extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function miss()
    {
        if (Request::instance()->isOptions()) {
            return ;
        } else {
            echo 'vuethink接口';
        }
    }

}
