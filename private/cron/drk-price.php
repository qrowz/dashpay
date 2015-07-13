<?
require_once('/var/www/midas/root/private/config.php');
require_once('/var/www/midas/root/private/init/mysql.php');

function cryptsy_price($id, $name, $dash = null){
	$i = @json_decode(file_get_contents("http://pubapi1.cryptsy.com/api.php?method=singlemarketdata&marketid=".$id), true);
	$pubapi = 'pubapi1';
	if($i == NULL){
		$i = json_decode(file_get_contents("http://pubapi2.cryptsy.com/api.php?method=singlemarketdata&marketid=".$id), true);
		$pubapi = 'pubapi2';
	}
	if($dash != null){
		$j = json_decode(file_get_contents("http://$pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=213"), true);
		$v = json_decode(file_get_contents("http://$pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=214"), true);
		$z = json_decode(file_get_contents("http://$pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=313"), true);
		return ['price' => $i["return"]["markets"][$name]["lasttradeprice"], 'vol' => $i["return"]["markets"][$name]["volume"]+$j["return"]["markets"][$name]["volume"]+$v["return"]["markets"][$name]["volume"]+$z["return"]["markets"][$name]["volume"]];
	}
	return ['price' => $i["return"]["markets"][$name]["lasttradeprice"], 'vol' => $i["return"]["markets"][$name]["volume"]];
}

function poloniex_price($name){
	$i = json_decode(file_get_contents("https://poloniex.com/public?command=returnTicker"), true);
	return ['price' => ($i[$name]["highestBid"]+$i[$name]["lowestAsk"])/2, 'vol' => $i[$name]["baseVolume"]];
}

function bter_price(){
	$i = json_decode(file_get_contents("http://data.bter.com/api/1/ticker/dash_btc"), true);
	$j = json_decode(file_get_contents("http://data.bter.com/api/1/ticker/dash_cny"), true);
	return ['price' => $i["avg"], 'vol' => $i["vol_btc"]+$j["vol_dash"]*$i["avg"]];
}

function bittrex_price(){
	$i = json_decode(file_get_contents("https://bittrex.com/api/v1.1/public/getmarketsummaries"), true);
	foreach($i["result"] as $key => $value){
		if($value['MarketName'] != "BTC-DASH") continue;
		$v = ['price' => $value["Last"], 'vol' => $value["Volume"]];
	}
	return $v;
}

function bitfinex_price(){
	$i = json_decode(file_get_contents("https://api.bitfinex.com/v1/pubticker/drkbtc"), true);
	$j = json_decode(file_get_contents("https://api.bitfinex.com/v1/pubticker/drkusd"), true);
	return ['price' => $i["mid"], 'vol' => $i["volume"]+$j["volume"]];
}


function cex_price(){
	$i = json_decode(file_get_contents("https://cex.io/api/ticker/DRK/BTC"), true);
	$j = json_decode(file_get_contents("https://cex.io/api/ticker/DRK/USD"), true);
	$v = json_decode(file_get_contents("https://cex.io/api/ticker/DRK/LTC"), true);
	return ['price' => $i["ask"], 'vol' => $i["volume"]+$j["volume"]+$v["volume"]];
}

function usecryptos_price(){
	$i = json_decode(file_get_contents("https://usecryptos.com/jsonapi/ticker/dash-btc"), true);
	//$j = json_decode(file_get_contents("https://usecryptos.com/jsonapi/ticker/dash-usd"), true);
	$k = json_decode(file_get_contents("https://usecryptos.com/jsonapi/ticker/dash-eur"), true);
	//return ['price' => $i["lastPrice"], 'vol' => $i["priVolume"]+$j["priVolume"]+$k["priVolume"]];
	return ['price' => $i["lastPrice"], 'vol' => $i["priVolume"]+$k["priVolume"]];
}

$btc = cryptsy_price(2, 'BTC');
$dash = cryptsy_price(155, 'DRK', 'lolka!');
$p_dash = poloniex_price('BTC_DASH');
$b_dash = bter_price();
$bi_dash = bitfinex_price();
$ce_dash = cex_price();
$bit_dash = bittrex_price();
$use_dash = usecryptos_price();

$c_val = round($dash['price']*$dash['vol']*$btc['price']);
$c_price= round($dash['price']*$btc['price'], 2);

$p_val = round($p_dash['vol']*$btc['price']);
$p_price= round($p_dash['price']*$btc['price'], 2);

$b_val = round($b_dash['vol']*$btc['price']);
$b_price= round($b_dash['price']*$btc['price'], 2);

$bi_val = round($bi_dash['vol']*$dash['price']*$btc['price']);
$bi_price= round($bi_dash['price']*$btc['price'], 2);

$ce_val = round($ce_dash['vol']*$dash['price']*$btc['price']);
$ce_price= round($ce_dash['price']*$btc['price'], 2);

$bit_val = round($bit_dash['vol']*$dash['price']*$btc['price']);
$bit_price= round($bit_dash['price']*$btc['price'], 2);

$use_val = round($use_dash['vol']*$dash['price']*$btc['price']);
$use_price= round($use_dash['price']*$btc['price'], 2);


$cryptsy = ['val' => $c_val, 'price' => $c_price, 'url' => 'https://www.cryptsy.com/'];
$poloniex = ['val' => $p_val, 'price' => $p_price, 'url' => 'https://poloniex.com/'];
$bter = ['val' => $b_val, 'price' => $b_price, 'url' => 'https://bter.com/'];
$bitfinex = ['val' => $bi_val, 'price' => $bi_price, 'url' => 'https://bittrex.com/'];
$cex = ['val' => $ce_val, 'price' => $ce_price, 'url' => 'https://cex.io/'];
$bittrex = ['val' => $bit_val, 'price' => $bit_price, 'url' => 'https://bittrex.com/'];
$usecryptos = ['val' => $use_val, 'price' => $use_price, 'url' => 'https://usecryptos.com/'];

$data = [ 'Cryptsy' => $cryptsy, 'Poloniex' => $poloniex, 'BTER' => $bter, 'Bitfinex' => $bitfinex, 'CEX.IO' => $cex, 'Bittrex' => $bittrex, 'UseCryptos' => $usecryptos];
arsort($data);

$query = $db->prepare("INSERT INTO `price` (`price`, `time`) VALUES (:price, :time)");
$query->bindParam(':price', round($dash['price']*$btc['price'], 2), PDO::PARAM_STR);
$query->bindParam(':time', time(), PDO::PARAM_STR);
$query->execute();

$query = $db->prepare("SELECT * FROM `params` WHERE `key` = 'markets'");
$query->execute();
if($query->rowCount() != 1){
	$query = $db->prepare("INSERT INTO `params` (`key`, `value`) VALUES ('markets', :value)");
	$query->bindParam(':value', json_encode($data), PDO::PARAM_STR);
	$query->execute();
}else{
	$query = $db->prepare("UPDATE `params` SET `value` =:value WHERE `key` = 'markets'");
	$query->bindParam(':value', json_encode($data), PDO::PARAM_STR);
	$query->execute();
}

$query = $db->prepare("SELECT * FROM `params` WHERE `key` = 'rur'");
$query->execute();
$row=$query->fetch();
$usd = json_decode($row['value'], true);

$all = $cryptsy['val']+$poloniex['val']+$bter['val']+$bitfinex['val']+$cex['val']+$bittrex['val']+$usecryptos['val'];
$query = $db->prepare("INSERT INTO `data_market` (`value`, `usd`, `time`) VALUES (:val, :usd, :time)");
$query->bindParam(':val', $all, PDO::PARAM_STR);
$query->bindParam(':usd', $usd['dash_usd'], PDO::PARAM_STR);
$query->bindParam(':time', time(), PDO::PARAM_STR);
$query->execute();
