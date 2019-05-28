<?php

namespace app\crontab\controller;
use think\console\Command;
use think\Log;

class BaseCron extends Command {

    public function __construct(){

    }

    /**
     * 记录日志
     * @param unknown $content 日志内容
     * @param boolean $if_sql 是否记录SQL
     */
    protected function log($content, $if_sql = true) {

        Log::record('queue\\'.$content);
    }

}
?>
