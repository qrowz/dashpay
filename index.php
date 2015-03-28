<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/wallets.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

switch(@$_GET['do']){
	default: 
		if(isset($_SESSION['sess']))	require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/news.php');
			else						require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/login.php');
	break;

	case 'stat': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/stat.php'); break;
	case 'market': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/market.php'); break;
	case 'exit': _exit(); break;
}
