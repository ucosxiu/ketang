<?php

namespace app\common\model;


class Adminlog extends Common {

    /**
     * 增加日子
     * @author csdeshang
     * @param type $data
     * @return type
     */
    public function addAdminlog($data) {
        return db('adminlog')->insertGetId($data);
    }

}
