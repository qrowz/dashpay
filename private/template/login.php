<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Midas-Bank</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/metisMenu.min.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
	<link href="/css/alertify.core.css" rel="stylesheet">
	<link href="/css/alertify.bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container">
	
	<div class="row">
	<div class="col-md-4 col-md-offset-4 text-center" style="margin-top: 40px;margin-bottom: -55px;">
	<a href="/"><img src="/img/logo.png">
	</a>
	</div>
	</div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" id="login" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <input id="do_login" type="submit" class="btn btn-lg btn-success btn-block" value="Login">
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
			<div class="row">
	<div class="col-md-4 col-md-offset-4 text-center" style="margin-top: 0px;margin-bottom: 10px;">
	<a href="https://github.com/poiuty/midas" target="_blank">open source code</a>
	</div>
	</div>
    </div>

<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/metisMenu.min.js"></script>
<script src="/js/sb-admin-2.js"></script>
<script src="/js/alertify.min.js"></script>
<script type="text/javascript">
$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        
		$.post("https://"+document.domain+"/public/login.php", {login: $('input[id=login]').val(), password: $('input[id=password]').val()}, function( data ){
			
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
		
		$.post("https://"+document.domain+"/public/login.php", {login: $('input[id=login]').val(), password: $('input[id=password]').val()}, function( data ){
		
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
</body>
</html>
