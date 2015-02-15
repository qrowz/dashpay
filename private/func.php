<?


function get_b_address($uid){

	global $db; $i = ''; $j = 0;
	$query = $db->prepare("SELECT * FROM `bank` WHERE `uid` = :uid ORDER BY `id`");
	$query->bindParam(':uid', $uid, PDO::PARAM_STR);
	$query->execute();

	while($row = $query->fetch()){
		$j++;
		$i = $i."<tr>
				<td><center>$j</center></td>
				<td><center>{$row['address']}</center></td>
				<td><center>{$row['label']}</center></td>
				</tr>";
	}
	
	return ["info" => $i];
}

function bank_address($label, $uid){
	global $db, $bitcoin;
	
	$address = $bitcoin->getnewaddress();
	$query = $db->prepare("INSERT INTO `bank` (`uid`, `address`, `label`) VALUES ( :uid, :address, :label )");
	$query->bindParam(':uid', $uid, PDO::PARAM_STR);
	$query->bindParam(':address', $address, PDO::PARAM_STR);
	$query->bindParam(':label', $label, PDO::PARAM_STR);
	$query->execute();
	
	return $address;
}

function midas_crypt($a, $key, $text){
	$algo = MCRYPT_RIJNDAEL_256;
	$mode = MCRYPT_MODE_CBC;
	
	$iv_size = mcrypt_get_iv_size($algo, $mode);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM);
	
	switch($a){
		default: 
		$ciphertext = mcrypt_encrypt($algo, $key, $text, $mode, $iv);
		$ciphertext = $iv . $ciphertext;
		$j = base64_encode($ciphertext);
		break;
		
		case 'decode':
		$ciphertext_dec = base64_decode($text);
		$iv_dec = substr($ciphertext_dec, 0, $iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $iv_size);
		$j = mcrypt_decrypt($algo, $key, $ciphertext_dec, $mode, $iv_dec);
		break;
	}
	return $j;
}


function midas_query($link, $post){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.137 Safari/537.36');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);

		curl_setopt($ch, CURLOPT_REFERER, 'https://midas-bank.ru');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

		return curl_exec($ch);
		curl_close($ch);
}

function payout(){
	global $db, $bitcoin;
	$query = $db->prepare("SELECT * FROM `income` WHERE `status` = 'complete'");
	$query->execute();
	
	while($row=$query->fetch()){
		$sites = $db->prepare("SELECT * FROM `sites` WHERE `id` = :id");
		$sites->bindParam(':id', $row['sid'], PDO::PARAM_STR);
		$sites->execute();
		$info = $sites->fetch();
		
		$u_query = $db->prepare("SELECT * FROM `users` WHERE `id` =:id");
		$u_query->bindParam(':id', $info['uid'], PDO::PARAM_STR);
		$u_query->execute();
		$out = $u_query->fetch();
		
		echo "{$out['address']} => {$row['balance']}";
		
		//$u = $bitcoin->getinfo();
		
		//var_dump($u);
		
		$address = $out['address'];
		$my_coins = $row['balance']*0.25/100;
		$send_coins = (float)($row['balance']-$my_coins-0.0001);
		
		//var_dump($send_coins);
		
		$txid = $bitcoin->sendtoaddress($address, $send_coins);
		
		$update = $db->prepare("UPDATE `income` SET `status` = 'payout', `out` =:txid, `time2` = :time WHERE `id` = :id");
		$update->bindParam(':time', time(), PDO::PARAM_STR);
		$update->bindParam(':txid', $txid, PDO::PARAM_STR);
		$update->bindParam(':id', $row['id'], PDO::PARAM_STR);
		$update->execute();
		
		$update = $db->prepare("UPDATE `my` SET `balance` = `balance`+:balance WHERE `id` = '1'");
		$update->bindParam(':balance', $my_coins, PDO::PARAM_STR);
		$update->execute();
	}
}

function send2site(){
	global $db;
	$query = $db->prepare("SELECT * FROM `income` WHERE `status` = 'wait'");
	$query->execute();
	
	while($row=$query->fetch()){
		$sites = $db->prepare("SELECT * FROM `sites` WHERE `id` = :id");
		$sites->bindParam(':id', $row['sid'], PDO::PARAM_STR);
		$sites->execute();
		$info = $sites->fetch();
		
		$pid = $db->prepare("SELECT * FROM `address` WHERE `address` = :address");
		$pid->bindParam(':address', $row['address'], PDO::PARAM_STR);
		$pid->execute();
		if($pid->rowCount() != 1) continue;
		
		$info2 = $pid->fetch();
		

		
		
		$t = midas_crypt('encode', $info['secret'], "{$info['id']} {$info2['pid']} {$row['balance']} {$row['txid']}");
		$y = midas_crypt('decode', $info['secret'], $t);
		//echo "$t => $y <br/>";
		//echo "{$info['surl']} => {$info['secret']} => {$row['balance']} <br/>";
		$q = midas_query($info['surl'], array('q' => $t));
		
		//echo $q."<br/>";
		
		if($q == 1){
			$update = $db->prepare("UPDATE `income` SET `status` = 'complete', `time2` = :time WHERE `id` = :id");
			$update->bindParam(':time', time(), PDO::PARAM_STR);
			$update->bindParam(':id', $row['id'], PDO::PARAM_STR);
			$update->execute();
		}
		
	}
}

function store_stat($id, $uid){
	global $db; $i = '';
	
	$query = $db->prepare("SELECT * FROM `sites` WHERE `uid` = :uid AND  `id` = :id");
	$query->bindParam(':uid', $uid, PDO::PARAM_STR);
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() != 1) return ["info" => "<h4><center>Deny for you ;)</center></h4>", "pages" => NULL, "items" => NULL];

	$query = $db->prepare("SELECT * FROM `income` WHERE `sid` = :id");
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() == 0) return ["info" => "<h4><center>No transactions</center></h4>", "pages" => NULL, "items" => NULL];
	
	
	$pages = new Paginator;  
	$pages->items_total = $query->rowCount();  
	$pages->mid_range = 5;  
	$pages->paginate();
	
	$query = $db->prepare("SELECT * FROM `income` WHERE `sid` = :id ORDER BY `id` DESC {$pages->limit}");
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	
	while($row = $query->fetch()){
		$i = $i."<tr><td><center>{$row['address']}</center></td>
            <td><center>{$row['balance']}</center></td>
            <td><center>".date("Y-m-d H:i:s", $row['time'])."</center></td>
			<td><center><a href=\"https://blockchain.info/tx/{$row['txid']}\" target=\"_blank\" title=\"{$row['txid']}\">IN</a></center></td>
			<td><center><a href=\"https://blockchain.info/tx/{$row['out']}\" target=\"_blank\" title=\"{$row['out']}\">OUT</a></center></td>
			<td><center>{$row['status']}</center></td>
			<td><center>".date("Y-m-d H:i:s", $row['time2'])."</center></td>
			</tr>";
	}
	
	return ["info" => $i, "pages" => $pages->display_pages(), "items" => $pages->display_items_per_page()];
}

function balance($a){
	global $db; $i = 0;
	
	for($i = 0; count($a) > $i; $i++){
		if($a["$i"]["category"] != "receive" || $a["$i"]["confirmations"] < 6 || $a["$i"]["amount"] < 0.001) continue;
		
		$j = round($a["$i"]["amount"], 8, PHP_ROUND_HALF_DOWN);
		
		// Есть ли в базе эта транзакция?
		$select_query = $db->prepare("SELECT * FROM `income` WHERE `txid` =:id AND `address` =:address");
		$select_query->bindParam(':id', $a["$i"]["txid"], PDO::PARAM_STR);
		$select_query->bindParam(':address', $a["$i"]["address"], PDO::PARAM_STR);
		$select_query->execute();
		if($select_query->rowCount() > 0){ echo "ALREADY => {$a["$i"]["amount"]} => {$a["$i"]["txid"]} <br/>"; $i++; continue; }
		
		// Кто оплачивает?
		$select_query = $db->prepare("SELECT * FROM `address` WHERE `address` =:address");
		$select_query->bindParam(':address', $a["$i"]["address"], PDO::PARAM_STR);
		$select_query->execute();
		if($select_query->rowCount() != 1){ echo "NO ADDRESS FOR => {$a["$i"]["amount"]} => {$a["$i"]["txid"]} <br/>"; $i++; continue; }
		$row = $select_query->fetch();
		
		// Запишем лог
		$insert_query = $db->prepare("insert into `income`( `address`, `sid`, `txid`, `balance`, `time`) VALUES ( :address, :sid, :id, :money, UNIX_TIMESTAMP())");
		$insert_query->bindParam(':id', $a["$i"]["txid"], PDO::PARAM_STR);
		$insert_query->bindParam(':sid', $row['sid'], PDO::PARAM_STR);
		$insert_query->bindParam(':money', $j, PDO::PARAM_STR);
		$insert_query->bindParam(':address', $a["$i"]["address"], PDO::PARAM_STR);
		$insert_query->execute();

		echo " test? {$a["$i"]["address"]} {$a["$i"]["amount"]} {$a["$i"]["txid"]}<br/>";
	}
}


function gen_address($sid, $pid){
	global $db, $bitcoin;
	$query = $db->prepare("SELECT * FROM `address` WHERE `sid` = :sid AND `pid` = :pid");
	$query->bindParam(':sid', $sid, PDO::PARAM_STR);
	$query->bindParam(':pid', $pid, PDO::PARAM_STR);
	$query->execute();
	$row = $query->fetch();
	$address = $row['address'];
	
	if($query->rowCount() != 1){ 
		$address = $bitcoin->getnewaddress();
		$query = $db->prepare("INSERT INTO `address` (`address`, `sid`, `pid`) VALUES ( :address, :sid, :pid )");
		$query->bindParam(':address', $address, PDO::PARAM_STR);
		$query->bindParam(':sid', $sid, PDO::PARAM_STR);
		$query->bindParam(':pid', $pid, PDO::PARAM_STR);
		$query->execute();
	}

	return $address;
}

function pay_store_name($id){
	global $db;
	$query = $db->prepare("SELECT * FROM `sites` WHERE `id` = :id");
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	$row = $query->fetch();
	
	if($query->rowCount() != 1) die('no market');
	return $row['name'];
}


function edit_site($id, $uid){
	global $db;
	$query = $db->prepare("SELECT * FROM `sites` WHERE `uid` = :uid AND  `id` = :id");
	$query->bindParam(':uid', $uid, PDO::PARAM_STR);
	$query->bindParam(':id', $id, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() != 1) return '<center><h4>Deny for you ;)</center></h4>';
	$row = $query->fetch();
	
	return "<center>
			<input type=\"hidden\" id=\"id\" value=\"{$row['id']}\">
		  <p><input class=\"form-control input-sm\" id=\"name\" style=\"display:inline; position:relative;top:2px;width:300px;\" type=\"text\" name=\"name\" value=\"{$row['name']}\" placeholder=\"PayPal\" readonly></p>
		  <p><input class=\"form-control input-sm\" id=\"main_url\" style=\"display:inline; position:relative;top:2px;width:300px;\" type=\"text\" name=\"name\" value=\"{$row['url']}\" placeholder=\"paypal.ru\" readonly> </p>
		  <p><input class=\"form-control input-sm\" id=\"url\" style=\"display:inline; position:relative;top:2px;width:300px;\" type=\"text\" name=\"name\" value=\"{$row['surl']}\" placeholder=\"https://paypal.ru/secret/pay.php\"> </p>
		  <p><input class=\"form-control input-sm\" id=\"key\" style=\"display:inline; position:relative;top:2px;width:282px;\" type=\"text\" name=\"name\" value=\"{$row['secret']}\" placeholder=\"Secret key\"> <a id=\"key_generate\" href=\"javascript: void(0)\"  ><i class=\"glyphicon glyphicon-retweet\" title=\"generate key\"></i></a> </p>
		  <input class=\"btn btn-info\" id=\"edit_site\" type=\"submit\" style=\"margin-top: 0px;\" value=\"Save\">
		</center>";
}

function store_balance($i){
	global $db; $o = 0; 
	$query = $db->prepare("SELECT * FROM `income` WHERE `status` != 'payout' AND `sid` =:id");
	$query->bindParam(':id', $i, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() == 0) return 0;
	if($query->rowCount() > 0){
		while($row = $query->fetch()){
			$o = $o + $row['balance'];
		}
		return $o;
	}
}


function list_sites($user){
	global $db; $i = '';
	$query = $db->prepare("SELECT * FROM `sites` WHERE `uid` = :id");
	$query->bindParam(':id', $user, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() == 0) return '<center><h4>You have no stores.</center></h4>';
		
	while($row = $query->fetch()){
	
	
	$i = $i."<tr><td><center>{$row['id']}</center></td>
            <td><center>{$row['name']}</center></td>
            <td><center>{$row['url']}</center></td>
			<td><center>".store_balance($row['id'])."</center></td>
            <td><center><a href=\"?do=stat&id={$row['id']}\"><i class=\"glyphicon glyphicon-signal\" title=\"stat\"></i></a> &nbsp; 
			<a href=\"/?do=edit&id={$row['id']}\"><i class=\"glyphicon glyphicon-pencil\" title=\"edit\"></i></a></center></td></tr>";
	}
	return $i;
}

function __autoload($class_name) {
	include $_SERVER['DOCUMENT_ROOT'].'/private/class/'.$class_name.'.class.php';
}

function error($message){
	if(!is_array($message)){
		$err = [
			"no_auth" => "Неудачная попытка входа.",
			"no_user" => "Неправильный логин.",
			"bad_passwd" => "Неправильный пароль."
		];
		
		if (array_key_exists($message, $err)){
			die('error');
		}
	} else return $message;
}

function auth($login, $session){
	global $db;
	$query = $db->prepare("SELECT * FROM `users` WHERE `login` = :login AND `session` = :session");
	$query->bindParam(':login', $login, PDO::PARAM_STR);
	$query->bindParam(':session', $session, PDO::PARAM_STR);
	$query->execute();
	
	if($query->rowCount() != 1){
		$query = $db->prepare("UPDATE `users` SET `session` = NULL WHERE `login` = :login AND `session` = :session");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':session', $session, PDO::PARAM_STR);
		$query->execute();
		
		session_unset();
		session_destroy();
		return 'no_auth';
	}
	
	$row = $query->fetch();
	return ["id" => $row['id'], "address" => $row['address']];
}

function login($login, $passwd){
	global $db;
	$query = $db->prepare("SELECT * FROM `users` WHERE `login` =:login");
	$query->bindParam(':login', $login, PDO::PARAM_STR);
	$query->execute();
	if($query->rowCount() != 1) return 'no_user';
	$row = $query->fetch();

	if (password_verify($passwd, $row['passwd'])){
		$new_passwd = rehash($passwd, $row['passwd']);
		$_SESSION['id'] = $row['id'];
		$_SESSION['login'] = $login;
		$_SESSION['sess'] = hash('sha256' ,$login.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
	
		if($new_passwd != 'no_hash'){	// Обновляем hash в базе
			$query = $db->prepare("UPDATE `users` SET `passwd` = :passwd WHERE `login` = :login");
			$query->bindParam(':passwd', $new_passwd, PDO::PARAM_STR);
			$query->bindParam(':login', $login, PDO::PARAM_STR);
			$query->execute();
		}
		
		$query = $db->prepare("UPDATE `users` SET `session` = :sess WHERE `login` = :login");
		$query->bindParam(':login', $login, PDO::PARAM_STR);
		$query->bindParam(':sess', $_SESSION['sess'], PDO::PARAM_STR);
		$query->execute();
		
		return 'enter';
		
	} else return 'bad_passwd';
}

function rehash($passwd, $hash){
	if (password_needs_rehash($hash, PASSWORD_DEFAULT))
		return password_hash($passwd, PASSWORD_DEFAULT);
	else
		return 'no_hash';
}

function gen_passwd($a){
		return password_hash($a, PASSWORD_DEFAULT, ['cost' => 12]);
}

function generateRandomString($length) 
{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';	
		for ($i = 0; $i < $length; $i++)
		{
			$randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		return $randomString;
}

function _exit(){
		global $conf;
		session_unset();
		session_destroy();
		header("Location: {$conf['url']}");
}
