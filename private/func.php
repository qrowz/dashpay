<?
function get_block_info($block){
	require_once('/var/www/midas/root/private/class/jsonRPCClient.php');
	$darkcoin = new jsonRPCClient('http://xxxx:yyyy@127.0.0.1:9998/');
	$hash_block = $darkcoin->getblockhash(intval($block));
	
	$info_block = $darkcoin->getblock($hash_block);
	$tx = $info_block["tx"][0];
	$diff = round($info_block["difficulty"]);
	$last_block = $block_id = $info_block["height"];
	$block_time = $info_block["time"];
	return $info_block;
}

function blockreward($block_id, $diff){
	global $db; $k = $block_id-1;
	$query = $db->prepare("SELECT * FROM `data` WHERE `bid` = :bid");
	$query->bindParam(':bid', $k, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1){
		$d = get_block_info($k);
		$diff = $d['difficulty'];
	}else{
		$row = $query->fetch();
		$diff = $row['diff'];
	}
	
	$blockreward = floor(2222222/(pow((($diff+2600)/9), 2)));
	if($blockreward > 25) $blockreward = 25;
	if($blockreward < 5) $blockreward = 5;
	for ($i=210240; $i<=$block_id; $i += 210240) {
		$blockreward -= $blockreward/14;
	}
	
	if($block_id > 158000+((576*30)* 17))
		$blockreward = $blockreward*40/100;
	elseif($block_id > 158000+((576*30)* 15))
		$blockreward = $blockreward*42.5/100;
	elseif($block_id > 158000+((576*30)* 13))
		$blockreward = $blockreward*45/100;
	elseif($block_id > 158000+((576*30)* 11))
		$blockreward = $blockreward*47.5/100;
	elseif($block_id > 158000+((576*30)* 9))
		$blockreward = $blockreward*50/100;
	elseif($block_id > 158000+((576*30)* 7))
		$blockreward = $blockreward*52.5/100;
	elseif($block_id > 158000+((576*30)* 6))
		$blockreward = $blockreward*55/100;
	elseif($block_id > 158000+((576*30)* 5))
		$blockreward = $blockreward*57.5/100;
	elseif($block_id > 158000+((576*30)* 4))
		$blockreward = $blockreward*60/100;
	elseif($block_id > 158000+((576*30)* 3))
		$blockreward = $blockreward*62.5/100;
	elseif($block_id > 158000+((576*30)* 2))
		$blockreward = $blockreward*65/100;
	elseif($block_id > 158000+((576*30)* 1))
		$blockreward = $blockreward*70/100;
	else
		$blockreward = $blockreward*75/100;
	
	return round($blockreward, 2);
}

function get_json_param($key){
	global $db;
	$query = $db->prepare("SELECT * FROM `params` WHERE `key` = :key");
	$query->bindParam(':key', $key, PDO::PARAM_STR);
	$query->execute();
	$row=$query->fetch();
	return json_decode($row['value'], true);
}

function secondsToTime($seconds) {
	$dtF = new DateTime("@0");
	$dtT = new DateTime("@$seconds");
	
	if($seconds > 60*60*24)
		return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes');
	elseif($seconds > 60*60)
		return $dtF->diff($dtT)->format('%h hours, %i minutes');
	else
		return $dtF->diff($dtT)->format('%i minutes');
}

function ghash($globall_hashrate){
	$hash_display = ($globall_hashrate/(1024*1024));	
	return round($hash_display, 2);
}

function auto_hash($globall_hashrate){
	if($globall_hashrate>(1024*1024*1024*1024)){
		$hash_display = ($globall_hashrate/(1024*1024*1024*1024));
		$hash_string = ' TH/s';
	} else if($globall_hashrate>(1024*1024*1024)){
		$hash_display = ($globall_hashrate/(1024*1024*1024));
		$hash_string = ' GH/s';
	} else if($globall_hashrate>(1024*1024)){
		$hash_display = ($globall_hashrate/(1024*1024));
		$hash_string = ' MH/s';
	} else if($globall_hashrate>1024){
		$hash_display = ($globall_hashrate/1024);
		$hash_string = ' KH/s';
	} else {
		$hash_display = $globall_hashrate;
		$hash_string = ' H/s';
	}
	
	return round($hash_display, 2).$hash_string;
}

function blockreward_all($block_id, $diff){
	global $db; $k = $block_id-1;
	$query = $db->prepare("SELECT * FROM `data` WHERE `bid` = :bid");
	$query->bindParam(':bid', $k, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1){
		$d = get_block_info(268646);
		$diff = $d['difficulty'];
	}else{
		$row = $query->fetch();
		$diff = $row['diff'];
	}
	$blockreward = floor(2222222/(pow((($diff+2600)/9), 2)));
	if($blockreward > 25) $blockreward = 25;
	if($blockreward < 5) $blockreward = 5;
	for ($i=210240; $i<=$block_id; $i += 210240){
		$blockreward -= $blockreward/14;
	}
	return round($blockreward, 2);
}

function myCmp($a, $b) {
	if ($a['reward'] === $b['reward']) return 0;
	return $a['reward'] < $b['reward'] ? 1 : -1;
}

