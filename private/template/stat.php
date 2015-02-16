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

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Midas Bank</a>
            </div>
            <!-- /.navbar-header -->
			
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="/index.php"><i class="fa fa-newspaper-o fa-fw"></i> News</a>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-money"></i> Wallets<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="/?do=btc_wallet">Bitcoin</a>
                                </li>

								<li>
                                     <a href="#">Litecoin</a>
                                </li>
								<li>
                                     <a href="#">Vertcoin</a>
                                </li>
								<li>
                                     <a href="#">Darkcoin</a>
                                </li>
								<li>
                                     <a href="#">Dogecoin</a>
                                </li>

                            </ul>
							
						<li>
							<a href="#"><i class="fa fa-bar-chart"></i> Exchange<span class="fa arrow"></span></a>
							 <ul class="nav nav-second-level">
                                <li>
                                    <a href="blank.html">Bitcoin</a>
                                </li>

								<li>
                                     <a href="blank.html">Litecoin</a>
                                </li>
								<li>
                                     <a href="blank.html">Vertcoin</a>
                                </li>
								<li>
                                     <a href="blank.html">Darkcoin</a>
                                </li>
								<li>
                                     <a href="blank.html">Dogecoin</a>
                                </li>

                            </ul>
                        </li>
						<li>
                            <a href="/?do=market"><i class="fa fa-credit-card"></i> Processing</a>
                        </li>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.navbar-static-side -->
        </nav>
		
		

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Bitcoin Wallet</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">			
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Litst of your markets
                        </div>
                        <!-- /.panel-heading -->
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
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
				</div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
	
    </div>
    <!-- /#wrapper -->
	


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
