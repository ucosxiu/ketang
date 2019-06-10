<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-6-10
 * Time: 2:35
 */
namespace app\api\controller;

use app\admin\controller\Adminbase;
use org\Http;
use org\Wechat;
use PHPZxing\PHPZxingDecoder;
use think\Controller;
use think\db\Where;

class Log extends Apibase
{
    //收藏列表
    public function getlist() {
//        $where = new Where();
//        $where['a.member_id'] = $this->member->member_id;
//        $where['b.is_delete'] = 0;
//
//        $fav_model = model('favorites');
//        $paginate = $fav_model->alias('a')->leftJoin('goods b', 'a.goods_id = b.goods_id')->field('b.*')->where($where)->order('a.fav_time desc')->paginate()->toArray();
//        foreach ($paginate['data'] as $k => $v) {
//            $paginate['data'][$k]['goods_pic'] = request()->rootUrl(true).'/'.$v['goods_pic'];
//        }
//        $this->result($paginate, 1, '数据初始化成功', 'json');
    }

    public function addLog() {
        $log_model = model('goodslog');
        $param = $this->request->param();
        $param['member_id'] = $this->member->member_id;
        $param['log_time'] = TIMESTAMP;

        $today = date('Ymd', TIMESTAMP);
        $param['log_date'] = $today;

        //查询今日记录是否已存在
        $where = new Where();
        $where['log_date'] = $today;
        $where['member_id'] = $this->member->member_id;
        $where['goods_id'] = $param['goods_id'];
        $goods_model = model('goods');
        $goods = $goods_model->get($param['goods_id']);
        $param['goods_name'] = $goods['goods_name'];

        $chapter_model = model('goodschapter');
        $chapter = $chapter_model->get($param['chapter_id']);
        $param['chapter_name'] = $chapter['chapter_name'];

        $log = $log_model->where($where)->find();
        if ($log) {
            $update = [
                'log_time' => TIMESTAMP,
                'chapter_id' => $param['chapter_id']
            ];
            $update['chapter_name'] = $chapter['chapter_name'];

            if ($log_model->where($where)->update($update)) {
                $this->result([], 1, '保存成功', 'json');
            } else {
                $this->result([], 0, '保存失败', 'json');
            }
        } else {
            if ($log_model->data($param)->save()) {
                $this->result([], 1, '保存成功', 'json');
            } else {
                $this->result([], 0, '保存失败', 'json');
            }
        }
    }
}
