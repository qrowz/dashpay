<? 
// PAY

if(empty($_GET['id']) || empty($_GET['pid']) || empty($_GET['s'])) die('empty');

$name = pay_store_name($_GET['id']);
$address = gen_address($_GET['id'], $_GET['pid']);
if(!is_numeric($_GET['s'])) die('OWNED');
if($_GET['s'] < 0.001) die('lolka');
?>

<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/favicon.ico">
    <title>[MIDAS-BANK] processing</title>
	
	<script src="/js/jquery-2.1.1.min.js"></script>	
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="css/alertify.core.css"/>
	<link rel="stylesheet" type="text/css" href="css/alertify.bootstrap.css"/>
    <link href="/css/main.css" rel="stylesheet">
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/alertify.min.js"></script>
	<script src="/js/midas.js"></script>
 </head>

<body>
	<div class="container">
	<center><h1>Market &laquo;<? echo $name; ?>&raquo;</h1>
	<div class="alert alert-success" style="width:450px;"> 
	send to <u><? echo $address; ?></u> [<? echo $_GET['s']; ?> BTC]
	</div>
		<h4>for payment send coins to any of these addresses</h4>
	</center>
    </div> 
</body></html>