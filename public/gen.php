<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/wallets.php');

$store_id = (int)$_GET['sid']; $payer_id = (int)$_GET['pid'];
if(empty($store_id) || empty($payer_id)) die;

echo gen_address($store_id, $payer_id);
