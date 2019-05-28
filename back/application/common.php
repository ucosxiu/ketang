<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
/* 引用全局定义 */
require __DIR__ . '/common_global.php';
/* 引用订单函数 */
require __DIR__ . '/common_order.php';

// 应用公共文件
function resultArray($array, $code=1)
{
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 1;
    } elseif (isset($array['error'])) {
        $code = 0;
        $array['data'] = '';
    }
    $re = [
        'code'  => $code,
        'data'  => $array['data'],
        'href'  => isset($array['href']) ? $array['href'] : '',
        'error' => $array['error']
    ];
    foreach ($array as $key => $value) {
        if (!in_array($key, ['data', 'error'])) {
            $re[$key] = $value;
        }
    }

    exit(json_encode($re));
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5($str . $auth_key);
}

function base64Upload($base64) {
    $base64_image = str_replace(' ', '+', $base64);
    //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
        //匹配成功
        if($result[2] == 'jpeg'){
            $ext = '.jpg';
            //纯粹是看jpeg不爽才替换的
        }else{
            $ext = '.'.$result[2];
        }
        $root_path = SITE_PATH . DIRECTORY_SEPARATOR .'public';
        $savename = date('Ymd') . DIRECTORY_SEPARATOR . md5(microtime(true));
        $filename = $root_path . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . $savename.$ext;
        checkPath(pathinfo($filename, PATHINFO_DIRNAME ));

        //服务器文件存储路径
        if (file_put_contents($filename, base64_decode(str_replace($result[1], '', $base64_image)))){
            $f = 'assets' . DIRECTORY_SEPARATOR . $savename.$ext;
            return $f;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function checkPath($path) {
    if (is_dir($path)) {
        return true;
    }

    if (mkdir($path, 0755, true)) {
        return true;
    }
}

if (!function_exists('random_string')) {
    /**
     * Url生成
     * @param string        $url 路由地址
     * @param string|array  $vars 变量
     * @param bool|string   $suffix 生成的URL后缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function random_string($len, $flag = false)
    {
        $chars1 = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0"
        );

        $chars2 = ["1", "2",
            "3", "4", "5", "6", "7", "8", "9"];

        $chars = $flag == true ? $chars2 : array_merge($chars1, $chars2);;

        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}

/**
 * 发送邮件
 * @param $to
 * @param $template_code
 * @param $title
 * @param $content
 * @param array $data
 * @return bool
 * @throws \think\Exception\DbException
 */
function sendMail($to, $template_code, $title, $content, $data=[]) {
    if ($template_code) {
        $template = \app\common\model\MailerTemplate::get(['template_code' => $template_code, 'status'=>1]);
        if ($template) {
            $title = $template->template_subject;
            $content = $template->template_content;
            preg_match_all('/\{\$(.*?)\}/', $content, $matches);
            foreach ($matches[1] as $vo) {
                $content = str_replace('{$' . $vo . '}', $data[$vo], $content);
            }
        }
    }

    if (!$title || !$content ) {
        return false;
    }
    $config = \app\common\model\Option::getOption('smtp_setting');

    $mail = new \org\Email($config);
    $mail->setMail($title, $content);
    $result = $mail->sendMail($to);
    return $result;
}

/**
 * 读
 * @param $key
 * @param bool $callback
 * @return mixed
 * @throws Exception
 */
function rkcache($key, $callback = false)
{
    $value = cache($key);
    if (empty($value) && $callback !== false) {
        if ($callback === true) {
            $callback = array(model('cache'), 'call');
        }

        if (!is_callable($callback)) {
            exception('Invalid rkcache callback!');
        }
        $value = call_user_func($callback, $key);
        wkcache($key, $value);
    }
    return $value;
}

/**
 * 写
 * @param $key
 * @param $value
 * @param int $expire
 * @return mixed
 */
function wkcache($key, $value, $expire = 7200)
{
    return cache($key, $value, $expire);
}

/**
 * @param $key
 * @return mixed
 */
function dkcache($key)
{
    return cache($key, NULL);
}

/**
 * 去除字符串末尾的PKCS7 Padding
 * @param string $source    带有padding字符的字符串
 */
function stripPKSC7Padding($source){
    $block = mcrypt_get_block_size('tripledes', 'cbc');
    $pad = ord(substr($source, -1));
//    if ($pad < 1 || $pad > $block)
//        $pad = 0;
    if ($pad < 1) {
        $pad = 0;
    }
    return substr($source, 0, (strlen($source) - $pad));
}

/**
 * 为字符串添加PKCS7 Padding
 * @param string $source    源字符串
 */
function addPKCS7Padding($source){
    $block = mcrypt_get_block_size('tripledes', 'cbc');
    $pad = $block - (strlen($source) % $block);
    if ($pad <= $block) {
        $char = chr($pad);
        $source .= str_repeat($char, $pad);
    }
    return $source;
}

/**
 * 解密
 * @param String input 解密的字符串
 * @param String key   解密的key
 * @return String
 */
function aesDecrypt($sStr) {
    $key = 'contentWindowHig';
    $iv = 'contentDocuments';
    if (version_compare(PHP_VERSION,'7.1.0','ge')) {
        $decrypted = openssl_decrypt(base64_decode($sStr),"aes-256-cbc", md5($key),OPENSSL_RAW_DATA,  $iv);

       return trim($decrypted);
    } else {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($key), base64_decode($sStr), MCRYPT_MODE_CBC, $iv);
        return stripPKSC7Padding($decrypted);
    }

//    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($key), base64_decode($sStr), MCRYPT_MODE_CBC, $iv);

}

/**
 * 加密
 * @param String input 加密的字符串
 * @param String key   加密的key
 * @return String
 */
function aesEncrypt($sStr) {
    $key = 'contentWindowHig';
    $iv = 'contentDocuments';
    $sStr = addPKCS7Padding($sStr);
    $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($key), $sStr, MCRYPT_MODE_CBC, $iv);
    return $encrypted = base64_encode($encrypted);
}


function sendTemplateFromId($openid, $template_id='', $params = []) {
    $wechat = new \org\Wechat([]);

    $access_token = cache('access_token');
    if (!$access_token) {
        $wechattoken = $wechat->getAccess_token();
        if ($wechattoken) {
            $access_token = $wechattoken['access_token'];
            cache('access_token', $access_token, 7200);
        }
    }

    $time = time();
    $template_id = '8A1yTDRfN3xMngcg-jNNoVq-1-W93wYSohpG05CjkpE';

    $templateformid_model = new \app\common\model\TemplateFormId();
    $form = $templateformid_model->getFirstFormid();
    if ($access_token && $form) {
        $openid = $openid;
        $formid = $form->formid;
        $template_id = $template_id;

        $data = array(
            'touser' => $openid, //要发送给用户的openid
            'template_id' => $template_id,//改成自己的模板id，在微信后台模板消息里查看
            'form_id' => $formid,
            'data' => array(
                'keyword1' => array(
                    'value' =>  substr($params['keyword1'], 0, 30),
                    'color' => "#000"
                ),
                'keyword2' => array(
                    'value' => date('m月d日 H:i:s', $params['keyword2']),
                    'color' => "#000"
                ),
                'keyword3' => array(
                    'value' => substr($params['keyword3'], 0, 30),
                    'color' => "#000"
                ),
                'keyword4' => array(
                    'value' => substr($params['keyword4'], 0, 30),
                    'color' => "#000"
                )
            )
        );
        $re = $wechat->templatesend($access_token, $data);
        $form->status = 1;
        $form->save();
    }
}

/**
 * 生成二维码 并保持
 * @param string $path
 * @param $scene
 * @return bool|string
 */
function saveQrcode($path = '', $scene) {
    $result = getWXACode($path, $scene);
    if ($result) {
        $root_path = BASE_UPLOAD_PATH;
        $url = 'qrcode'. DIRECTORY_SEPARATOR .md5(time().rand(100000, 999999)).'.png';
        $dir = dirname($root_path . $url);
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }

        checkPath(pathinfo($root_path . DIRECTORY_SEPARATOR . $url, PATHINFO_DIRNAME ));

        file_put_contents($root_path . DIRECTORY_SEPARATOR . $url, $result);
        return str_replace('\\', '/', DIR_UPLOAD. DIRECTORY_SEPARATOR .$url);
    }

    return false;
}

/**
 * @param string $path
 * @param int $width
 */
function getWXACode($path = '', $scene = '', $width=430) {
    $wechat = new \org\Wechat([]);

    $access_token = cache('access_token');
    if (!$access_token) {
        $wechattoken = $wechat->getAccess_token();
        if ($wechattoken) {
            $access_token = $wechattoken['access_token'];
            cache('access_token', $access_token, 7200);
        }
    }

    $result  = $wechat->getWXACode($access_token, $path, $scene, $width);
    if (is_array($result) && isset($result['errcode'])) {
        return false;
    } else {
        return $result;
    }
}

/**
 * 获取CMF上传配置
 */
function get_upload_setting()
{
    $uploadSetting = [];
    if (empty($uploadSetting) || empty($uploadSetting['file_types'])) {
        $uploadSetting = [
                'file_types' => [
                'image' => [
                    'upload_max_filesize' => '10240',//单位KB
                    'extensions'          => 'jpg,jpeg,png,gif,bmp4'
                ],
                'video' => [
                    'upload_max_filesize' => '10240',
                    'extensions'          => 'mp4,avi,wmv,rm,rmvb,mkv'
                ],
                'audio' => [
                    'upload_max_filesize' => '10240',
                    'extensions'          => 'mp3,wma,wav'
                ],
                'file'  => [
                    'upload_max_filesize' => '10240',
                    'extensions'          => 'txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar'
                ]
            ],
            'chunk_size' => 512,//单位KB
            'max_files'  => 20 //最大同时上传文件数
        ];
    }

    if (empty($uploadSetting['upload_max_filesize'])) {
        $uploadMaxFileSizeSetting = [];
        foreach ($uploadSetting['file_types'] as $setting) {
            $extensions = explode(',', trim($setting['extensions']));
            if (!empty($extensions)) {
                $uploadMaxFileSize = intval($setting['upload_max_filesize']) * 1024;//转化成B
                foreach ($extensions as $ext) {
                    if (!isset($uploadMaxFileSizeSetting[$ext]) || $uploadMaxFileSize > $uploadMaxFileSizeSetting[$ext]) {
                        $uploadMaxFileSizeSetting[$ext] = $uploadMaxFileSize;
                    }
                }
            }
        }

        $uploadSetting['upload_max_filesize'] = $uploadMaxFileSizeSetting;
    }

    return $uploadSetting;
}

/**
 * 获取某日最后时间戳
 * @param $date
 * @return false|int
 */
function date_last_time($time) {
    return  strtotime(date('Y-m-d 23:59:59', $time));
}



/**
 * 生成20位编号(时间+微秒+随机数+会员ID%1000)，该值会传给第三方支付接口
 * 长度 =12位 + 3位 + 2位 + 3位  = 20位
 * 1000个会员同一微秒提订单，重复机率为1/100
 * @return string
 */

function makePaySn($member_id) {
    return date('ymdHis',  time()).sprintf('%03d', (float) microtime() * 1000) .mt_rand(10, 99).sprintf('%03d', intval($member_id) % 1000);
}


/**
 * 生成20位编号(时间+微秒+随机数+会员ID%1000)
 * 长度 =14位 + 3位 + 2位 + 3位  = 20位
 * 1000个会员同一微秒提订单，重复机率为1/100
 * @return string
 */
function makeOrderSn()
{
    return date('YmdHis',  time()).sprintf('%03d', (float) microtime() * 1000) .mt_rand(100, 999);
}

/**
 *
 * @param type $out_trade_no  #商城内部订单号
 * @param type $trade_no  #支付交易流水号
 * @param type $order_type  #订单ID
 */
function updateOrder($out_trade_no, $trade_no, $order_type) {
    $out_trade_no = current(explode('_', $out_trade_no));
    $order_model = model('order');
    $condition = array();
    $condition['pay_sn'] = $out_trade_no;

    $order_pay_info = $order_model->getOrderpayInfo($condition);
    if (empty($order_pay_info)) {
        return false;
    }
    if ($order_pay_info['order_state'] > 10) {
        return true;
    }

    if ($order_pay_info['order_state'] == 10) {
        $data = [];
        $data['pay_code'] = 'online';
        $data['order_state'] = 20;
        $data['pay_time'] = request()->time();
        $order_model->save($data, $condition);
    }
    return true;
}


function exportExcel($expTitle, $expCellName, $expTableData){
    include_once SITE_PATH .DIRECTORY_SEPARATOR . 'vendor' .DIRECTORY_SEPARATOR . 'phpoffice' . DIRECTORY_SEPARATOR . 'phpexcel' . DIRECTORY_SEPARATOR . 'Classes' . DIRECTORY_SEPARATOR . 'PHPExcel.php';
    $fileName = date('Ymd', time()) .rand(1000, 9999);

    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称

    $cellNum = count($expCellName);

    $dataNum = count($expTableData);

    $objPHPExcel = new \PHPExcel();

    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');


    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格


    for($i=0; $i<$cellNum; $i++){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);

    }

    // Miscellaneous glyphs, UTF-8
    for($i=0; $i<$dataNum; $i++){
        for($j=0; $j<$cellNum; $j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), " " . $expTableData[$i][$expCellName[$j][0]]);

        }

    }

    header('pragma:public');

    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');

    header("Content-Disposition:attachment;filename = $fileName.xls");//attachment新窗口打印inline本窗口打印

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

    $objWriter->save('php://output');

    exit;

}


function update_wechat($userinfo) {
    if (!empty($userinfo['openid'])) {
        $where = ['openid' => $userinfo['openid']];
        $member = \app\common\model\Member::get($where);
        if (!$member) {
            (new \app\common\model\Member())->allowField(true)->save($userinfo);
        } else {
            $member->allowField(true)->save($userinfo);
        }
    }
}

function getfirstchar($s0) {
    $fchar = ord(substr($s0, 0, 1));
    if (($fchar >= ord("a") and $fchar <= ord("z"))or($fchar >= ord("A") and $fchar <= ord("Z"))) return strtoupper(chr($fchar));
    $s = iconv("UTF-8", "GBK", $s0);
    $asc = ord($s{0}) * 256 + ord($s{1})-65536;
    if ($asc >= -20319 and $asc <= -20284)return "A";
    if ($asc >= -20283 and $asc <= -19776)return "B";
    if ($asc >= -19775 and $asc <= -19219)return "C";
    if ($asc >= -19218 and $asc <= -18711)return "D";
    if ($asc >= -18710 and $asc <= -18527)return "E";
    if ($asc >= -18526 and $asc <= -18240)return "F";
    if ($asc >= -18239 and $asc <= -17923)return "G";
    if ($asc >= -17922 and $asc <= -17418)return "H";
    if ($asc >= -17417 and $asc <= -16475)return "J";
    if ($asc >= -16474 and $asc <= -16213)return "K";
    if ($asc >= -16212 and $asc <= -15641)return "L";
    if ($asc >= -15640 and $asc <= -15166)return "M";
    if ($asc >= -15165 and $asc <= -14923)return "N";
    if ($asc >= -14922 and $asc <= -14915)return "O";
    if ($asc >= -14914 and $asc <= -14631)return "P";
    if ($asc >= -14630 and $asc <= -14150)return "Q";
    if ($asc >= -14149 and $asc <= -14091)return "R";
    if ($asc >= -14090 and $asc <= -13319)return "S";
    if ($asc >= -13318 and $asc <= -12839)return "T";
    if ($asc >= -12838 and $asc <= -12557)return "W";
    if ($asc >= -12556 and $asc <= -11848)return "X";
    if ($asc >= -11847 and $asc <= -11056)return "Y";
    if ($asc >= -11055 and $asc <= -10247)return "Z";
    return null;
}
