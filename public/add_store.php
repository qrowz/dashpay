<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

if(empty($_POST['name']) || empty($_POST['url'])) die('er_empty');

if(preg_match('/[^0-9a-zA-Z.-]/', $_POST['name']))	die('er_name');
if(preg_match('/[^0-9a-zA-Z:\/.-]/', $_POST['url']))	die('er_url');



// Проверим есть ли такой сайт в базе
$select_query = $db->prepare("SELECT * FROM `sites` WHERE `url` = :url");
$select_query->bindParam(':url', $_POST['url'], PDO::PARAM_STR);
$select_query->execute();
if($select_query->rowCount() > 0) die('er_add');

$insert_query = $db->prepare("INSERT INTO `sites` (`uid`, `name`, `url`) VALUES (:uid, :name, :url)");
$insert_query->bindParam(':uid', $user['id'], PDO::PARAM_STR);
$insert_query->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
$insert_query->bindParam(':url', $_POST['url'], PDO::PARAM_STR);
$insert_query->execute();
$page = $db->lastInsertId();

echo $page;
