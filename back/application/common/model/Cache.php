<?php

namespace app\common\model;

use think\Model;

class Cache extends Common {
    /**
     * @access public
     * @author csdeshang
     * @param string $method
     * @return boolean
     */
    public function call($method) {
        $method = '_' . strtolower($method);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            return false;
        }
    }

    /**
     * 基本设置
     * @access private
     * @author csdeshang
     * @return array
     */
    private function _config() {
        $result = db('config')->select();
        if (is_array($result)) {
            $list_config = array();
            foreach ($result as $k => $v) {
                $list_config[$v['code']] = $v['value'];
            }
        }
        unset($result);
        return $list_config;
    }
}
