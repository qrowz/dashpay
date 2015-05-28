<?
require_once('/var/www/midas/root/private/config.php');
require_once('/var/www/midas/root/private/init/mysql.php');
require_once('/var/www/midas/root/private/class/jsonRPCClient.php');

$darkcoin = new jsonRPCClient('http://xxxx:yyyy@127.0.0.1:9998/');
$list_nodes = $darkcoin->masternodelist('full');
$count_nodes = $darkcoin->masternode('count');

$query = $db->prepare("INSERT INTO `mn_count` (`count`, `time`) VALUES (:count, :time)");
$query->bindParam(':count', $count_nodes, PDO::PARAM_STR);
$query->bindParam(':time', time(), PDO::PARAM_STR);
$query->execute();

$clean_data = array();

foreach($list_nodes as $key => $value){
	$data =  explode(" ", substr(preg_replace('/ {2,}/',' ',$value), 1));
	$data_ip = explode(":", trim($key));
	
	$query_select = $db->prepare("SELECT * FROM `mn_data` WHERE `ip` = :ip");
	$query_select->bindParam(':ip', $data_ip[0], PDO::PARAM_STR);
	$query_select->execute();
	if($query_select->rowCount() != 1){
		$query = $db->prepare("INSERT INTO `mn_data` (`ip`, `port`, `status`, `version`, `address`) VALUES (:ip, :port, :status, :version, :address)");
		$query->bindParam(':ip', $data_ip[0], PDO::PARAM_STR);
		$query->bindParam(':port', $data_ip[1], PDO::PARAM_STR);
		$query->bindParam(':status', $data[0], PDO::PARAM_STR);
		$query->bindParam(':version', $data[1], PDO::PARAM_STR);
		$query->bindParam(':address', $data[2], PDO::PARAM_STR);
		$query->execute();
	}else{
		$query = $db->prepare("UPDATE `mn_data` SET `port` = :port, `status` = :status, `version` = :version, `address` = :address WHERE `ip` = :ip");
		$query->bindParam(':ip', $data_ip[0], PDO::PARAM_STR);
		$query->bindParam(':port', $data_ip[1], PDO::PARAM_STR);
		$query->bindParam(':status', $data[0], PDO::PARAM_STR);
		$query->bindParam(':version', $data[1], PDO::PARAM_STR);
		$query->bindParam(':address', $data[2], PDO::PARAM_STR);
		$query->execute();
	}
	
	array_push($clean_data, $data_ip[0]);
}

$query = $db->prepare("SELECT * FROM `mn_data`");
$query->execute();
while($row=$query->fetch()){
	if (!in_array($row['ip'], $clean_data)){
		$query_delete = $db->prepare("DELETE FROM `mn_data` WHERE `ip` = :ip");
		$query_delete->bindParam(':ip', $row['ip'], PDO::PARAM_STR);
		$query_delete->execute();
	}
}
