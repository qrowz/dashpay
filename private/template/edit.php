<? $site = edit_site($_GET['id'], $user['id']); ?>
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
	  
<div class="row" style="margin-top: -60px;">

<? echo $site; ?>


</div>

</div>

<script type="text/javascript">

function makeRand(max){
        // Generating random number from 0 to max (argument)
        return Math.floor(Math.random() * max);
}

$("#key_generate").click(function(e) {
// password Lenght
        var length = 32;
        var result = '';
        // allowed characters
        var symbols = new Array(
                                'q','w','e','r','t','y','u','i','o','p',
                                'a','s','d','f','g','h','j','k','l',
                                'z','x','c','v','b','n','m',
                                'Q','W','E','R','T','Y','U','I','O','P',
                                'A','S','D','F','G','H','J','K','L',
                                'Z','X','C','V','B','N','M',
							//	'%', '*', ')', '?', '@', '#', '$', '~',
							//	'=', '_', '-', '+', '{', '}', '[', ']',
							//	'\/', '/', '.', '!', '\'', '"',
                                1,2,3,4,5,6,7,8,9,0
        );
        for (i = 0; i < length; i++){
                result += symbols[makeRand(symbols.length)];
        }
        document.getElementById('key').value = result;
});


	$("#edit_site").click(function(e) {
		$.post("https://"+document.domain+"/public/edit_store.php", {id: $('input[id=id]').val(), url: $('input[id=url]').val(), key: $('input[id=key]').val()}, function( data ){
		
		//alertify.error(data);
		
		if(data == "er_empty"){
			alertify.error('empty value!'); return;
		} 
		
		if(data == "er_id"){
			alertify.error('error store id!'); return;
		}
		
		if(data == "er_surl"){
			alertify.error('error site url!'); return;
		}
		
		if(data == "er_key"){
			alertify.error('error key!'); return;
		}
		
		if(data == "er_key2"){
			alertify.error('low key, must be 32 len!'); return;
		}
		
		if(data == "er_ac"){
			alertify.error('access denied'); return;
		}
		if(data == "ok"){
			alertify.success('data update'); return;
		}else{
			alertify.error('something is wrong'); return;
		}	
	});
});
</script>

</body>
</html>