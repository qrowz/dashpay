<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/class/easydarkcoin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');
$darkcoin = new Darkcoin('xxx','xxx','localhost','9998');
$info = $darkcoin->masternode('list');

function check_mn($ip){
	global $darkcoin, $info;
	
	if(@$info["$ip:9999"] == 'ENABLED'){
		$i = 'OK';
	}else{
		$i = 'NO';
	}
	return $i;
}

$mn_online = 0;

$query = $db->query("SELECT * FROM `hosting`");
$query->execute();
$mn_all = $query->rowCount();
	while($row=$query->fetch()){
		if(check_mn($row['ip']) == 'OK' || time()-60*60*24 < $row['last'] || time()-60*60*24 < $row['time']){
			$mn_online++;
		}
	}

$mn_free = $mn_all - $mn_online;
?>

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
<div id="myModal" class="modal fade" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<center> <h4 class="modal-title">Пожалуйста, подождите.</h4> </center>
			</div>
			<div id="modal_info" class="modal-body"></div>
		</div>
	</div>
</div>
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
			MasterNode (мастернода) – элементарный узел в сети Dash, который поддерживает проведение анонимных(технология Darksend) и моментальных(технология InstantX) транзакций, а также отвечает за раздачу blockchain.<br/><br/>
			Для создания мастерноды требуется 1000 монет Dash. За это владельцы мастернод получают часть монет от эмиссии. Чтобы получать выплаты, мастернода должна быть постоянно включена и подключена к сети.<br/>
			В то время, как мастернода, выпавшая из сети больше чем на час, вылетает из списка и требует повторного запуска.<br/>
			При этом она встает в конец очереди, как новая, без компенсации за предыдущее время работы.<br/>
			Поэтому хозяева узлов заинтересованы в их постоянной работоспособности и надежности.<br/><br/>
			Разместить мастерноду можно на операционной системе Windows, Linux и MacOS. Опытные пользователи могут <a href="https://forum.bits.media/index.php?/blog/191/entry-333-podnimaem-dash-masternode/" target="_blank">самостоятельно сделать это</a>.<br/>
			А для тех, кто хочет простое и надежное решение - мы сделали сервис «MasterNode хостинг». С его помощью вы сможете поднять свою ноду и управлять ей.<br/>
			Поддержите сеть DASH и получайте за это вознаграждение!
			
			<hr>
			
			<h3>Информация</h3>
			Техническая поддержка пользователей осуществляется через ICQ: 450420625<br/>
			Сервис автоматически получает от вас оплату через систему пожертвований.<br/>
			Вы сами определяете процент оплаты. Этот параметр задается в файле masternode.conf. Например:<br/><br/>
			<blockquote style="font-size:14px;">XkB8ySpiqyVHeAXHsNhU83mUJ7Jd3CJaqW:10</blockquote>
	
			Такая запись означает, что мы получаем 10% от дохода вашей мастерноды.<br/>
			Минимальный лимит за использования сервиса составляет 10%. <br/>
			Мы постоянно следим за этой настройкой, если она окажется меньше 10%, тогда ваша мастернода без предупреждения отключается, и она выбывает из списка нод с вытекающими из этого последствиями.<br><br>
			
			Количество размещенных MN: <? echo $mn_online; ?> | Количество свободных мест: <? echo $mn_free; ?> | Минимальный donate лимит: 10%<br/><br/>
			
			
			
			<hr>
			
			<h3>Установка</h3>
			Запустите ваш DASH кошелек и откройте консоль. Далее создайте новый DASH адрес:<br/><br/>
			<blockquote style="font-size:14px;">getaccountaddress 0</blockquote>
			После того, как вы выполните эту команду вы увидите свой новый адрес. Это адрес вашей мастерноды. Отправьте на него 1000 DASH с помощью команды:<br/><br/>
			<blockquote style="font-size:14px;">sendtoaddress ваш_новый_адрес 1000</blockquote>
			После этого вы увидите номер вашей транзакции, через 50 минут напишите этот номер и нажмите кнопку  "<u>получить masternode.conf</u>"<br/>
			Если ваш кошелек зашифрован (установлен пароль), терминал выдаст ошибку, тогда вам надо будет <a href="http://www.youtube.com/watch?feature=player_detailpage&v=VEaRjVwxlxw#t=245" target="_blank">разблокировать кошелек</a> и повторить отправку 1000 DASH.<br/><br/>
			
			<input id="txid" class="form-control" placeholder="Номер вашей транзакции" type="text"><br/>
			<button id="setup" type="submit" class="form-control btn btn-default">Получить masternode.conf</button>
			
			<br/><br/>
			Если все прошло успешно, и вы скачали файл <u>masternode.conf</u>, то положите его в папку <i>%appdata%/Roaming/Dash/</i><br/>
			Перезагрузите ваш DASH кошелек. Откройте консоль и запустите мастерноду командой:<br/><br/>
			<blockquote style="font-size:14px;">masternode start-many</blockquote>
			
			Если вы увидели "<u>Successfully started 1 masternodes</u>" - то все отлично.<br/>
			Не выключайте кошелек минут 10-20. Откройте через текстовый редактор файл <u>masternode.conf</u> - файл имеет следующую структуру:<br/><br/>
			<blockquote style="font-size:14px;">mn1 IP_REMOTE_NODE:9999 ВАШ_MASTERNODE_KEY txid 0</blockquote>
			
			Скопируйте ваш masternode ключ. Прокрутите страницу наверх и найдите "<u>Управление</u>".<br/>
			Введите ваш ключ и нажмите "<u>Скачать debug.log</u>" - как только вы скачаете файл откройте его через текстовый редактор.<br/>
			В файле будет много текста, воспользуйтесь поиском и найдите "<u>Enabled! You may shut down the cold daemon.</u>"<br/>
			Обнаружив запись, вы можете выключить локальный кошелек. Теперь ваша мастернода работает. Поздравляем!<br/><br/>
			
			Также вы можете проверять статус работы вашей ноды на нашем сайте <a href="http://dash.org.ru/pages/stats.php#masternode" target="_blank">dash.org.ru</a>.<br/>
			Для этого в поиске укажите IP вашей мастерноды или адрес вашего кошелька (1000DASH).<br/>
			Альтернативный ресурс для проверки вашей мастерноды, общего количества узлов в сети и обхема вознаграждения  dashninja.pl.<br/>
			<br/>
			<hr>
			
			<h3>Управление и пользование</h3>
			Для управления своей нодой - введите ключ мастерноды, далее выполните нужное вам действие.<br/><br/>
			
			<input id="private_key" class="form-control" placeholder="masternode key" type="text"><br/>
			
			<center>
				<button id="restart" type="submit" class="form-control btn btn-default" style="width: 300px;">Перезагрузить</button>
				<button id="status" type="submit" class="form-control btn btn-default" style="width: 300px;">Статус</button>
				<button id="log" type="submit" class="form-control btn btn-default" style="width: 300px;">Скачать debug.log</button>
			</center>
			<br/>
			Раз в 4-5 дней на адрес вашей мастерноды будет поступать выплата.<br/>
			Как только захотите её снять - заходите в кошелёк, с помощью функции <a href="http://www.youtube.com/watch?v=Z12GNiBJqjQ" target="_blank">контроль монет</a>, блокируйте 1000DASH мастерноды (если их случайно потратить, она выключится).<br/>
			Теперь всё, что выше 1000 можете переводить на свой основной адрес.<br/><br/>
			Периодически проверяете работоспособность своей Мастерноды. Если вдруг её нет в списке, пишите в поддержку.<br/><br/>
			В случае выхода новой версии DASH - Мастернод-хостинг сервис обновляет у себя серверную часть.<br/>
			Затем Вы устанавливаете у себя новую версию кошелька. После этого надо снова запустить Мастерноду.<br/><br/>
			Напоминание №1. При использовании нашего Мастернод-хостинг сервиса, вы не передаёте и не сообщаете никакой информации, кроме адреса вашей Мастерноды. А значит ваши 1000 DASH всё время находятся в безопасности, под вашим единоличным контролем.<br/>
			Напоминание №2. При запуска Мастерноды - 1000 DASH никуда не тратятся и не блокируются. Вы можете ими в любой момент воспользоваться. Однако, это повлечёт за собой выключение вашей Мастерноды.<br/>
			
		</div>
	</div>
</div>
<script>
$("#log").click(function(e) {
	$(this).blur();
	e.preventDefault();
	key = $('input[id=private_key]').val();
	$('#myModal').modal('show');
	$.post("//dash.org.ru/public/mn.php?control=log", { key: key }, function( data ){
		$('#myModal').modal('hide');
		if(data == 'no_key'){
			alertify.error("Неправильный ключ");
			return;
		}
		window.location = data;
	});
});

$("#restart").click(function(e) {
	$(this).blur();
	e.preventDefault();
	key = $('input[id=private_key]').val();
	$('#myModal').modal('show');
	$.post("//dash.org.ru/public/mn.php?control=restart", { key: key }, function( data ){
		$('#myModal').modal('hide');
		if(data == 'no_key'){
			alertify.error("Неправильный ключ");
			return;
		}
		alertify.success("Готово");
	});
});

$("#status").click(function(e) {
	$(this).blur();
	e.preventDefault();
	key = $('input[id=private_key]').val();
	$('#myModal').modal('show');
	$.post("//dash.org.ru/public/mn.php?control=status", { key: key }, function( data ){
		$('#myModal').modal('hide');
		if(data == 'no_key'){
			alertify.error("Неправильный ключ");
			return;
		}
		if(data == 'ENABLED'){
			alertify.success("Ваша MN работает");
			return;
		}else{
			alertify.error("Ваша MN не работает");
			return;
		}
	});
});

$("#setup").click(function(e) {
	$(this).blur();
	e.preventDefault();
	txid = $('input[id=txid]').val();
	$('#myModal').modal('show');
	$.post("//dash.org.ru/public/mn.php", { txid: txid }, function( data ){
		$('#myModal').modal('hide');
		if(data == 'empty'){
			alertify.error("Пустое значение");
			return;
		}
		if(data == 'wrong_txid'){
			alertify.error("Неправильный номер транзакции");
			return;
		}
		if(data == 'full'){
			alertify.error("Нет мест");
			return;
		}
		if(data == 'not_15_conf'){
			alertify.error("Дождитесь 15 подтверждений");
			return;
		}
		if(data == 'not_1000_DASH_TX'){
			alertify.error("Неправильная транзакция");
			return;
		}
		if(data == 'not_1000_DASH_BALANCE'){
			alertify.error("Ваш баланс != 1000 DASH");
			return;
		}
		if(data == 'error'){
			alertify.error("Ошибка");
			return;
		}
		if(data == 'mn_work'){
			alertify.error("MN уже работает");
			return;
		}
		window.location = "//dash.org.ru/public/mn.php?download=getfile&data="+data;
	});
});
</script>
</body>
</html>
