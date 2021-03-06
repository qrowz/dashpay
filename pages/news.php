<!DOCTYPE html>
<html lang="en">
<head>
	<title>DASH: торговля на бирже</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<style>.tweaked-margin { margin-right: 30px; }</style>
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
				<li class="active"><a href="#">Новости</a></li>
				<li><a href="/pages/download.php">Скачать кошелек</a></li>
				<li><a href="/pages/community.php">Сообщество</a></li>
				<li><a href="/pages/mining.php">Майнинг</a></li>
				<li><a href="/pages/trade.php">Биржа</a></li>
				<li><a href="/pages/merchant.php">Прием платежей</a></li>
				<li><a href="/pages/stats.php">Статистика</a></li>
				<li><a href="https://wiki.dash.org.ru" target="_blank">База знаний</a></li>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
	<div class="row">
		<div class="col-md-12 ">
			<h3>[28-04-2015] новости от разработчиков</h3>
			Всем привет!
Нам бы хотелось проинформировать всех о текущей работе над проектом и рассказать, над чем команда работает в настоящий момент.<br/>
Сейчас мы параллельно занимаемся сразу несколькими задачами по разработке, продвижению и адаптации.<br/>
Мы добились значительного прогресса в этих направлениях и продолжим развивать успех на протяжении ближайших недель и месяцев.<br/>
Последние пару недель вы могли наблюдать некоторое затишье - и этому есть хорошее объяснение.
<h4>Обновление кошелька - v0.12.0.0</h4>
<ul>
  <li>Udjin перенёс и внедрил все последние обновления ядра Bitcoin 10 в наш проект.<br/>
  Это означает, что теперь Dash поддерживает ускоренную синхронизацию путём приоритетной загрузки заголовков блоков и ряд других изменений в коде ядра.<br/><br/></li>
  <li>Evan поработал над переформатированием протокола взаимодействия Мастернод и оформил команды в универсальные классы, что значительно упрощает их последующее изучение и использование. Это позволяет нам наконец начать использование команд инвентарного типа для коммуникации Мастернод при обновлениях. Нововведение позволило сократить внутренний трафик в сети на 80%!<br/><br/></li>
  <li>Код приближается к тому состоянию, при котором отпадает необходимость в существовании референсной ноды. Референсная нода была создана с целью решения проблемы, существовавшей с самого начала проекта Dash - управление справедливыми выплатами мастернодам. С референсной нодой, выплаты происходят последовательно и прогнозируемо всем мастернодам, но эта управляющая выплатами нода является централизованным механизмом, а наша цель - добиться полной децентрализации всех компонентов сети. В версии ядра v0.12.0.0, мы закладываем поддержку гибридного режима, при котором референсная нода может быть выключена, но сеть продолжит принудительные последовательные выплаты мастернодам так же, как это происходит сейчас, но уже без централизации. Гибридный режим позволит нам по-прежнему использовать референсную ноду в качестве запасного варианта на крайний случай.<br/><br/></li>
  <li>Udjin поработал над улучшением поддержки национальных языков при осуществлении процессов Darksend - теперь не-англоязычные пользователи, смешивающие монеты, не будут обескуражены статусными сообщениями и сообщениями об ошибках на английском языке.</li>
</ul>

<h4>Мобильные кошельки, поддерживающие DS и IX</h4>
<ul>
<li>Мы рады сообщить, что у нас появились разработчики, занимающиеся внедрением функционала Darksend/InstantX на мобильные платформы.<br/> Это воодушевляет, так как позволит быстро и просто производить частные приватные расчёты, аналогично переводам наличных денег - но децентрализовано и без участия посредников. Это очень важный этап развития, так как он значительно приближает нас к осуществлению глобальной миссии Dash - стать полноценной цифровой наличностью.<br/><br/></li>
<li>Мы также хотим поприветствовать нашего нового разработчика QuantumExplorer, который занимается разработкой мобильного Кошелька для iPhone.</li>
</ul>

<h4>Документация Dash (whitepaper) V1</h4>
Старая документация значительно устарела, мы внесли актуальные правки и подробное описание всех технологий, которые используются в Dash в настоящий момент.<br/>
По ссылке вы можете <a href="https://www.dashpay.io/wp-content/uploads/2015/04/Dash-WhitepaperV1.pdf" target="_blank">прочитать новую документацию</a>!

		<br/></div>
	</div>
</div>
</body>
</html>