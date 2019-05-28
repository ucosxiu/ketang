<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-16
 * Time: 20:07
 */
namespace com;

/**
 * aes 加密 解密类库
 * @by singwa
 * Class Aes
 * @package app\common\lib
 */
class Aes {

    private $key = null;
    private $iv = null;

    /**
     *
     * @param $key 		密钥
     * @return String
     */
    public function __construct() {
        // 需要小伙伴在配置文件app.php中定义aeskey
        $this->key = md5(config('app.aeskey'));
        $this->iv = config('app.aesiv');
    }

    /**
     * 加密
     * @param String input 加密的字符串
     * @param String key   解密的key
     * @return HexString
     */
    public function encrypt($input = '') {
        $cryptText = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $input, MCRYPT_MODE_CBC, $this->iv);
        $res = base64_encode($cryptText);

        return $res;

    }
    /**
     * 填充方式 pkcs5
     * @param String text 		 原始字符串
     * @param String blocksize   加密长度
     * @return String
     */
    private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * 解密
     * @param String input 解密的字符串
     * @param String key   解密的key
     * @return String
     */
    public function decrypt($sStr) {
        if (version_compare(PHP_VERSION,'7.1.0','ge')) {
            $decrypted = openssl_decrypt(base64_decode($sStr),"aes-256-cbc", $this->key,OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $this->iv);
        } else {
            $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($sStr), MCRYPT_MODE_CBC, $this->iv);
        }
        return trim($decrypted);
    }

}
