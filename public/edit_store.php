<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');


if(preg_match('/[^0-9]/', $_POST['id']))		die('er_id');
if(preg_match('/[^0-9a-zA-Z:\/._-]/', $_POST['url']))	die('er_surl');
if(preg_match('/[^0-9a-zA-Z.-]/', $_POST['key']))	die('er_key');

if(empty($_POST['id']) || empty($_POST['url']) || empty($_POST['key'])) die('er_empty');

if(strlen($_POST['key']) != 32) die('er_key2');


// Проверяем права
$query = $db->prepare("SELECT * FROM `sites` WHERE `uid` = :uid AND  `id` = :id");
$query->bindParam(':uid', $user['id'], PDO::PARAM_STR);
$query->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
$query->execute();
if($query->rowCount() != 1) return 'er_ac';

// Обновляем

$query = $db->prepare("UPDATE `sites` SET `surl` = :url, `secret` = :key WHERE `id` = :id ");
$query->bindParam(':url', $_POST['url'], PDO::PARAM_STR);
$query->bindParam(':key', $_POST['key'], PDO::PARAM_STR);
$query->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
$query->execute();

echo "ok";
