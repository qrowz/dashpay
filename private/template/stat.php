<? $mystat = store_stat($_GET['id'], $user['id']); ?>
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
</head>
<body>
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
                        <div class="panel-heading">
                            Litst of your markets
                        </div>
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
											<th style="text-align: center;">ID</th>
											<th style="text-align: center;">Payer</th>
											<th style="text-align: center;">Amount</th>
											<th style="text-align: center;">Time</th>
											<th style="text-align: center;">TXID</th>
											<th style="text-align: center;">TXID</th>
											<th style="text-align: center;">Status</th>
											<th style="text-align: center;">Status Time</th>
                                        </tr>
                                    </thead>
									<tbody>
										<? echo $mystat; ?>
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
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
				stateSave: true
        });
    });
    </script>

</body>

</html>
