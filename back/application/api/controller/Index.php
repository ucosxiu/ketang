<?php
namespace app\api\controller;

use org\Http;
use org\Wechat;
use org\wechat\SDK;
use PHPZxing\PHPZxingDecoder;
use think\Controller;

class Index extends Apibase
{
    /**
     * 执行方法
     */
    public function index() {
        // 事件类型
        $type = $this->weObj->getRev()->getRevType();
        $wedata = $this->weObj->getRev()->getRevData();

        // 接收消息
        switch ($type) {
            // 文本消息
            case SDK::MSGTYPE_TEXT:
                $keywords = $wedata['Content'];
                break;
            // 事件推送
            case SDK::MSGTYPE_EVENT:
                break;
            // 图片消息
            case SDK::MSGTYPE_IMAGE:
                $this->handle_image_message($wedata);
                break;
            // 语音消息
            case SDK::MSGTYPE_VOICE:
                exit();
                break;
            // 视频消息
            case SDK::MSGTYPE_VIDEO:
                exit();
                break;
            // 小视频消息
            case SDK::MSGTYPE_SHORTVIDEO:
                exit();
                break;
            // 地理位置消息
            case SDK::MSGTYPE_LOCATION:
                exit();
                break;
            // 链接消息
            case SDK::MSGTYPE_LINK:
                exit();
                break;
            default:
//                $this->msg_reply('msg'); // 消息自动回复
                exit();
        }
    }

    /**
     * @param $wedata
     * 处理图片消息
     */
    private function handle_image_message ($wedata) {
        $PicUrl = $wedata['PicUrl'];
        if (!$PicUrl) {

        }
        $http = new Http();
        $extension = 'png';
        $savename = 'assets' . DIRECTORY_SEPARATOR . 'voucher' . DIRECTORY_SEPARATOR . date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true)) . '.' . $extension;

        if (!file_exists(dirname($savename))) {
            mkdir(dirname($savename), 0777, true);
        }
        if($http::curlDownload($PicUrl, PUBLIC_PATH . DIRECTORY_SEPARATOR . $savename)){

            //http下载图片
            //识别图片二维码
            $decoder = new PHPZxingDecoder();
            $data = $decoder->decode(PUBLIC_PATH . '/' .$savename);
            $text = '';
            if($data->isFound()) {
                $text = $data->getImageValue();

                require_once EXTEND_ORG_PATH . '/aip-php-sdk/AipOcr.php';
                $app_id = '16329444';
                $api_key = 'Ckx20f7hGaEvYU6lXRSrCz9M';
                $secret_key = 'DazO9G5C1tz4VF1wsUc40otz3T3pv34b';
                $client = new \AipOcr($app_id, $api_key, $secret_key);

                $filename = PUBLIC_PATH . DIRECTORY_SEPARATOR . $savename;
                if (!file_exists($filename)) {
                    exit('文件不存在');
                }

                $image = file_get_contents($filename);
                // 调用通用文字识别, 图片参数为本地图片
                $result = $client->basicGeneral($image);
                $order_model = model('order');

                if ($result && $result['words_result']) {
                    $words_result = array_column($result['words_result'], 'words');
                    $words_result_str = implode('', $words_result);
                    $voucher_model = model('voucher');
                    $list = $voucher_model->getCache();
                    $find = [];
                    foreach ($list as $key => $value) {
                        preg_match('/(?P<name>('.$value['voucher_name'].'))/u', $words_result_str, $matches);
                        if ($matches) {
                            $find = $value;
                            break;
                        }
                    }
                    $member =  model('member')->get($this->member->member_id);
                    $code = $text;
                    if ($find) {
                        //查找到券商 插入订单
                        $insert = [];
                        $insert['qrcode_pic'] = $savename;
                        $insert['code'] = $code;
                        $insert['order_state'] = ORDER_STATE_NEW;
                        $insert['add_time'] = TIMESTAMP;
                        $insert['voucher_id'] = $find['voucher_id'];
                        $insert['vc_id'] = $find['vc_id'];
                        $insert['member_id'] = $member->member_id;
                        $insert['price'] = $find['price'];
                        $insert['order_sn'] = makeOrderSn();
                        $insert['is_vip'] = $member->is_vip;
                        if (!$order_model->get(['code'=>$code, 'voucher_id' => $find['voucher_id']])) {
                            $order_model->insertGetId($insert);
                            $a = "<a href=\"". $this->request->rootUrl(true) . '/' . Url('index/order/index')."\">订单管理</a>";
                            $text = '订单创建成果通知'. "\n卷码：". $text . "\n品类：" . $find['voucher_name'] . "\n未审核前，您可以在{$a}中撤销该操作";

                            $this->weObj->text($text)->reply();
                        }
                    }
                }
            }
        }
    }

    /**
     * 被动关注，消息回复
     *
     * @param string $type
     * @param string $return
     */
    private function msg_reply($type, $return = 0) {
        $this->weObj->text('sss')->reply();
    }
}
