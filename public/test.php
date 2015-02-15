<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

if(!is_numeric($_POST['id'])) die('er_id');
if(!is_numeric($_POST['amount']) || $_POST['amount'] < 1) die('er_m');
if(preg_match('/[^0-9a-zA-Z:\/._-]/', $_POST['surl']))	die('er_url');
if(preg_match('/[^0-9a-zA-Z.-]/', $_POST['key']))	die('er_key');
if(preg_match('/[^0-9a-zA-Z_]/', $_POST['txid']))	die('er_txid');

if(strlen($_POST['key']) != 32) die('er_key2');

if(empty($_POST['txid']) || empty($_POST['surl']) || empty($_POST['key']) || empty($_POST['amount'])) die('er_empty');


$t = midas_crypt('encode', $_POST['key'], "1 {$_POST['id']} {$_POST['amount']} {$_POST['txid']}");
$q = midas_query($_POST['surl'], array('q' => $t, 'test' => '1'));


//if($q == 1) echo 'OK';
//	else	echo 'NO';

	echo $q;
