<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

$table = $table_market = $data_market = $transaction_table = $tx_rate = $block_table = $mn_count = $mntable = $hash_rate = $price_usd = $mtable = $diff_stat = $ghash_rate = $tpools = '';
$all_reward = $k = $other_reward = $pools_all_blocks = $pools_all_reward = $pools_all_hashrate = $step = $all_sum = $all_price = 0;
$pools_stats = array();
$rur = get_json_param('rur');
$markets = get_json_param('markets');

$query = $db->prepare("SELECT * FROM `mn_data`");
$query->execute();
while($row=$query->fetch()){
	$mntable = $mntable."<tr><td>{$row['ip']}</td><td>{$row['port']}</td><td>{$row['status']}</td><td><img src=\"https://dash.org.ru/img/16/".mb_strtolower(geoip_country_code_by_name($row['ip'])).".png\"> ".geoip_country_name_by_name($row['ip'])."</td><td>{$row['version']}</td><td><a href=\"https://chainz.cryptoid.info/dash/address.dws?{$row['address']}.htm\" target=\"_blank\">{$row['address']}</a></td></tr>";
}

$query = $db->prepare("select sum(tx_sum), sum(txs), time from `data` group by month(from_unixtime(`time`))");
$query->execute();
while($row=$query->fetch()){
	$transaction_table = $transaction_table."<tr><td>".date("Y, F" ,$row['time'])."</td><td>".round($row['sum(tx_sum)'])."</td><td>{$row['sum(txs)']}</td></tr>";
}

$query = $db->prepare("select sum(tx_sum), time from `data` group by day(from_unixtime(`time`))");
$query->execute();
while($row=$query->fetch()){
	if(empty($row["sum(tx_sum)"])) continue;
	$tx_rate = "$tx_rate [{$row['time']}000, ".round($row["sum(tx_sum)"])."],";
}

$query = $db->prepare("select avg(value), avg(usd), time from `data_market` group by day(from_unixtime(`time`))");
$query->execute();
while($row=$query->fetch()){
	$data_market = "$data_market [{$row['time']}000, ".round($row["avg(value)"])."],";
}

$query = $db->prepare("select avg(value), avg(usd), time from `data_market` group by month(from_unixtime(`time`))");
$query->execute();
while($row=$query->fetch()){
	$number = date("j", $row['time']);
	$table_market = $table_market."<tr><td>".date("Y, F", $row['time'])."</td><td>".round($row["avg(value)"]/$row["avg(usd)"]*$number)."</td><td>".round($row["avg(value)"]*$number)."</td></tr>";
}

$query = $db->prepare("SELECT * FROM `data`");
$query->execute();
while($row=$query->fetch()){
	if(empty($row['txs'])) continue;
	$query_select = $db->prepare("SELECT * FROM `address` WHERE `address` =:address AND `label` != ''");
	$query_select->bindParam(':address', $row['address'], PDO::PARAM_STR);
	$query_select->execute();
	$row_label = $query_select->fetch();
	if($query_select->rowCount() != 1){
		$row_label['label'] = 'Unknown';
	}
	$block_table = $block_table."<tr><td>{$row['bid']}</td><td><a href=\"https://chainz.cryptoid.info/dash/address.dws?{$row['address']}.htm\" target=\"_blank\">{$row_label['label']}</a></td><td>{$row['diff']}</td><td>".blockreward_all($row['bid'], $row['diff'])."</td><td>{$row['txs']}</td><td>".round($row['tx_sum'], 2)."</td><td>".secondsToTime(time()-$row['time'])."</td></tr>";
	unset($row_label);
}

$query = $db->prepare("select avg(count), time from `mn_count` group by day(from_unixtime(`time`))");
$query->execute();
while($row=$query->fetch()){
	if(empty($row['avg(count)'])) continue;
	$mn_count = "$mn_count [{$row['time']}000, ".round($row['avg(count)'])."],";
}

$query = $db->prepare("SELECT * FROM `node`");
$query->execute();
while($row=$query->fetch()){
	$table = $table."<tr><td><a href='http://{$row['ip']}:7903' target='_blank'>{$row['ip']}:7903</a></td><td><img src=\"https://dash.org.ru/img/16/".mb_strtolower(geoip_country_code_by_name($row['ip'])).".png\"> {$row['country']}</td><td>{$row['users']}</td><td>".ghash($row['hash'])."</td><td>{$row['fee']}</td><td>".secondsToTime($row['uptime'])."</td></tr>";
}
$query = $db->prepare("SELECT * FROM `global` order by `time` asc");
$query->execute();
while($row=$query->fetch()){
	$hash_rate = "$hash_rate [{$row['time']}000, {$row['hash']}],";
	if($row['ghash'] != 0){
		$ghash_rate = "$ghash_rate [{$row['time']}000, {$row['ghash']}],";
	}
}

$query = $db->prepare("SELECT * FROM `price` order by `time` asc");
$query->execute();
while($row=$query->fetch()){
	if(empty($row['price'])) continue;
	$price_usd = "$price_usd [{$row['time']}000, {$row['price']}],";
}

foreach ($markets as $key => $value) {
	$step++; $all_sum += $value['val']; $all_price += $value['price'];
	if(count($markets) == $step){
		$all_price = round($all_price/$step, 2);
		$mtable = "<tr><td>Все</td><td>$ {$all_sum}</td><td>100%</td><td>$ {$all_price}</td></tr>";
	}
}

foreach ($markets as $key => $value) {
	$market_percent = round(($value['val']/$all_sum)*100);
	$mtable = $mtable."<tr><td><a href=\"{$value['url']}\" target=\"_blank\">{$key}</a></td><td>$ {$value['val']}</td><td> {$market_percent}%</td><td>$ {$value['price']}</td></tr>";
}

$query = $db->prepare("select avg(diff), time from `data` group by day(from_unixtime(`time`))");
$query->execute();
while($row=$query->fetch()){
	if(empty($row['avg(diff)'])) continue;
	$diff_stat = "$diff_stat [{$row['time']}000, {$row['avg(diff)']}],";
}

$query_select = $db->prepare("SELECT * FROM `data` WHERE `time` > UNIX_TIMESTAMP()-86400");
$query_select->execute();
$all_data = $query_select->rowCount();

$query_avg_diff = $db->prepare("SELECT AVG(diff), MAX(bid) FROM `data` WHERE `time` > UNIX_TIMESTAMP()-86400");
$query_avg_diff->bindParam(':address', $row['address'], PDO::PARAM_STR);
$query_avg_diff->execute();
$row_avg_diff=$query_avg_diff->fetch();
$seconds_per_day = 24*60*60; // 86400

$query_select = $db->prepare("SELECT * FROM `address`");
$query_select->execute();
if($query_select->rowCount() == 0) return;
while($row = $query_select->fetch()){
		$query_data = $db->prepare("SELECT * FROM `data` WHERE `address` = :address AND `time` > UNIX_TIMESTAMP()-86400");
		$query_data->bindParam(':address', $row['address'], PDO::PARAM_STR);
		$query_data->execute();
		if($query_data->rowCount() == 0) continue;
		while($row_data = $query_data->fetch()){
			$all_reward = $all_reward+blockreward($row_data['bid'] ,$row_data['diff']);
		}
		$hashrate = $all_reward * ($row_avg_diff['AVG(diff)'] * (pow(2, 48) / hexdec('0x00000000ffff'))) / ($seconds_per_day * blockreward($row_avg_diff['MAX(bid)'], $row_avg_diff['AVG(diff)']));
		if($row['address'] == 'P2Pool') $row['label'] = 'P2Pool';
		if(empty($row['label'])){ $k = $k+$query_data->rowCount(); $other_reward = $other_reward+$all_reward; $all_reward = 0; continue;}
		$arr_count[] = $query_data->rowCount();
		$percent = round($query_data->rowCount()/$all_data*100, 2);
		array_push($pools_stats, [ 'name' => $row['label'], 'blocks' => $query_data->rowCount(), 'reward' => round($all_reward, 2), 'hashrate' => $hashrate, 'percent' =>  round($percent, 2)]);
		$all_reward = 0;
}

$hashrate = $other_reward * ($row_avg_diff['AVG(diff)'] * (pow(2, 48) / hexdec('0x00000000ffff'))) / ($seconds_per_day * blockreward($row_avg_diff['MAX(bid)'], $row_avg_diff['AVG(diff)']));
array_push($pools_stats, [ 'name' => 'Unknown', 'blocks' => $k, 'reward' => round($other_reward, 2), 'hashrate' => $hashrate, 'percent' =>  round($k/$all_data*100, 2)]);

foreach ($pools_stats as $key => $value) {
	$pools_all_blocks += $value['blocks'];
	$pools_all_reward += $value['reward'];
	$pools_all_hashrate += $value['hashrate'];
}

array_push($pools_stats, [ 'name' => 'Все', 'blocks' => $pools_all_blocks, 'reward' => $pools_all_reward, 'hashrate' => $pools_all_hashrate, 'percent' =>  100]);
uasort($pools_stats, 'myCmp');
foreach ($pools_stats as $key => $value) {
	$tpools = $tpools."<tr><td>{$value['name']}</td><td>{$value['blocks']}</td><td>{$value['reward']}</td><td>".auto_hash($value['hashrate'])."</td><td>{$value['percent']} %</td></tr>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>DASH: статистика</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="//code.highcharts.com/highcharts.js"></script>
	<script src="//code.highcharts.com/modules/exporting.js"></script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/1.10.6/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<script type="text/javascript" charset="utf-8"> $(document).ready(function() { $('#example').dataTable({ "order": [[ 3, "desc" ]] }); }); </script>
	<script type="text/javascript" charset="utf-8"> $(document).ready(function() { $('#mn_table').dataTable({ "order": [[ 4, "desc" ]] }); }); </script>
	<script type="text/javascript" charset="utf-8"> $(document).ready(function() { $('#blocks').dataTable({ "order": [[ 0, "desc" ]] }); }); </script>
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
				<li><a href="#">Новости</a></li>
				<li><a href="/pages/download.php">Скачать кошелек</a></li>
				<li><a href="/pages/community.php">Сообщество</a></li>
				<li><a href="/pages/mining.php">Майнинг</a></li>
				<li><a href="/pages/trade.php">Биржа</a></li>
				<li><a href="/pages/merchant.php">Процессинг</a></li>
				<li class="active"><a href="/pages/stats.php">Статистика</a></li>
			</ul>
		</div>
	</div>
</nav>
<div class="container">
	<div role="tabpanel">
		<ul class="nav nav-tabs" role="tablist" id="myTab">
			<li role="presentation" class="active"><a href="#price" aria-controls="price" role="tab" data-toggle="tab">Цена</a></li>
			<li role="presentation"><a href="#network" aria-controls="network" role="tab" data-toggle="tab">Сеть</a></li>
			<li role="presentation"><a href="#netdiff" aria-controls="netdiff" role="tab" data-toggle="tab">Сложность</a></li>
			<li role="presentation"><a href="#p2pool" aria-controls="p2pool" role="tab" data-toggle="tab">P2Pools</a></li>
			<li role="presentation"><a href="#masternode" aria-controls="masternode" role="tab" data-toggle="tab">Мастерноды</a></li>
			<li role="presentation"><a href="#transaction" aria-controls="transaction" role="tab" data-toggle="tab">Транзакции</a></li>
			<li role="presentation"><a href="#trade" aria-controls="trade" role="tab" data-toggle="tab">Обьем торгов</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="price">
				<div class="panel-body">
					<div id="container2" style="height: 300px; width: 1110px;"></div>
					<table class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Биржа</th>
								<th>Обьем</th>
								<th>Доля</th>
								<th>Цена</th>
							</tr>
						</thead>
						<tbody>
							<? echo $mtable; ?>
						</tbody>
					</table>	
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="p2pool">
				<div class="panel-body">
					<div id="container" style="height: 300px; width: 1110px;"></div>
					<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>P2Pool</th>
								<th>Country</th>
								<th>Users</th>
								<th>Rate (MH/s)</th>
								<th>Fee %</th>
								<th>Uptime</th>
							</tr>
						</thead>
						<tbody>
							<? echo $table; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="netdiff">
				<div class="panel-body">
					<div id="container3" style="height: 300px; width: 1110px;"></div>
						<table id="blocks" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Height</th>
								<th>Founder</th>
								<th>Diff</th>
								<th>Reward</th>
								<th>TXS</th>
								<th>Sum</th>
								<th>Age</th>
							</tr>
						</thead>
						<tbody>
							<? echo $block_table; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="network">
				<div class="panel-body">
					<div id="container4" style="height: 300px; width: 1110px;"></div>
						<table class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Название</th>
									<th>Блоки</th>
									<th title="Монеты, которые получат майнеры">Эмиссия</th>
									<th title="Среднее значение за 24 часа">Скорость</th>
									<th>Доля</th>
								</tr>
							</thead>
							<tbody>
								<? echo $tpools; ?>
							</tbody>
						</table>
					</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="masternode">
				<div class="panel-body">
					<div id="container5" style="height: 300px; width: 1110px;"></div>
					<table id="mn_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>IP</th>
								<th>Port</th>
								<th>Status</th>
								<th>Страна</th>
								<th>Version</th>
								<th>Address</th>
							</tr>
						</thead>
						<tbody>
							<? echo $mntable; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="transaction">
				<div class="panel-body">
					<div id="container6" style="height: 300px; width: 1110px;"></div>		
					<table class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Date</th>
								<th>Sum (DASH)</th>
								<th>TXs</th>
							</tr>
						</thead>
						<tbody>
							<? echo $transaction_table; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="trade">
				<div class="panel-body">
					<div id="container7" style="height: 300px; width: 1110px;"></div>		
					<table class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Date</th>
								<th>DASH</th>
								<th>USD</th>
							</tr>
						</thead>
						<tbody>
							<? echo $table_market; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(function () {
	$('#myTab a[href="'+window.location.hash+'"]').tab('show');
	$('#container7').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
		yAxis: {
			title: { text: 'Обьем' },
			min: 0,
		},
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: { 
						x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
					},
					marker: { radius: 2 },
					lineWidth: 1,
					states: {
						hover: { lineWidth: 1 }
					},
					threshold: null
				}
			},		
		series: [{
			type: 'area',
			name: 'USD',
			data: [<? echo $data_market; ?>]
		}]
	});	

	$('#container6').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
		yAxis: {
			title: { text: 'Обьем' },
			min: 0,
		},
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
						},
						marker: { radius: 2 },
						lineWidth: 1,
						states: {
							hover: { lineWidth: 1 }
						},
					threshold: null
				}
			},
				
		series: [{
			type: 'area',
			name: 'DASH',
			data: [<? echo $tx_rate; ?>]
		}]
	});		

	$('#container5').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
		yAxis: {
			title: { text: 'Количество' },
			min: 0,
		},
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
						},
					marker: { radius: 2 },
					lineWidth: 1,
					states: {
						hover: { lineWidth: 1 }
					},
					threshold: null
				}
			},
				
		series: [{
			type: 'area',
			name: 'Количество',
			data: [<? echo $mn_count; ?>]
		}]
	});		

	$('#container4').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
		yAxis: {
			title: { text: 'Hashrate' },
			min: 0,
			labels: {
				formatter: function() {
					if(this.value > Math.pow(1000, 4)){ return (this.value / Math.pow(1000, 4)) + ' TH/s'; }
					if(this.value > Math.pow(1000, 3)){ return (this.value / Math.pow(1000, 3)) + ' GH/s'; }
					if(this.value > Math.pow(1000, 2)){ return (this.value / Math.pow(1000, 2)) + ' MH/s'; }
					if(this.value > Math.pow(1000, 1)){ return (this.value / Math.pow(1000, 1)) + ' KH/s'; }
					return this.value + ' H/s';
					},
				},
			},
			plotOptions: {
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
						},
						marker: { radius: 2 },
						lineWidth: 1,
						states: {
							hover: { lineWidth: 1 }
						},
						threshold: null
					}
				},
			tooltip: {
				formatter: function() {
				var s= '<b>' + new Date(this.x).toUTCString() + '</b>';
				var hashrate= 0;
				$.each(this.points, function(i, point) {
					if(point.series.name==='Hashrate') { hashrate= point.y; }
					if(point.y > Math.pow(1000, 4))
						{ val= (point.y / Math.pow(1000, 4)).toFixed(2) + ' TH/s'; }
					else if(point.y > Math.pow(1000, 3))
						{ val= (point.y / Math.pow(1000, 3)).toFixed(2) + ' GH/s'; }
					else if(point.y > Math.pow(1000, 2))
						{ val= (point.y / Math.pow(1000, 2)).toFixed(2) + ' MH/s'; }
					else if(point.y > Math.pow(1000, 1))
						{ val= (point.y / Math.pow(1000, 1)).toFixed(2) + ' KH/s'; }
					else { val= point.y + ' H/s'; }
					s+= '<br/>' + point.series.name + ': ' + val;
				});
				return s;
			  },
				shared: true,
				valueSuffix: ' H/s',
			},
		series: [{
			type: 'area',
			name: 'Hashrate',
			data: [<? echo $ghash_rate; ?>]
		}]
	});

	$('#container3').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
		yAxis: {
			title: { text: 'Сложность' },
			min: 0,
		},
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
						},
						marker: { radius: 2 },
						lineWidth: 1,
						states: {
							hover: { lineWidth: 1 }
						},
						threshold: null
					}
				},	
		series: [{
			type: 'area',
			name: 'Сложность',
			data: [<? echo $diff_stat; ?>]
		}]
	});	
		
	$('#container2').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
			yAxis: {
				title: { text: 'Price' },
				min: 0,
			},	
		plotOptions: {
			area: {
				fillColor: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
					stops: [[0, Highcharts.getOptions().colors[0]],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
						},
						marker: { radius: 2 },
						lineWidth: 1,
						states: {
							hover: { lineWidth: 1 }
						},
						threshold: null
					}
				},	
		series: [{
			type: 'area',
			name: 'DASH/ USD',
			data: [<? echo $price_usd; ?>]
		}]
	});	

	$('#container').highcharts({
		chart: { zoomType: 'x' },
		credits:	{ enabled: false },
		exporting:	{ enabled: false },
		title: { text: '' },
		xAxis: { type: 'datetime' },
		legend:{ enabled: false },
		yAxis: {
			title: { text: 'Global Hashrate' },
				min: 0,
				labels: {
					formatter: function() {
						if(this.value > Math.pow(1000, 4)){ return (this.value / Math.pow(1000, 4)) + ' TH/s'; }
						if(this.value > Math.pow(1000, 3)){ return (this.value / Math.pow(1000, 3)) + ' GH/s'; }
						if(this.value > Math.pow(1000, 2)){ return (this.value / Math.pow(1000, 2)) + ' MH/s'; }
						if(this.value > Math.pow(1000, 1)){ return (this.value / Math.pow(1000, 1)) + ' KH/s'; }
						return this.value + ' H/s';
					},
				},
			},
			plotOptions: {
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]]
						},
						marker: { radius: 2 },
						lineWidth: 1,
						states: {
							hover: { lineWidth: 1 }
						},
						threshold: null
					}
				},
			tooltip: {
				formatter: function() {
				var s= '<b>' + new Date(this.x).toUTCString() + '</b>';
				var hashrate= 0;
				$.each(this.points, function(i, point) {
					if(point.series.name==='Hashrate') { hashrate= point.y; }
					if(point.y > Math.pow(1000, 4))
						{ val= (point.y / Math.pow(1000, 4)).toFixed(2) + ' TH/s'; }
					else if(point.y > Math.pow(1000, 3))
						{ val= (point.y / Math.pow(1000, 3)).toFixed(2) + ' GH/s'; }
					else if(point.y > Math.pow(1000, 2))
						{ val= (point.y / Math.pow(1000, 2)).toFixed(2) + ' MH/s'; }
					else if(point.y > Math.pow(1000, 1))
						{ val= (point.y / Math.pow(1000, 1)).toFixed(2) + ' KH/s'; }
					else { val= point.y + ' H/s'; }
					s+= '<br/>' + point.series.name + ': ' + val;
				});
				return s;
			  },
				shared: true,
				valueSuffix: ' H/s',
			},
		series: [{
			type: 'area',
			name: 'Hashrate',
			data: [<? echo $hash_rate; ?>]
		}]
	});
});
</script>
</body>
</html>
