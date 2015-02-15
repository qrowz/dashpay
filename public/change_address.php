<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/wallets.php');

$isvalid = $bitcoin->validateaddress($_POST['address']);
	if(!$isvalid['isvalid']) die("invalid");
	
$query = $db->prepare("UPDATE `users` SET `address` =:address WHERE `id` = :id");
$query->bindParam(':address', $_POST['address'], PDO::PARAM_STR);
$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
$query->execute();

echo "ok";
