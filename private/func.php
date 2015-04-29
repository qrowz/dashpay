<?
function blockreward($block_id, $diff){
	if($block_id > 158000+((576*30)* 17))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*40/100, 3);
	elseif($block_id > 158000+((576*30)* 15))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*42.5/100, 3);
	elseif($block_id > 158000+((576*30)* 13))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*45/100, 3);
	elseif($block_id > 158000+((576*30)* 11))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*47.5/100, 3);
	elseif($block_id > 158000+((576*30)* 9))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*50/100, 3);
	elseif($block_id > 158000+((576*30)* 7))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*52.5/100, 3);
	elseif($block_id > 158000+((576*30)* 6))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*55/100, 3);
	elseif($block_id > 158000+((576*30)* 5))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*57.5/100, 3);
	elseif($block_id > 158000+((576*30)* 4))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*60/100, 3);
	elseif($block_id > 158000+((576*30)* 3))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*62.5/100, 3);
	elseif($block_id > 158000+((576*30)* 2))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*65/100, 3);
	elseif($block_id > 158000+((576*30)* 1))
		$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*70/100, 3);
	else
			$blockreward = round(2222222/(pow((($diff+2600)/9), 2))*75/100, 3);	
	return $blockreward;
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
	return round(2222222/(pow((($diff+2600)/9), 2))*75/100, 3);
}

function myCmp($a, $b) {
	if ($a['reward'] === $b['reward']) return 0;
	return $a['reward'] < $b['reward'] ? 1 : -1;
}

