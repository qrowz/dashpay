<? 
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

$hashrate = 10*1000000;
$power = 0.25;
$price = 2;
$fee = 1;
$decode_diff = get_json_param('block');
$decode = get_json_param('rur');
$difficulty = $decode_diff['diff'];
$blockreward = blockreward($decode_diff['num'], $difficulty);

$dash = $decode['rur']*$decode['dash_usd'];
$seconds_per_day = 24*60*60; // 86400
$coins_per_day = ($seconds_per_day * $blockreward * $hashrate) / ($difficulty * (pow(2, 48) / hexdec('0x00000000ffff')));
$cost_per_day = round($power*$price*24, 2);

$coins_per_hour = round($coins_per_day/24, 4);
$coins_per_week = round($coins_per_day*7, 3);
$coins_per_month = round($coins_per_day*30, 2);
$coins_per_year = round($coins_per_day*365, 2);

$cost_per_hour = round($cost_per_day/24, 2);
$cost_per_week = round($cost_per_day*7);
$cost_per_month = round($cost_per_day*30);
$cost_per_year = round($cost_per_day*365);

$fee_hour = round(($coins_per_hour*1/100)*$dash, 4);
$fee_day = round(($coins_per_day*1/100)*$dash, 2);
$fee_week = round(($coins_per_week*1/100)*$dash);
$fee_month = round(($coins_per_month*1/100)*$dash);
$fee_year = round(($coins_per_year*1/100)*$dash);

$rur_hour = round($coins_per_hour*$dash, 2);
$rur_day = round($coins_per_day*$dash);
$rur_week = round($coins_per_week*$dash);
$rur_month = round($coins_per_month*$dash);
$rur_year = round($coins_per_year*$dash);

$result_hour = round($coins_per_hour*$dash-$cost_per_hour-$fee_hour, 3);
$result_day = round($coins_per_day*$dash-$cost_per_day-$fee_day);
$result_week = round($coins_per_week*$dash-$cost_per_week-$fee_week);
$result_month = round($coins_per_month*$dash-$cost_per_month-$fee_month);
$result_year = round($coins_per_year*$dash-$cost_per_year-$fee_year);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>DASH: майнинг</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<style type="text/css"> .table td, th { text-align: center; } </style>
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
				<li class="active"><a href="/pages/mining.php">Майнинг</a></li>
				<li><a href="/pages/trade.php">Биржа</a></li>
				<li><a href="/pages/merchant.php">Прием платежей</a></li>
				<li><a href="/pages/stats.php">Статистика</a></li>
				<li><a href="/pages/mn.php">Хостинг</a></li>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
	<div class="row">
		<div class="col-md-12 ">
			<h3>Майнинг</h3>
			Эмиссия монет децентрализована, заранее ограничена по объёму ( максимум 22 миллиона монет) и времени. Распределяется относительно случайно среди тех кто использует аппаратные ресурсы компьютера с целью выполнения математических вычислений для подтверждения транзакций и обеспечения безопасности сети.<br/>
			На данный момент вы можете использовать графический процессор (GPU) и центральный процессор (CPU) для выпуска новых монет.<br/>
			<hr>
			<h3>Пулы</h3>
			Выпуском новых монет занимается огромное количество людей. Если вы занимаетесь этим самостоятельно и не имеете большой мощности, то может пройти много времени (месяцы и более), прежде чем вы получите награду.
			Участие в работе пула дает вам возможность зарабатывать понемногу, но зато регулярно, минимизируя для себя влияние удачи и риски месяцами ничего не заработать. Если у вас нет в наличии фермы из десятков или сотен видеокарт, участие в работе пула для вас единственный способ зарабатывать DASH.<br/><br/>
			Существует два типа пулов: централизованные и децентрализованные. Последние принято называть p2pool.<br/>
			Вы можете выбрать любой централизованный, например: <a href="https://coinmine.pl/dash/index.php" target="_blank">coinmine</a>, <a href="http://dark.coinobox.ru/" target="_blank">coinobox</a>, <a href="http://darkcoin.miningpoolhub.com/" target="_blank">miningpoolhub</a> или <a href="http://simplemulti.com/" target="_blank">simplemulti</a>.<br/>
			Мы рекомендуем использовать <a href="/pages/stats.php#p2pool" target="_blank">децентрализованные пулы</a> или выбирать обычный пул со среднем hashrate.<br/>
			
			<hr>
			<img src="/stat/drk.png">
			
			
			<hr>
			<h3>Калькулятор</h3>
			Вы можете посчитать приблизительный доход в рублях. Вводите только цифры. Десятичные - через точку.<br/>
			Обратите внимание, при генерации результата, значение "награда за блок" рассчитывается автоматически.<br/>
			<br/>
			<div style="width: 720px;">
				<center>
					<div class="form-inline">
						<input id="hashrate" type="text" class="form-control" placeholder="Скорость 10 MH/S" maxlength="5">
						<input id="difficulty" type="text" class="form-control" placeholder="Сложность <?=$decode_diff['diff'];?>" maxlength="5">
						<input id="blockreward" type="text" class="form-control" placeholder="Награда за блок <?=$blockreward;?>" disabled>
						<input id="rur" type="text" class="form-control" placeholder="Курс RUR/ USD <?=$decode['rur'];?>" maxlength="5"><br/><br/>
						<input id="power" type="text" class="form-control" placeholder="кВт/ час 0.25" maxlength="5">
						<input id="price" type="text" class="form-control" placeholder="Цена за кВт/ час 2" maxlength="5">
						<input id="fee" type="text" class="form-control" placeholder="Комиссия пула 1%" maxlength="3">
						<input id="dash" type="text" class="form-control" placeholder="Курс DASH/ USD <?=$decode['dash_usd'];?>" maxlength="5">
					</div>
				</center>
				<br/>
				<div id="table">
					<?echo "<table class=\"table table-bordered table-striped\">
								<thead>
									<tr>
										<th>Время</th>
										<th>Кол-во монет</th>
										<th>Доход</th>
										<th>Электричество</th>
										<th>Комиссия</th>
										<th>Результат</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Час</td>
										<td>$coins_per_hour</td>
										<td>$rur_hour</td>
										<td>$cost_per_hour</td>
										<td>$fee_hour</td>
										<td>$result_hour</td>
									</tr>
									<tr>
										<td>День</td>
										<td>".round($coins_per_day, 4)."</td>
										<td>$rur_day</td>
										<td>$cost_per_day</td>
										<td>$fee_day</td>
										<td>$result_day</td>
									</tr>
									<tr>
										<td>Неделя</td>
										<td>$coins_per_week</td>
										<td>$rur_week</td>
										<td>$cost_per_week</td>
										<td>$fee_week</td>
										<td>$result_week</td>
									</tr>
									<tr>
										<td>Месяц</td>
										<td>$coins_per_month</td>
										<td>$rur_month</td>
										<td>$cost_per_month</td>
										<td>$fee_month</td>
										<td>$result_month</td>
									</tr>
									<tr>
										<td>Год</td>
										<td>$coins_per_year</td>
										<td>$rur_year</td>
										<td>$cost_per_year</td>
										<td>$fee_year</td>
										<td>$result_year</td>
									</tr>
							</tbody>
					</table>"; ?>
				</div>
				<button id="calc" type="submit" class="form-control btn btn-default">Посчитать</button>
			</div>
			<hr/>
			<h3>CPU программное обеспечение</h3>
			CPUminer [<a href="http://download.darkcoin.fr/darkCoin-cpuminer-1.3-avx-aes-windows-binaries.zip">windows</a>] [<a href="https://github.com/elmad/darkcoin-cpuminer-1.3-avx-aes">исходный код</a>] для центрального процессора, необходима поддержка AVX-AES.
			<hr/>
			<h3>GPU программное обеспечение</h3>
			CCminer [<a href="http://cryptomining-blog.com/wp-content/files/ccminer-1.5.2-git-spmod.zip">windows</a>] [<a href="https://github.com/sp-hash/ccminer">исходный код</a>] для NVIDIA, показывают высокий hashrate начиная с серии Maxwell.<br/>
			SGminer [<a href="http://cryptomining-blog.com/wp-content/files/sgminer-5-1-1-windows.zip">windows</a>] [<a href="https://github.com/sgminer-dev/sgminer">исходный код</a>] для AMD, максимальный hashrate на драйвере 14.6<br/>
			Кроме этого, есть бинарники от Wolf0, которые позволяют значительно увеличить скорость на AMD картах.<br/>
			Находим свою карту, скачиваем bin, смотрим какой bin вы использовали.<br/>
			Далее сохраняем его название. Удаляем его. Перемещаем в папку новый bin и переименовываем его.<br/><br/>
			<a href="https://dl.dropboxusercontent.com/u/59491914/drk/wolf-x11Hawaiigw64l8ku0.bin">Hawaii R9 290/R9 290X/R9 295X2</a><br/>
			<a href="https://dl.dropboxusercontent.com/u/59491914/drk/wolf-x11Tahitigw64l8ku0.bin">Tahiti 7870XT/7950/7970/R9 280/R9 280X</a><br/>
			<a href="https://dl.dropboxusercontent.com/u/59491914/drk/wolf-x11Pitcairngw64l8ku0.bin">Pitcairn 7850/7870/R9 270/R9 270X</a><br/>
			<a href="https://dl.dropboxusercontent.com/u/59491914/drk/wolf-x11Capeverdegw64l8ku0.bin">Cape Verde 7730/7750/7770</a><br/>	
			<hr>
			<h3>Предупреждение</h3>
			Указанное программное обеспечение не проходило оценки и не получало одобрения разработчиков Dash - таким образом, нет гарантий в достоверности представленной информации. Используя программное обеспечение сторонних разработчиков, вы действуете на свой страх и риск.
			<br/><br/>
		</div>
	</div>
</div>
<script>
$("#calc").click(function(e) {
	$(this).blur();
	e.preventDefault();
	hashrate = $('input[id=hashrate]').val();
	difficulty = $('input[id=difficulty]').val();
	if($('input[id=price]').val().length > 0){
		price = $('input[id=price]').val();
	} else {
		price = 2;
	}
	if($('input[id=power]').val().length > 0){
		power = $('input[id=power]').val();
	} else {
		power = 0.25;
	}
	if($('input[id=fee]').val().length > 0){
		fee = $('input[id=fee]').val();
	} else {
		fee = 1;
	}
	rur = $('input[id=rur]').val();
	dash = $('input[id=dash]').val();
	$.post("//dash.org.ru/public/calc.php", { hashrate: hashrate, difficulty: difficulty, power: power, price: price, fee: fee, rur: rur, dash: dash }, function( data ){
		$("#table").html('');
		$("#table").append(data);
		$.post("//dash.org.ru/public/calc.php", { hashrate: hashrate, difficulty: difficulty, power: power, price: price, fee: fee, rur: rur, dash: dash, get_blockreward: 1 }, function( data ){
			value = JSON.parse(data);
			$("#blockreward").attr("placeholder", "Награда за блок "+value['reward']).val("").focus().blur();
			if(difficulty.length == 0){
				$("#difficulty").attr("placeholder", "Сложность "+value['diff']).val("").focus().blur();
			}
		});
	});
});
</script>
</body>
</html>
