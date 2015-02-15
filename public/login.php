<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

if($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SESSION['sess']) && !empty($_POST['login']) && !empty($_POST['password'])){
 error(login($_POST['login'], $_POST['password']));

 } else echo "error10";