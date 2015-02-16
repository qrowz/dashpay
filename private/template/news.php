<? $bank_address = get_b_address($user['id']);  ?>
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
                            <a href="/"><i class="fa fa-newspaper-o fa-fw"></i> News</a>
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
                    <h1 class="page-header">News</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<div class="col-md-12 news6">
				<div class="panel panel-default">
				<div class="panel-heading">
				<span class="label label-info">15.02.15 21:23</span>
				<strong style="margin-left: 10px;">О проекте</strong>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
				<p> xxx </p>
					
				
				</div><!-- /.panel-body -->
				</div>
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
