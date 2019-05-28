<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;



use think\Model;

//邮件模板模型
class MailerTemplate extends Common
{
    protected $pk = 'template_id';
    protected $name = 'mail_templates';
    protected $resultSetType = 'collection';

    protected $autoWriteTimestamp = true;
    protected $createTime = 'last_modify'; //添加时间
    protected $updateTime = 'last_modify'; //修改时间

    static function load_tpls() {
        $tpls = session('tpls');
        if (!$tpls) {
            $file = APP_PATH.'mailer.php';
            if (file_exists($file)) {
                $tpls = include_once($file);
                session('tpls', $tpls);
            } else {
                $tpls = [];
            }
        }

        return $tpls;
    }

    static function load_tpl($template_code) {
        if (!$template_code) {
            return [];
        }

        $tpl = self::get(['template_code'=>$template_code]);
        if ($tpl) {
            return $tpl;
        }

        $tpls = static::load_tpls();
        foreach ($tpls as $k => $v) {
            if ($template_code == $v['template_code']) {
                return $v;
            }
        }

        return [];

    }
}
