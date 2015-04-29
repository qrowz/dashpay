<?
require_once($_SERVER['DOCUMENT_ROOT'].'/private/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/init/mysql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/private/func.php');

$decode_diff = get_json_param('block');
$decode = get_json_param('rur');

if(!empty($_POST['rur'])) $decode['rur'] = abs(str_replace(',', '.', $_POST['rur']));
if(!empty($_POST['dash'])) $decode['dash_usd'] = abs(str_replace(',', '.', $_POST['dash']));
if(empty($_POST['hashrate'])) $hashrate = 10*1000000; else $hashrate = abs(str_replace(',', '.', $_POST['hashrate']))*1000000; // *1000000 = MH/s
if(empty($_POST['difficulty'])) $difficulty = $decode_diff['diff']; else $difficulty = abs(str_replace(',', '.', $_POST['difficulty']));
if(!isset($_POST['power'])) $power = 0.25; else $power = abs(str_replace(',', '.', $_POST['power']));
if(!isset($_POST['price'])) $price = 2; else $price = abs(str_replace(',', '.', $_POST['price']));
if(!isset($_POST['fee'])) $fee = 1; else $fee = abs(str_replace(',', '.', $_POST['fee']));

$blockreward = blockreward($decode_diff['num'], $difficulty);

if(!empty($_POST['get_blockreward'])){
	$w = ['reward' => $blockreward, 'diff' => $decode_diff['diff']];
	echo json_encode($w); 
	die;
}

$dash = $decode['rur']*$decode['dash_usd'];
$seconds_per_day = 24*60*60; // 86400
$coins_per_day = ($seconds_per_day * $blockreward * $hashrate) / ($difficulty * (pow(2, 48) / hexdec('0x00000000ffff')));
$cost_per_day = round($power*$price*24, 2);

$coins_per_hour = round($coins_per_day/24, 4);
$coins_per_week = round($coins_per_day*7, 3);
$coins_per_month = round($coins_per_day*30, 2);
$coins_per_year = round($coins_per_day*365, 2);

$cost_per_hour = round($cost_per_day/24, 2);
$cost_per_week = round($cost_per_day*7, 2);
$cost_per_month = round($cost_per_day*30, 2);
$cost_per_year = round($cost_per_day*365, 2);

$fee_hour = round(($coins_per_hour*$fee/100)*$dash, 4);
$fee_day = round(($coins_per_day*$fee/100)*$dash, 2);
$fee_week = round(($coins_per_week*$fee/100)*$dash);
$fee_month = round(($coins_per_month*$fee/100)*$dash);
$fee_year = round(($coins_per_year*$fee/100)*$dash);

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

echo "<table class=\"table table-bordered table-striped\">
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
  </table>";
  