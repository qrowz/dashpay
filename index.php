<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Case</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#" >
		<img alt="Brand" src="/img/logo.png" style="max-width: 150px;">
      </a>
	  <ul class="nav navbar-nav">
		   <li class="active"><a href="#">Главная</a></li>
		   <li><a href="#">Новости</a></li>
		   <li><a href="/pages/download.php">Скачать</a></li>
		   <li><a href="/pages/community.php">Сообщество</a></li>
		   <li><a href="/pages/mining.php">Майнинг</a></li>
		   <li><a href="#">Биржа</a></li>
		   <li><a href="#">Процессинг</a></li>
		   <li><a href="/pages/stats.php">Статистика</a></li>
	   </ul>
    </div>
  </div>
</nav>
  
<div class="container">
  <div class="jumbotron">
    <h1>My first Bootstrap website!</h1>      
    <p>This page will grow as we add more and more components from Bootstrap...</p>      
  </div>

  <div class="row">
    <div class="col-md-3">
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
    <div class="col-md-3"> 
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    </div>
    <div class="col-md-3"> 
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    </div>
    <div class="col-md-3">
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="#">Home</a></li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Menu 1 <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Submenu 1-1</a></li>
            <li><a href="#">Submenu 1-2</a></li>
            <li><a href="#">Submenu 1-3</a></li>                        
          </ul>
        </li>
        <li><a href="#">Menu 2</a></li>
        <li><a href="#">Menu 3</a></li>
      </ul>
    </div>
    <div class="clearfix visible-lg"></div>
  </div>
</div>

</body>
</html>
