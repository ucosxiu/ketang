<?php
// +----------------------------------------------------------------------
// | Description: 用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\common\model;


class Assets extends Common
{
    protected $name = 'assets';
    protected $pk = 'assetid';

    protected $autoWriteTimestamp = 'dateline';
    protected $createTime = 'dateline';
    protected $updateTime = 'dateline';
    protected $resultSetType = 'collection';

    /**
     * 获取所有上传的文件
     * @param array $contidion
     * @return array
     * @throws \think\exception\DbException
     */
    function getAssetList($contidion=[]) {
        return $this->where($contidion)->order('assetid desc')->field('assetid, width, height, url')->paginate()->toArray();
    }

    /**
     * 删除上传文件
     * @param array $contidion
     */
    function del($contidion=[]) {
        return $this->where($contidion)->update(['status'=>0]);
    }
}
