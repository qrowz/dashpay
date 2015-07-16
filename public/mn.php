<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/class/easydarkcoin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

$donate = "XkB8ySpiqyVHeAXHsNhU83mUJ7Jd3CJaqW:10";
$name = "mn1";
$ip = "149.202.138.61:9999";
$darkcoin = new Darkcoin('xxx','xxx','localhost','9998');

$tx = 'd2df1e0f0aa308b0ada0a88291e93429d52977f05fc831dc741348c5c9055c63';
$raw_tx = $darkcoin->getrawtransaction($tx);
$decode_tx = $darkcoin->decoderawtransaction($raw_tx);
//var_dump($decode_tx["vout"]);

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

echo "$name $ip $mn_key $tx $outputs $donate";
