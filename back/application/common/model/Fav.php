<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-29
 * Time: 16:28
 */
namespace app\common\model;


class Fav extends Common
{
    protected $name = 'fav';
    protected $pk = 'fav_id';

    protected $autoWriteTimestamp = 'dateline';
    protected $createTime = 'dateline';
    protected $updateTime = 'dateline';
    protected $resultSetType = 'collection';

    /**
     * @return \think\model\relation\BelongsTo
     */
    function template() {
        return $this->belongsTo('template', 'term_id', 'id')->bind([
            'template_id' => 'id',
            'template_name',
            'width',
            'height',
            'thumbnailUrl',
            'cat_2'
        ]);
    }

    /**
     *
     */
    function getFavsByUid($uid, $condition=[]) {
        $condition['uid'] = $uid;
        $condition['fav_type'] = 1;
        $paginate = $this->where($condition)->with('template')->paginate()->toArray();

        $category_model = \model('category');
        foreach ($paginate['data'] as $k => $v) {
            $paginate['data'][$k]['cat2'] = $category_model->field('tc_name')->where(['tc_id' => $v['cat_2']])->find();
        }
        return $paginate;
    }

    /**
     * @param $id
     * @param int $fav_type
     * @return float|string
     */
    function getTemplateCount($id, $fav_type = 0) {
        $condition = [];
        $condition['term_id'] = $id;
        $condition['fav_type'] = $fav_type;
        return $this->where($condition)->count();
    }
}
