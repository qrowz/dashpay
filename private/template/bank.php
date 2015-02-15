<? $bank_address = get_b_address($user['id']);  ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="./favicon.ico">

    <title>[MIDAS-BANK]</title>

	
	
<script src="/js/jquery-2.1.1.min.js"></script>		
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="css/alertify.core.css"/>
<link rel="stylesheet" type="text/css" href="css/alertify.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="/css/justified-nav.css"/>
<link rel="stylesheet" type="text/css" href="/css/midas.css"/>

<script src="/js/bootstrap.min.js"></script>
<script src="/js/alertify.min.js"></script>
<script src="/js/midas.js"></script>
  </head>

  <body>

  
<div class="navi-top">
<nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Bank</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
			<li><a href="/?do=market">Processing</a></li>
			<li><a href="/">Exchange</a></li>
			<li><a href="/">Transactions</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="../navbar-fixed-top/">Balance: 10500 BTC</a></li>
			  <li><a href="/?do=exit">Exit</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
</div>
  

<div class="bank">	  
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<table id="b_address" class="table table-bordered">
    <thead>
        <tr>
            <th><center>ID</center></th>
            <th><center>Address</center></th>
            <th><center>Label</center></th>
        </tr>
    </thead>
    <tbody>

	<? echo $bank_address['info']; ?>


</tbody>
</table>
</div>

<div class="center">
<div class="test1">
<input class="form-control" id="btc_address" style="display:inline; position:relative;top:2px;width:350px;" type="text" name="name" value = "" placeholder="btc address">
<input class="form-control" id="btc_address" style="display:inline; position:relative;top:2px;width:100px;" type="text" name="name" value = "" placeholder="amount"><br/>


<input class="form-control" id="label" style="display:inline; position:relative;top:2px;width:454px;" type="text" name="name" value = "" placeholder="Label">
</div>

<div class="test2">
<input class="btn btn-info btn-sm" id="change_btc" type="submit" style="margin-top: 4px; width: 100px; margin-left:5px" value="send money"><br/>

<input class="btn btn-info btn-sm" id="gen_wallet" type="submit" style="margin-top: 4px; width: 100px; margin-left:5px" value="new address">
</div>
</div>


</div>
</div>

</div>

<script type="text/javascript">
$("#gen_wallet").click(function(e) {
	$.post("https://"+document.domain+"/public/bank_address.php", {label: $('input[id=label]').val()}, function( data ){
		//alertify.error(data);
		$('#b_address').append('<tr><td><center>'+($('#b_address tr').length+1)+'</center></td><td><center>'+data+'</center></td><td><center>'+$('input[id=label]').val()+'</center></td></tr>');
	});
});
</script>

</body>
</html>
