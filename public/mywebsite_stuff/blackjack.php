<?php

require_once('classes/blackjack-classes.php');

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/mywebsite_stuff/blackjack.php') {
	session_destroy();
	session_start();
} elseif (isset($_POST['restart']) || ((isset($_POST['playagain']) && count($_SESSION['deck']->fullDeck) < 10))) {
	session_destroy();
	session_start();
} elseif (isset($_POST['playagain'])) {
	unset($_SESSION['player']);
	unset($_SESSION['dealer']);
	unset($_SESSION['win']);
}

if (!isset($_SESSION['deck'])) {
	$_SESSION['deck'] = new Deck();
}

if (!isset($_SESSION['player']) && !isset($_SESSION['dealer'])) {
	$_SESSION['player'] = new Hand();
	$_SESSION['dealer'] = new Hand();
}

if (!isset($_POST['stay'])) {
	if (isset($_POST['hit']) && $_POST['hit'] === 'yes') {
		$_SESSION['deck']->drawCard($_SESSION['player']);
	}
} else {
	while ($_SESSION['dealer']->getScore() < 17 && ($_SESSION['dealer']->getScore() < $_SESSION['player']->getScore())) {
		$_SESSION['deck']->drawCard($_SESSION['dealer']);
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
	<title>Blackjack</title>
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
			<h1>Blackjack <small>Coded by Cameron Holland</small></h1>
		</div>
		<?php $hide = TRUE; ?>
		<?php if ((($_SESSION['player']->getScore() === 21 || $_SESSION['player']->getScore() > 21) || isset($_POST['stay'])) || (isset($_SESSION['win']))) { ?>
			<?php $hide = FALSE; ?>
		<?php } ?>
		<div class='row'>
			<div class='col-md-4'>
				<h3>Player Hand <?= "Score: " . $_SESSION['player']->getScore(); ?></h3>
				<?php $_SESSION['player']->displayHand(); ?>
			</div>
			<div class='col-md-4'>
				<h3>Dealer Hand <?= "Score: " . $_SESSION['dealer']->getScore($hide); ?></h3>
				<?php $_SESSION['dealer']->displayHand($hide); ?>
			</div>
			<div class="col-md-4">
				<h3>Deck <?= "Card Count: " . count($_SESSION['deck']->fullDeck); ?></h3>
				<?php $_SESSION['deck']->displayDeck(); ?>
			</div>
		</div>
		<div class='row'>
			<div class='userstuff'>
				<?php if ((($_SESSION['player']->getScore() === 21 || $_SESSION['player']->getScore() > 21) || isset($_POST['stay'])) || (isset($_SESSION['win']))) { ?>
					<form method="POST" action="">
						<button class='btn-md btn-success' name="playagain" type="submit" value="yes" autofocus="autofocus">Play Again</button>
						<button class='btn-md btn-danger' name="restart" type="submit" value="yes">Restart Game</button>
					</form>
					<?php if ($_SESSION['dealer']->getScore() > 21) { ?>
						<h3>Player Wins! Dealer Busted!</h3>
						<?php $_SESSION['win'] = TRUE; ?>
					<?php } elseif ($_SESSION['player']->getScore() > 21) { ?>
						<h3>Dealer Wins! Player Busted!</h3>
						<?php $_SESSION['win'] = TRUE; ?>
					<?php } elseif ($_SESSION['dealer']->getScore() > $_SESSION['player']->getScore()) { ?>
						<h3>Dealer Wins!</h3>
						<?php $_SESSION['win'] = TRUE; ?>
					<?php } elseif ($_SESSION['dealer']->getScore() < $_SESSION['player']->getScore()) { ?>
						<h3>Player Wins!</h3>
						<?php $_SESSION['win'] = TRUE; ?>
					<?php } else { ?>
						<h3>Dealer Wins!</h3>
						<?php $_SESSION['win'] = TRUE; ?>
					<?php } ?>
				<?php } else { ?>
					<form method="POST" action="">
						<button class='btn-md btn-success' name="hit" type="submit" value="yes" autofocus="autofocus">Hit</button>
						<button class='btn-md btn-danger' name="stay" type="submit" value="yes">Stay</button>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>