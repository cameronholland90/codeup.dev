<?php

session_start();

require_once('classes/read-database.php');

if (isset($_GET['organize'])) {
	if ($_SESSION['sort'] === $_GET['organize']) {
		$_SESSION['sort'] = $_GET['organize'] . " DESC";
	} else {
		$_SESSION['sort'] = $_GET['organize'];
	}
}

if (isset($_SESSION['sort'])) {
	$_SESSION['tableData'] = new Datafile('national_parks', 'codeup_mysqli_test_db', $_SESSION['sort']);
} else {
	$_SESSION['tableData'] = new Datafile('national_parks', 'codeup_mysqli_test_db');
}


$parks = $_SESSION['tableData']->getQuerySet();

?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Revalia' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="css/carousel.css"/>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<link rel="shortcut icon" href="img/Arches v2-6.jpg" />
	<title>mysqli-national-parks.php</title>
</head>
<body>
	<!-- navbar -->
	<div id="navbar" class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="row">
				<div class="navbar-header">
					<a href="/" class="navbar-brand">CameronHolland.me</a>
					<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse navHeaderCollapse nav-pills">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="/">Resume</a></li>
						<li><a href="portfolio.html">Portfolio</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="yahtzee.php">Yahtzee</a></li>
								<li><a href="blackjack.php">Blackjack</a></li>
								<li><a href="connect-four.php">Connect Four</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- end navbar -->
	<div class='container main-container'>
		<div class="page-header">
			<h1>List of National Parks <small>From Wikipedia</small></h1>
		</div>
		<table class='table table-bordered table-striped'>
			<thead>
				<tr>
					<?= "<th class='col-md-1'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=name'>NAME</a></th>

					<th class='col-md-1'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=location'>LOCATION</a></th><th class='col-md-2'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=date_established'>DATE ESTABLISHED</a></th><th class='col-md-1'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=area_in_acres'>AREA</a></th><th class='col-md-7'>DESCRIPTION</th>"; ?>
				</tr>
			</thead>
			<?php foreach ($parks as $park) { ?>
				<?= "<tr>"; ?>
					<?= "<td>{$park['name']}</td><td>{$park['location']}</td><td>{$park['date_established']}</td><td> ". number_format($park['area_in_acres'], 2, '.', ',') . " acres</td><td>{$park['description']}</td>"; ?>
				<?= "</tr>"; ?>
			<?php } ?>
		</table>
	</div>
</body>
</html>