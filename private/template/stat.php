<?  

$mystat = store_stat($_GET['id'], $user['id']); 

		//	$pages = new Paginator;  
		//	$pages->items_total = 1000;  
		//	$pages->mid_range = 5;  
		//	$pages->paginate();	

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="./favicon.ico">

    <title>[MIDAS-BANK] processing</title>

	
	
<script src="/js/jquery-2.1.1.min.js"></script>	
<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="css/alertify.core.css"/>
<link rel="stylesheet" type="text/css" href="css/alertify.bootstrap.css"/>
<link href="/css/justified-nav.css" rel="stylesheet">
<script src="/js/bootstrap.min.js"></script>
<script src="/js/alertify.min.js"></script>
<script src="/js/midas.js"></script>
  </head>

  <body>

      <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./?do=market">Processing</a>
        </div>

		
		
		
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	
	  <ul class="nav navbar-nav"> 
	  <li><a href="/?do=test">Test transaction</a></li>
	  <li><a href="/?do=bank">Bank</a></li>
	  <li><a href="/">Exchange</a></li>
	  <li><a href="/?do=exit">Exit</a></li>
      </ul>
      
    </div><!-- /.navbar-collapse -->

      </div><!-- /.container -->
    </div><!-- /.navbar -->
	


      <div class="jumbotron">
        <h2>Welcome processing panel</h2>
        <p class="lead">Anonymous, free, fee 0.25%</p>
      </div>
  
    <div class="container">
	  
<div class="row">
<div class="col-lg-12">

<div>

<div id="parent">
	<div style="float:left;">
		<nav><?php	echo @$mystat['pages'];	?></nav>
	</div>
	<div style="float:right; margin: 20px 0;"> 
		<?php echo @$mystat['items'];	?>
	</div>
</div>

<br/><br/><br/><br/>


<div class="panel panel-default">
<table class="table table-bordered">
    <thead>
        <tr>
            <th><center>Payer</center></th>
            <th><center>Amount</center></th>
            <th><center>Time</center></th>
			<th><center>TXID</center></th>
			<th><center>TXID</center></th>
			<th><center>Status</center></th>
			<th><center>Status Time</center></th>
        </tr>
    </thead>
    <tbody>
<? echo $mystat['info']; ?>
    </tbody>
</table>
</div>

<div id="parent">
	<div style="float:left;">
		<nav><?php	echo @$mystat['pages'];	?></nav>
	</div>
	<div style="float:right; margin: 20px 0;"> 
		<?php echo @$mystat['items'];	?>
	</div>
</div>

</div>
</div>


</body>
</html>