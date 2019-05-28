<?php /** @noinspection ALL */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//配置模型
class Config extends Common
{
    protected $pk = 'id';
    protected $name = 'config';
    protected $resultSetType = 'collection';

    public function editConfig($data) {
        if (empty($data)) {
            return false;
        }
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $tmp = array();
                $specialkeys_arr = array('statistics_code');
                $tmp['value'] = (in_array($k, $specialkeys_arr) ? htmlentities($v, ENT_QUOTES) : $v);
                $result = db('config')->where('code', $k)->update($tmp);
                dkcache('config');
            }
            return true;
        } else {
            return false;
        }
    }
}
