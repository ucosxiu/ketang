<?php /** @noinspection ALL */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-11-5
 * Time: 16:03
 */
namespace app\common\model;
use think\Model;

//小区模型
class Option extends Common
{
    protected $pk = 'optionid';
    protected $name = 'option';
    protected $resultSetType = 'collection';

    /**
     * 设置配置
     * @param string $key
     * @param mixed $data
     * @param bool $replace
     * @return Common|bool
     * @throws \think\Exception\DbException
     */
    static function setOption($key, $data, $replace = false) {
        if (!is_array($data) || empty($data) || !is_string($key) || empty($key)) {
            return false;
        }
        $key        = strtolower($key);
        $option     = [];

        $findOption = self::get(['option_name' => $key]);
        if ($findOption) {
            $findOption->option_value = serialize($data);
            $findOption->save();
        } else {
            $option['option_name']  = $key;
            $option['option_value'] = serialize($data);
            (new self)->save($option);
        }

        return true;
    }

    /**
     * 获取配置
     * @param $key
     * @return bool|mixed
     * @throws \think\Exception\DbException
     */
    static function getOption($key) {
        $findOption = self::get(['option_name' => $key]);
        if ($findOption) {
            return unserialize($findOption['option_value']);
        }
        return false;
    }
}
