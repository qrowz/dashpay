<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

if(empty($_POST['passwd'])) die('er_p');

$query = $db->prepare("SELECT * FROM `users` WHERE `id` = :id"); // Проверяем пароль
$query->bindParam(':id', $user['id'], PDO::PARAM_STR);
$query->execute();
$row = $query->fetch();

if (!password_verify($_POST['passwd'], $row['passwd'])) die('er_w');

echo "ok";
