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

class Article extends Apibase
{
    //收藏列表
    public function detail() {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $article_model = model('article');
        $article = $article_model->get(['article_show' => 1, 'article_id' => $id]);
        if (!$article) {
            $this->result('', 0, lang('param_error'), 'json');
        }

        $this->result($article, 1, '数据初始化成功', 'json');
    }
}
