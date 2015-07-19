<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/class/easydarkcoin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
$darkcoin = new Darkcoin('xxx','xxx','localhost','9998');
$info = $darkcoin->masternode('list');
$donate = "XkB8ySpiqyVHeAXHsNhU83mUJ7Jd3CJaqW:10";
$name = "mn1";
$auth = "secret";

function send_do($command, $ip, $key){
	global $auth;
	return file_get_contents("http://92.222.108.232/index.php?do=$command&ip=$ip&key=$key&auth=$auth");
}

function check_mn($ip){
	global $darkcoin, $info;
	if(@$info["$ip:9999"] == 'ENABLED'){
		$i = 'OK';
	}else{
		$i = 'NO';
	}
	return $i;
}

if(!empty($_GET['check'])){
$query = $db->query("SELECT * FROM `hosting`");
$query->execute();
$mn_all = $query->rowCount();
	while($row=$query->fetch()){
		if(check_mn($row['ip']) == 'OK'){
			$query_update = $db->prepare("UPDATE `hosting` SET `last` = :time WHERE `ip` = :ip");
			$query_update->bindParam(':time', time(), PDO::PARAM_STR);
			$query_update->bindParam(':ip', $row['ip'], PDO::PARAM_STR);
			$query_update->execute();
			echo $row['ip'];
		}
	}
die;
}

if(!empty($_GET['control'])){
	sleep(1);
	if(empty($_POST['key'])) die('no_key');
	$key = $_POST['key'];
	$query = $db->prepare("SELECT * FROM `hosting` WHERE `key` = :key");
	$query->bindParam(':key', $key, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) die('no_key');
	$row=$query->fetch();
	$ip = $row['ip'];	
	switch($_GET['control']){
		default: echo "no"; break;
		case 'restart': send_do('restart', $ip, $key); break;
		case 'log':
			send_do('log', $ip, $key);
			echo "http://92.222.108.232/$ip/debug.tar.gz";
		break;
		case 'status':
			echo check_mn($ip);
		break;
	}
	die;
}

if(!empty($_GET['download']) && $_GET['download'] == 'getfile'){
	header ("Accept-Ranges: bytes");
	header ("Connection: close");
	header ("Content-Transfer-Encoding: binary");
	header ("Content-Disposition: attachment; filename=masternode.conf");
	echo base64_decode(urldecode($_GET['data']));
	die;
}

sleep(1);

if(empty($_POST['txid'])) die("empty");
if(preg_match('/[^0-9a-z]/', $_POST['txid'])) die('wrong_txid');
$tx = $_POST['txid'];

// Проверим, есть ли такая MN
$query = $db->prepare("SELECT * FROM `hosting` WHERE `txid` = :txid");
$query->bindParam(':txid', $tx, PDO::PARAM_STR);
$query->execute();
if($query->rowCount() != 1){

	// Проверим, есть ли свободные места
	$query = $db->query("SELECT * FROM `hosting`");
	$query->execute();
	while($row=$query->fetch()){
		if(check_mn($row['ip']) == 'NO' && time()-60*60*24 > $row['last'] && time()-60*60*24 > $row['time']){
			$ip = $row['ip'];
			continue;
		}
	}
	
	if(empty($ip)) die('full');

	$raw_tx = $darkcoin->getrawtransaction($tx);
	if(empty($raw_tx)) die('wrong_txid');

	$decode_tx = $darkcoin->decoderawtransaction($raw_tx);
	if($decode_tx["vout"]['0']["value"] != 1000) die('not 1000 DASH TX');
	$outputs = $decode_tx["vout"]['0']["n"];

	$end_block = $darkcoin->getblockcount();
	$start_block = $end_block - 15;

	while($end_block != $start_block){ // check 15 conf
		$hash_block = $darkcoin->getblockhash($start_block);
		$info_block = $darkcoin->getblock($hash_block);
		
		if(in_array($tx, $info_block["tx"])){
			die("not_15_conf");
		}
		
		$start_block++;
	}

	$mn_key = $darkcoin->masternode('genkey');

	$query = $db->prepare("UPDATE `hosting` SET `txid` = :txid,`time` = :time, `out` = :out, `key` = :key  WHERE `ip` = :ip");
	$query->bindParam(':txid', $tx, PDO::PARAM_STR);
	$query->bindParam(':time', time(), PDO::PARAM_STR);
	$query->bindParam(':out', $outputs, PDO::PARAM_STR);
	$query->bindParam(':key', $mn_key, PDO::PARAM_STR);
	$query->bindParam(':ip', $ip, PDO::PARAM_STR);
	$query->execute();
}else{
	$row=$query->fetch();
	$ip = $row['ip'];
	$mn_key = $row['key'];
	$outputs = $row['out'];
	
	// Не отдаем приватный ключ MN после того как она запустилась.
	if(check_mn($ip) == 'OK' || time()-60*60*24 > $row['time']) die('mn_work');
}

if(empty(send_do('setup', $ip, $mn_key))){
	echo urlencode(base64_encode("$name $ip:9999 $mn_key $tx $outputs $donate"));
}else{
	echo 'error';
}
