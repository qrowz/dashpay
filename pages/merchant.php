<!DOCTYPE html>
<html lang="en">
<head>
	<title>DASH: торговля на бирже</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="/css/default.css">
	<script src="/js/highlight.pack.js"></script>
	<script>hljs.initHighlightingOnLoad();</script>
	<style>.tweaked-margin { margin-right: 30px; } </style>
</head>
<body>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="/" >
				<img alt="Brand" src="/img/logo.png" style="max-width: 150px;">
			</a>
			<ul class="nav navbar-nav">
				<li><a href="/">Главная</a></li>
				<li><a href="/pages/news.php">Новости</a></li>
				<li><a href="/pages/download.php">Скачать кошелек</a></li>
				<li><a href="/pages/community.php">Сообщество</a></li>
				<li><a href="/pages/mining.php">Майнинг</a></li>
				<li><a href="/pages/trade.php">Биржа</a></li>
				<li class="active"><a href="/pages/merchant.php">Прием платежей</a></li>
				<li><a href="/pages/stats.php">Статистика</a></li>
				<li><a href="/pages/mn.php">Хостинг</a></li>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
	<div class="row">
		<div class="col-md-12 ">
			<h3>Прием платежей</h3>
			Вы можете автоматизировать прием платежей DASH на вашем сайте. Первый способ - использовать полную версию кошелька.<br/>
			В личном кабинете, сгенерируйте клиенту уникальный адрес для пополнения счета. Периодически, скриптом проверяйте входящие транзакции на ваш кошелек.<br/>
			Когда вы видите новую входящую транзакцию, проверьте на какой адрес поступили деньги. Узнайте кому из клиентов принадлежит адрес и увеличьте его баланс.<br/>
			После этого поменяйте в базе статус транзакции, чтобы не зачислить ее повторно. Как видите алгоритм действий достаточно простой. Ниже пример на PHP.<br/><br/>
			
<pre><code class="PHP">require_once('jsonRPCClient.php'); // http://jsonrpcphp.org/?page=download&lang=en
$dash = new jsonRPCClient('http://USER:PASSWD@127.0.0.1:9998/');

// Получаем массив и делаем цикл
$i = 0;
$a = $dash->listtransactions("*", 100000));

/* $dash->getnewaddress("NAME"); => так можно сгенерировать новый адрес клиенту */

while(count($a) > $i){
// Проверяем тип транзакции, количество подтверждений + сумму
if($a["$i"]["category"] != "receive" || $a["$i"]["confirmations"] < 6 || $a["$i"]["amount"] < 0.001) continue;

// Есть ли в базе эта транзакция?
$select_query = $db->prepare("SELECT * FROM `billing_log` WHERE `payment_id` =:id");
$select_query->bindParam(':id', $a["$i"]["txid"], PDO::PARAM_STR);
$select_query->execute();
if($select_query->rowCount() > 0){ $i++; continue; }

// Кто оплачивает?
$select_query = $db->prepare("SELECT * FROM `users` WHERE `dash` =:address");
$select_query->bindParam(':address', $a["$i"]["address"], PDO::PARAM_STR);
$select_query->execute();
if($select_query->rowCount() != 1){ $i++; continue; }
$row = $select_query->fetch();
$user_id = $row['user_id'];

// Узнаем курс
$usd = json_decode(file_get_contents("http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=213"), true);
if(empty($usd["return"]["markets"]["DRK"]["lasttradeprice"])) die('cant get usd value');

// Увеличим баланс
$money = round($a["$i"]["amount"]*$usd["return"]["markets"]["DRK"]["lasttradeprice"]);
$update_query = $db->prepare("UPDATE `users` SET `money` = `money`+:money WHERE `user_id` = :user_id");
$update_query->bindParam(':money', $money, PDO::PARAM_STR);
$update_query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
$update_query->execute();

// Запишем лог
$insert_query = $db->prepare("INSERT INTO `billing_log`(`payment_id`, `amount`, `date`, `system`, `user_id`) VALUES ( :id, :money, :time, 'DASH', :user_id)");
$insert_query->bindParam(':id', $a["$i"]["txid"], PDO::PARAM_STR);
$insert_query->bindParam(':money', $money, PDO::PARAM_STR);
$insert_query->bindParam(':time', time(), PDO::PARAM_STR);
$insert_query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
$insert_query->execute();

$i++;
}</code></pre><br/>
		</div>
	</div>
</div>
</body>
</html>
