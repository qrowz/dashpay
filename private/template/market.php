<? $sites = list_sites($user['id']); ?>
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
          <a class="navbar-brand" href="/?do=market">Processing</a>
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

<div style="float: left;"><input class="form-control" id="passwd" style="display:inline; position:relative;top:2px;width:300px;" type="text" name="name" placeholder="new password">  
<input class="btn btn-info" id="change_paaswd" type="submit" style="margin-top: 0px;" value="change password"></div>
<div style="float: right;">
<input class="btn btn-info" id="change_btc" type="submit" style="margin-top: 0px;" value="change address">
<input class="form-control" id="btc_address" style="display:inline; position:relative;top:2px;width:300px;" type="text" name="name" value = "<? echo $user['address']; ?>" placeholder="btc address">  
</div>

<center>



 </center>
 
 <br/><br/><br/><br/>

<div class="panel panel-default">
<table class="table table-bordered">
    <thead>
        <tr>
            <th><center>ID</center></th>
            <th><center>Name</center></th>
            <th><center>Url</center></th>
			<th><center>BTC</center></th>
            <th><center>Actions</center></th>
        </tr>
    </thead>
    <tbody>
<? echo $sites; ?>
    </tbody>
</table>
</div>

<center>
<input class="form-control" id="name_store" style="display:inline; position:relative;top:2px;width:300px;" type="text" name="name" placeholder="Site Name"> 
<input class="form-control" id="url_store" style="display:inline; position:relative;top:2px;width:300px;" type="text" name="url" placeholder="site.com ">
<input class="btn btn-info" id="add_site" type="submit" style="margin-top: 0px;" value="Add new store">
 </center>

</div>
</div>

<script type="text/javascript">

$("#change_btc").click(function(e) {
	$.post("https://"+document.domain+"/public/change_address.php", {address: $('input[id=btc_address]').val()}, function( data ){
		alertify.error(data);
	
		if(data == "invalid"){
			alertify.error('wrong addresss'); return;
		}
		
		if(data == "ok"){
		alertify.success('success update'); return;
		}
	});
});

$("#change_paaswd").click(function(e) {

	$.post("https://"+document.domain+"/public/change_passwd.php", {passwd: $('input[id=passwd]').val()}, function( data ){
		alertify.error(data);
	
		if(data == "er_p"){
			alertify.error('empty data send'); return;
		}
		
		if(data == "er_w"){
			alertify.error('wrong password'); return;
		}
		
		if(data == "ok"){
		alertify.success('check your email'); return;
		}
	});
});

	$("#add_site").click(function(e) {
		
		$.post("https://"+document.domain+"/public/add_store.php", {name: $('input[id=name_store]').val(), url: $('input[id=url_store]').val()}, function( data ){
		
		//alertify.error(data);
		
		if(data == "er_name"){
			alertify.error('invalid name!'); return;
		} 
		
		if(data == "er_url"){
			alertify.error('invalid url!'); return;
		}
		
		if(data == "er_add"){
			alertify.error('has already been added'); return;
		}
		
		if(data == "er_empty"){
			alertify.error('empty data send'); return;
		}
		
		window.location.href = "/?do=edit&id="+data;
		
		
	});
});
</script>

</body>
</html>