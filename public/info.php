<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

$query = $db->prepare("SELECT * FROM `income` WHERE `sid` =:sid AND `txid` = :txid AND `balance` = :balance");
$query->bindParam(':sid', $_GET['sid'], PDO::PARAM_STR);
$query->bindParam(':txid', $_GET['txid'], PDO::PARAM_STR);
$query->bindParam(':balance', $_GET['amount'], PDO::PARAM_STR);
$query->execute();
	
if($query->rowCount() == 1){
$row = $query->fetch();
echo $row['status'];
}


