<?
require_once('/var/www/midas/root/private/config.php');
require_once('/var/www/midas/root/private/init/mysql.php');

function cryptsy_price($id, $name){
	$i = json_decode(file_get_contents("http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=".$id), true);
	return ['price' => $i["return"]["markets"][$name]["lasttradeprice"], 'vol' => $i["return"]["markets"][$name]["volume"]];
}

$btc = cryptsy_price(2, 'BTC');
$dash = cryptsy_price(155, 'DRK');

$usd_dash = round($btc['price']*$dash['price'], 2);

$cur_cb = file_get_contents ("http://www.cbr.ru/scripts/XML_daily.asp");
preg_match_all("#<Valute ID=\"R01235\">.*<CharCode>(.*)</CharCode>.*<Value>(.*)</Value>.*</Valute>#sU", $cur_cb, $_usd);  
$usd = round(str_replace(',', '.', $_usd[2][0]), 2);

if(empty($usd)) die();

$data = ['rur' => $usd, 'dash_usd' => $usd_dash];

$query = $db->prepare("SELECT * FROM `params` WHERE `key` = 'rur'");
$query->execute();
if($query->rowCount() != 1){
	$query = $db->prepare("INSERT INTO `params` (`key`, `value`) VALUES ('rur', :value)");
	$query->bindParam(':value', json_encode($data), PDO::PARAM_STR);
	$query->execute();
}else{
	$query = $db->prepare("UPDATE `params` SET `value` =:value WHERE `key` = 'rur'");
	$query->bindParam(':value', json_encode($data), PDO::PARAM_STR);
	$query->execute();
}
