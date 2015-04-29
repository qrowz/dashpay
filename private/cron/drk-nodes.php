<?php
require_once('/var/www/midas/root/private/config.php');
require_once('/var/www/midas/root/private/init/mysql.php');

function remove_ip($val){
	//return	preg_replace('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\:(\d+)(\s?)/', '', str_replace('"', "", $val));
	return	preg_replace('/:(\d+)(\s?)/', ' ', str_replace('"', "", $val));
}

function clean_arr($val){
	return array_unique(array_filter($val));
}

function host_to_ip($addr, $ips = ''){
	foreach($addr as $key => $value){
		$ips = $ips." ".gethostbyname($value);
	}
	return substr($ips, 1);
}

function clean_nodes($val){
	global $remove_nodes;
	array_push($remove_nodes, $val);
	return 0;
}

function search_node($addr, $last = null, $ips = '', $port = 7903){
	global $ctx, $list;
	foreach($addr as $key => $value){
		if(@inet_pton($value) === FALSE) continue;
		$ips = remove_ip(@file_get_contents("http://$value:$port/peer_addresses", 0, $ctx))." ".$ips;
	}
	
	if($last != null) $ips = $list." ".$ips;
	
	return $ips;
}

$ctx = stream_context_create(array('http' => array('timeout' => 3))); $table = ''; $remove_nodes = array(); 

$json = json_decode(file_get_contents("http://eu.p2pool.pl:7903/global_stats", 0, $ctx));
if(!empty($json->{"pool_hash_rate"})){
	$query = $db->prepare("INSERT INTO `global` (`hash`, `ghash`, `time`) VALUES (:hash, :ghash, :time)");
	$query->bindParam(':hash', $json->{"pool_hash_rate"}, PDO::PARAM_STR);
	$query->bindParam(':ghash', $json->{"network_hashrate"}, PDO::PARAM_STR);
	$query->bindParam(':time', time(), PDO::PARAM_STR);
	$query->execute();
	unset($json);
}

$hostname = 'p2pool.dashninja.pl dash.p2pools.us eu.p2pool.pl p2pool.crunchpool.com happymining.de';
$list = host_to_ip(explode(' ', $hostname));
$addr = clean_arr(explode(' ', search_node(explode(' ', $list)))); // first
$addr = clean_arr(explode(' ', search_node($addr, 'last'))); // all

foreach($addr as $key => $value){
	$uptime = @file_get_contents("http://$value:7903/uptime", 0, $ctx);
	if(empty($uptime)){ clean_nodes($value); continue; }
	$json = json_decode(@file_get_contents("http://$value:7903/local_stats", 0, $ctx), true);
	if(empty($json)){ clean_nodes($value); continue; }
	if(!empty($json['miner_hash_rates'])){
		$sum = array_sum($json['miner_hash_rates']);
		$users = count($json['miner_hash_rates']);
	}else{
		$sum = 0;
		$users = 0;
	}
	
	if(!is_numeric($sum) || !is_numeric($uptime) || !is_numeric($json['fee'])){ clean_nodes($value); continue; }
	
	$query_select = $db->prepare("SELECT * FROM `node` WHERE `ip` = :ip");
	$query_select->bindParam(':ip', $value, PDO::PARAM_STR);
	$query_select->execute();
	if($query_select->rowCount() != 1){
		$query_insert = $db->prepare("INSERT INTO `node` (`ip`, `country`, `users`, `hash`, `fee`, `uptime`) VALUES (:ip, :country, :users, :hash, :fee, :uptime)");
		$query_insert->bindParam(':ip', $value, PDO::PARAM_STR);
		$query_insert->bindParam(':country', geoip_country_name_by_name($value), PDO::PARAM_STR);
		$query_insert->bindParam(':users', $users, PDO::PARAM_STR);
		$query_insert->bindParam(':hash', round($sum), PDO::PARAM_STR);
		$query_insert->bindParam(':fee', round($json['fee'], 2), PDO::PARAM_STR);
		$query_insert->bindParam(':uptime', round($uptime), PDO::PARAM_STR);
		$query_insert->execute();
	}else{
		$query_update = $db->prepare("UPDATE `node` SET `country` = :country, `users` = :users, `hash` = :hash, `fee` =:fee, `uptime` = :uptime WHERE `ip` = :ip");
		$query_update->bindParam(':ip', $value, PDO::PARAM_STR);
		$query_update->bindParam(':country', geoip_country_name_by_name($value), PDO::PARAM_STR);
		$query_update->bindParam(':users', $users, PDO::PARAM_STR);
		$query_update->bindParam(':hash', round($sum), PDO::PARAM_STR);
		$query_update->bindParam(':fee', round($json['fee'], 2), PDO::PARAM_STR);
		$query_update->bindParam(':uptime', round($uptime), PDO::PARAM_STR);
		$query_update->execute();
	}
}

$query = $db->prepare("SELECT * FROM `node`");
$query->execute();
while($row=$query->fetch()){
	if (!in_array($row['ip'], $addr) || in_array($row['ip'], $remove_nodes)){
		$query_delete = $db->prepare("DELETE FROM `node` WHERE `ip` = :ip");
		$query_delete->bindParam(':ip', $row['ip'], PDO::PARAM_STR);
		$query_delete->execute();
	}
}
