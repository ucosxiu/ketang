<?php
/**
 * 此为PHP-SDK 2.0
 */
require_once(PLUGINS_PATH.DS.'snsapi'.DS.'qqweibo'.DS.'tencent.php' );
require_once(PLUGINS_PATH.DS.'snsapi'.DS.'qqweibo'.DS.'config.php' );

OAuth::init($client_id, $client_secret);
Tencent::$debug = $debug;

$url = OAuth::getAuthorizeURL($callback);
header('Location: ' . $url);
