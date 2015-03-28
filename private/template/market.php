<? 

if(!empty($_POST['edit_site_id'])){
	$site = edit_site($_POST['edit_site_id'], $user['id']);
	die($site); 
}


$sites = list_sites($user['id']); 



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Midas-Bank: wallet</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/metisMenu.min.css" rel="stylesheet">
    <link href="/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/css/sb-admin-2.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="/css/alertify.core.css"/>
	<link rel="stylesheet" type="text/css" href="/css/alertify.bootstrap.css"/>
</head>

<body>

<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit shop</h4>
            </div>
            <div id="modal_info" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="edit_site">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div id="wrapper">
<? require_once($_SERVER['DOCUMENT_ROOT'].'/private/template/includes/nav.php'); ?>
<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Bitcoin Wallet</h1>
                </div>
            </div>
            <div class="row">
		

<div class="col-lg-12">
<div class="panel panel-default">
<div class="panel-heading">Add new market</div>
<div class="panel-body">
<input class="form-control" id="name_store" style="display:inline; position:relative;top:2px;width:300px;" type="text" name="name" placeholder="Name"> 
<input class="btn btn-info" id="add_site" type="submit" style="margin-top: 2px;" value="add new store">
</div>
</div>
</div>
		
<div class="col-lg-12">
<div class="panel panel-default">
<div class="panel-heading">Payout address</div>
<div class="panel-body">
<input class="form-control" id="btc_address" style="display:inline; position:relative;top:2px;width:300px;" type="text" name="name" value = "<? echo $user['address']; ?>" placeholder="btc address">
<input class="btn btn-info" id="change_btc" type="submit" style="margin-top: 2px;" value="change address">
</div>
</div>
</div>			

                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Litst of your markets
                        </div>
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
											<th style="text-align: center;">ID</th>
											<th style="text-align: center;">Name</th>
											<th style="text-align: center;">BTC</th>
											<th style="text-align: center;">Actions</th>
                                        </tr>
                                    </thead>
									<tbody>
										<? echo $sites; ?>
									</tbody>
									</table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
        </div>
    </div>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/metisMenu.min.js"></script>
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables.bootstrap.min.js"></script>
    <script src="/js/sb-admin-2.js"></script>
	<script src="/js/alertify.min.js"></script>
	<script>
	$(document).on("click", "[data-editsite]", function(e) {
		e.preventDefault();
		$.post("https://"+document.domain+"/?do=market", {edit_site_id: $(this).data("editsite")}, function( data ){
		$("#modal_info").html(data);
		$('#myModal').modal('show');
		return false;
		});
	});
	</script>
	
<script>
function makeRand(max){
        // Generating random number from 0 to max (argument)
        return Math.floor(Math.random() * max);
}

$(document).on("click", "#key_generate", function(e) {
		e.preventDefault();
        var length = 32;
        var result = '';
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


$(document).on("click", "#edit_site", function(e) {
		$.post("https://"+document.domain+"/public/edit_store.php", {id: $('input[id=id]').val(), url: $('input[id=url]').val(), key: $('input[id=key]').val()}, function( data ){
		
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
			alertify.success('data update'); $('#myModal').modal('hide'); return;
		}else{
			alertify.error('something is wrong'); return;
		}	
	});
});
</script>	

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				stateSave: true
        });
    });
    </script>
	
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
