<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/class/easydarkcoin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
$darkcoin = new Darkcoin('xxx','xxx','localhost','9998');
$donate = "XkB8ySpiqyVHeAXHsNhU83mUJ7Jd3CJaqW:10";
$name = "mn1";
$auth = "secret";

function send_do($command, $ip, $key){
	global $auth;
	return file_get_contents("http://92.222.108.232/index.php?do=$command&ip=$ip&key=$key&auth=$auth");
}

//d2df1e0f0aa308b0ada0a88291e93429d52977f05fc831dc741348c5c9055c63
if(!empty($_GET['download']) && $_GET['download'] == 'getfile'){
	header ("Accept-Ranges: bytes");
	header ("Connection: close");
	header ("Content-Transfer-Encoding: binary");
	header ("Content-Disposition: attachment; filename=masternode.conf");
	echo base64_decode(urldecode($_GET['data']));
	die;
}

if(empty($_POST['txid'])) die("empty");
if(preg_match('/[^0-9a-z]/', $_POST['txid'])) die('wrong_txid');
$tx = $_POST['txid'];

// Проверим, есть ли такая MN
$query = $db->prepare("SELECT * FROM `hosting` WHERE `txid` = :txid");
$query->bindParam(':txid', $tx, PDO::PARAM_STR);
$query->execute();
if($query->rowCount() != 1){

	// Проверим, есть ли свободные места
	$query = $db->prepare("SELECT * FROM `hosting` WHERE `txid` IS NULL");
	$query->execute();
	if($query->rowCount() == 0) die('full');
	$row=$query->fetch();
	$ip = $row['ip'];

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
			die("not 15 conf");
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
}

if(empty(send_do('setup', $ip, $mn_key))){
	echo urlencode(base64_encode("$name $ip $mn_key $tx $outputs $donate"));
}else{
	echo 'error';
}
