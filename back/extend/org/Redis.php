<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24
 * Time: 1:43
 */

namespace org;

class Redis{
    protected static $instance;
    protected static $config = [
        'host'    => "127.0.0.1",
        'port'    => 6379,
        'password' => 'redis@zxk@673',
        'timeout' => 0.25,
        'pconnect' => false,
    ];

    static function instance() {
        if (is_null(self::$instance)) {

            try {
                $redis = new \Redis;
                $redis->connect(static::$config['host'], static::$config['port'], static::$config['timeout']);

                if (static::$config['password']) {
                    //授权
                    $redis->auth(static::$config['password']);
                }
                self::$instance = $redis;
            } catch (\Exception $e) {
                throw $e;
            }
        }
        return self::$instance;
    }
}