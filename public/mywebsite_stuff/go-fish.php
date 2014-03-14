<?php

require_once('classes/blackjack-classes.php');

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/mywebsite_stuff/go-fish.php') {
	session_destroy();
	session_start();
}

if (!isset($_SESSION['deck'])) {
	$_SESSION['deck'] = new Deck();
	$_SESSION['player'] = new GoFishHand();
	$_SESSION['computer'] = new GoFishHand();
}

if (!empty($_POST['ask'])) {
	if (!($_SESSION['computer']->checkHandForCard($_POST['ask']))) {
		$_SESSION['deck']->drawCard($_SESSION['player']);
	} else {
		$_SESSION['player']->addCardsToHand($_SESSION['computer']->checkHandForCard($_POST['ask']));
	}

	$computerGuess = $_SESSION['computer']->computerQuestion();
	if (!$_SESSION['player']->checkHandForCard($computerGuess)) {
		$_SESSION['deck']->drawCard($_SESSION['computer']);
	} else {
		$_SESSION['computer']->addCardsToHand($_SESSION['player']->checkHandForCard());
	}
}

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
	<title>Go Fish</title>
</head>
<body>
	<!-- navbar -->
	<!-- <div id="navbar" class="navbar navbar-inverse navbar-fixed-top">
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
	</div> -->
	<!-- end navbar -->

	<div class='container main-container'>
		<div class='page-header'>
			<h1>Go Fish <small>Coded by Cameron Holland</small></h1>
		</div>
		<div class='row'>
			<div class='col-md-4'>
				<h3>Player Hand</h3>
				<?php $_SESSION['player']->displayHand(); ?>
			</div>
			<div class="col-md-4">
				<h3>Deck <?= "Card Count: " . count($_SESSION['deck']->fullDeck); ?></h3>
				<?php $_SESSION['deck']->displayDeck(); ?>
			</div>
			<div class='col-md-4'>
				<h3>Computer Hand</h3>
				<?php $_SESSION['computer']->displayHand(TRUE); ?>
			</div>
		</div>
		<div class='row'>
			<div class='col-md-4'>
				<?php $_SESSION['player']->displayOptions(); ?>
			</div>
		</div>
	</div>
</body>
</html>