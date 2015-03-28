<?
$cache = new Memcache();
$cache->connect('unix:///tmp/memcached.socket', 0, 30);


function cache_price($name, $val){
	global $cache;
	
	$j = $cache->get($name);

	if($j === FALSE && $val != 'no' )
		$cache->set($name, $val, MEMCACHE_COMPRESSED, 86400);
		
	if(@$_GET['all'] == 1)
		$cache->set($name, $val, MEMCACHE_COMPRESSED, 86400);
	
	if($j != FALSE)
		return $j;
}

function crypto_price($id, $name){

	if(@$_GET['all'] != 1){
		$cache_test = cache_price($name, 'no');
		if(!empty($cache_test)) return $cache_test;
	}

	$i = file_get_contents("http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=".$id);
	if($i === false) die;
	$i = json_decode($i, true);
	cache_price($name, $i["return"]["markets"][$name]["lasttradeprice"]);
	return $i["return"]["markets"][$name]["lasttradeprice"];
}

if(@$_GET['all'] == 1){
	round(crypto_price(2, 'BTC'));
	round(crypto_price(213, 'DRK'), 2);
	round(crypto_price(6, 'FTC'), 3);
	round(crypto_price(2, 'BTC')*crypto_price(151, 'VTC'), 3);
	echo "OK";
}

switch(@$_GET['name']){
	case 'BTC': echo round(crypto_price(2, 'BTC')); break;
	case 'DRK': echo round(crypto_price(213, 'DRK'), 2); break;
	case 'FTC': echo round(crypto_price(6, 'FTC'), 3); break;
	case 'VTC': echo round(crypto_price(2, 'BTC')*crypto_price(151, 'VTC'), 3); break;
}
