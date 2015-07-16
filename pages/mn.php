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
	<script src="/js/alertify.js"></script>
	<link rel="stylesheet" href="/css/alertify.core.css">
	<link rel="stylesheet" href="/css/alertify.bootstrap.css">
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
				<li><a href="/pages/merchant.php">Прием платежей</a></li>
				<li><a href="/pages/stats.php">Статистика</a></li>
				<li class="active"><a href="/pages/mn.php">Хостинг</a></li>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
	<div class="row">
		<div class="col-md-12 ">
			<h3>MasterNode хостинг</h3>
			Владельцы мастернод получают часть эмиссии монет за проведение анонимных/ моментальных транзакий и раздачу blockchain.<br/>
			Чтобы получать выплаты - мастернода должна быть постоянно включена и подключена к сети.<br/>
			Выпавшая из сети больше чем на час мастернода  вылетает из списка и требует повторного запуска, при этом она встает в конец очереди как новая.<br/>
			Разместить мастерноду можно операционной системе Windows, Linix и MacOS. Опытные пользователи могут <a href="https://forum.bits.media/index.php?/blog/191/entry-333-podnimaem-dash-masternode/" target="_blank">самостоятельно сделать это</a>.<br/>
			А для тех кто хочет простое и надежное решение - мы сделали хостинг мастернод. С помощью него вы сможете поднять свою ноду и управлять ей.
			
			<hr>
			
			<h3>MasterNode управление</h3>
			Для управления своей нодой - введите ключ мастерноды, далее выполните нужное вам действие.<br/><br/>
			
			<input id="private_key" class="form-control" placeholder="masternode key" type="text"><br/>
			
			<center>
				<button type="submit" class="form-control btn btn-default" style="width: 300px;">Перезагрузить</button>
				<button type="submit" class="form-control btn btn-default" style="width: 300px;">Статус</button>
				<button type="submit" class="form-control btn btn-default" style="width: 300px;">Скачать debug.log</button>
			</center>
			
			<hr>
			
			<h3>MasterNode установка</h3>
			Запустите ваш DASH кошелек и откройте консоль. Далее создайте новый DASH адрес.<br/><br/>
			<blockquote style="font-size:14px;">getaccountaddress 0</blockquote>
			После того как вы выполните эту команду - вы увидите свой новый адрес. Отправьте на него 1000 DASH.<br/><br/>
			<blockquote style="font-size:14px;">sendtoaddress ваш_новый_адрес 1000</blockquote>
			Вы увидите номер вашей транзакции, через 50 минут напишите этот номер и нажимите кнопку "<u>получить masternode.conf</u>"<br/><br/>
			
			<input id="txid" class="form-control" placeholder="Номер вашей транзакции" type="text"><br/>
			<button id="setup" type="submit" class="form-control btn btn-default">Получить masternode.conf</button>
			
			<br/><br/>
			Если все прошло успешно и вы скачали файл <u>masternode.conf</u>, то положите его в папку <i>%appdata%/Dash/</i><br/>
			Перезагрузите ваш DASH кошелек. Откройте консоль и запустите мастерноду.<br/><br/>
			<blockquote style="font-size:14px;">masternode start-many</blockquote>
			
			Если вы увидели "<u>Successfully started 1 masternodes</u>" - то все отлично.<br/>
			Не выключайте кошелек минут 10-20. Откройте через текстовый редактор файл <u>masternode.conf</u> - файл имеет следующую структуру.<br/><br/>
			<blockquote style="font-size:14px;">mn1 IP_REMOTE_NODE:9999 ВАШ_MASTERNODE_KEY txid 0</blockquote>
			
			Найдите ваш masternode ключ. Прокрутите страницу наверх, и найдите "<u>MasterNode управление</u>".<br/>
			Напишите ваш ключ и нажмите "<u>Скачать debug.log</u>" - как только вы скачаете файл - откройте его через текстовый редактор.<br/>
			В файле будет много текста, так же откройте поиск и найдите "<u>Enabled! You may shut down the cold daemon.</u>"<br/>
			Если эта запись есть, то вы можете выключить локальный кошелек. Теперь ваша мастернода работает. Поздравляем!<br/><br/>
			
			Вы можете проверять статус работы вашей ноды на нашем сайте <a href="http://dash.org.ru/pages/stats.php#masternode" target="_blank">dash.org.ru</a>.<br/>
			Для этого в поиске укажите IP или адрес вашего кошелька (1000DASH) вашей мастерноды.
			<br/>
			<br/>
		</div>
	</div>
</div>
<script>
$("#setup").click(function(e) {
	$(this).blur();
	e.preventDefault();
	txid = $('input[id=txid]').val();
	//alertify.success("Success notification");
	$.post("//dash.org.ru/public/mn.php", { txid: txid }, function( data ){
		if(data == 'empty'){
			alertify.error("Пустое значение");
			return;
		}
		if(data == 'wrong_txid'){
			alertify.error("Неправильный номер транзакции");
			return;
		}
		window.location = "//dash.org.ru/public/mn.php?download=getfile&data="+data;
	});
});
</script>
</body>
</html>
