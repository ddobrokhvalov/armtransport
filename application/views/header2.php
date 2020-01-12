<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="Задачник" />
		<meta name="keywords" content="Задачник" />
		<title>Транспорт</title>
		<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
		<link href="http://fonts.googleapis.com/css?family=Kreon" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<script src="/js/jquery-1.6.2.js" type="text/javascript"></script>
		<script src="/js/script.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="logo">
					<a href="/">Транспорт</a>
				</div>
				
				<?if(Controller_user::authorized()):?>
					<div class="user_authorized">
						<?=$_SESSION["user"]["username"]?> <a href="/?logout=1">выход</a>
					</div>
				<?endif;?>
				<div id="menu">
					<?if(Controller_user::authorized()):?>
						<?if(Controller_user::is_dispatcher()):?>
							<ul>
								<li class="first"><a href="/dispatcher/ways.php">Маршруты</a></li>
								<li><a href="/dispatcher/cars.php">Автомобили</a></li>
								<li><a href="/dispatcher/drivers.php">Водители</a></li>
								<li><a href="/dispatcher/traffic.php">Движение</a></li>
								<li><a href="/dispatcher/report.php">Отчет</a></li>
							</ul>
						<?else:?>
							<ul>
								<li class="first"><a href="/driver/">Водитель</a></li>
							</ul>
						<?endif;?>
					<?endif;?>
					<br class="clearfix" />
				</div>
			</div>
			<div id="page">
				
				<div id="content">
					<div class="box">
