<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-6-6
 * Time: 3:40
 */
namespace app\api\controller;

use app\admin\controller\Adminbase;
use org\Http;
use org\Wechat;
use PHPZxing\PHPZxingDecoder;
use think\Controller;
use think\db\Where;

class Fav extends Apibase
{
    //收藏列表
    public function getlist() {
        $where = new Where();
        $where['a.member_id'] = $this->member->member_id;
        $where['b.is_delete'] = 0;

        $fav_model = model('favorites');
        $paginate = $fav_model->alias('a')->leftJoin('goods b', 'a.goods_id = b.goods_id')->field('b.*')->where($where)->order('a.fav_time desc')->paginate()->toArray();
        foreach ($paginate['data'] as $k => $v) {
            $paginate['data'][$k]['goods_pic'] = request()->rootUrl(true).'/'.$v['goods_pic'];
        }
        $this->result($paginate, 1, '数据初始化成功', 'json');
    }
}
