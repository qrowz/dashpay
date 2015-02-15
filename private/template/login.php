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
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
		<div class="login-panel panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Личный кабинет</h3>
		</div>
		<div class="panel-body">
		<fieldset>
		<div class="form-group">
		<input class="form-control" placeholder="E-mail" id="login" name="login" type="email" value="" autofocus="">
		</div>
		<div class="form-group">
		<input class="form-control" placeholder="Password" id="password" name="password" type="password" value="">
		</div>
		<input id="do_login" type="submit" name="login" class="btn btn-lg btn-success btn-block" value="Войти">
		<a href="/register" class="btn btn-lg btn-info btn-block">Регистрация</a>
		</fieldset>
		</div>
		</div>
		</div>
	</div>
	
	<div class="row">
	<div class="col-md-4 col-md-offset-4 text-center" style="margin-top: 0px;margin-bottom: 10px;">
	<a href="https://github.com/poiuty/midas-processing" target="_blank">open source code</a>
	</div>
	</div>
    </div> 
 
<script type="text/javascript">
$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        
		$.post("http://"+document.domain+"/public/login.php", {login: $('input[id=login]').val(), password: $('input[id=password]').val()}, function( data ){
			
			if(data == "error"){
				alertify.error('wrong data'); return;
			}
			
			if(data == "error10"){
				alertify.error('something is wrong'); return;
			}
			
			window.location.href = "/";
			
		});	
    }
});

	$("#do_login").click(function(e) {
		
		$.post("http://"+document.domain+"/public/login.php", {login: $('input[id=login]').val(), password: $('input[id=password]').val()}, function( data ){
		
		if(data == "error"){
			alertify.error('wrong data'); return;
		}
		
		if(data == "error10"){
			alertify.error('something is wrong'); return;
		}
		
		window.location.href = "/";
		
	});
});
</script>
</body></html>