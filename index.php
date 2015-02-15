<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/wallets.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/auth.php');

switch(@$_GET['do']){
	default: 
		if(isset($_SESSION['sess']))	require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/index.php');
			else						require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/login.php');
	break;

	case 'edit': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/edit.php'); break;
	case 'stat': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/stat.php'); break;
	case 'market': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/market.php'); break;
	case 'bank': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/bank.php'); break;
	case 'test': require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/test.php'); break;
	case 'exit': _exit(); break;
}
