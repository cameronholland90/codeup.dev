<?php

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/blackjack.php') {
	session_destroy();
	session_start();
} elseif (isset($_POST['playagain'])) {
	session_destroy();
	session_start();
}

// create an array for suits
$suits = ['C', 'H', 'S', 'D'];

// create an array for cards
$cards = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

class Card {
	private $cardValue;
	private $cardSuit;
	private $cardFaceValue;

	public function __construct($value, $suit) {
		$this->cardFaceValue = $value;
		$this->cardSuit = $suit;
		$this->cardValue = $this->setValue();
	}

	public function getValue($bust = FALSE) {
		if ($this->cardFaceValue == 'A' && $bust) {
			return 1;
		} else {
			return $this->cardValue;
		}
	}

	public function getSuit() {
		return $this->cardSuit;
	}

	public function getFace() {
		return $this->cardFaceValue;
	}

	public function setValue() {
		if ($this->cardFaceValue == 'A') {
			return 11;
		} elseif ($this->cardFaceValue == 'K' || $this->cardFaceValue == 'Q' || $this->cardFaceValue == 'J' || $this->cardFaceValue == '10') {
			return 10;
		} else {
			return $this->cardFaceValue;
		}
	}
}

class Deck {
	public $fullDeck = array();

	public function drawCard(&$player) {
		$player->hand[] = $this->fullDeck[0];
		unset($this->fullDeck[0]);
		$this->fullDeck = array_values($this->fullDeck);
	}

	public function shuffleDeck() {
		shuffle($this->fullDeck);
	}
}

class Hand {
	public $hand = array();

	public function __construct() {
	}

	public function getScore() {
		$total = 0;
	  	foreach ($this->hand as $key => $card) {
	  		$total += $card->getValue();
	  	}
	  	if ($total > 21) {
	  		$total = 0;
	  		foreach ($this->hand as $key => $card) {
	  			$total += $card->getValue(TRUE);
	  		}
	  	}
	  	return $total;
	}

	public function displayHand($hidden = FALSE) {
		if ($hidden) {
			echo "<p>";
			foreach ($this->hand as $key => $card) {
				if ($key > 0) {
					echo "[???] ";
				} else {
					echo "[{$card->getFace()} {$card->getSuit()}] ";
				}
			}
			echo "</p>";
		} else {
			echo "<p>";
			foreach ($this->hand as $key => $card) {
				echo "[{$card->getFace()} {$card->getSuit()}] ";
			}
			echo "</p>";
		}
	}
}

$deck = new Deck();

foreach ($suits as $suit) {
	foreach ($cards as $card) {
		$deck->fullDeck[] = new Card($card, $suit);
	}
}
$deck->shuffleDeck();

$player = new Hand();
$dealer = new Hand();

if (isset($_SESSION['player']) && isset($_SESSION['dealer'])) {
	$player->hand = $_SESSION['player'];
	$dealer->hand = $_SESSION['dealer'];
	$deck->fullDeck = $_SESSION['deck'];
}

if (empty($player->hand) && empty($dealer->hand)) {
	$deck->drawCard($player);
	$deck->drawCard($player);
	$deck->drawCard($dealer);
	$deck->drawCard($dealer);
}

if (!isset($_POST['stay']) || $dealer->getScore() != 21) {
	if (isset($_POST['hit']) && $_POST['hit'] === 'yes') {
		$deck->drawCard($player);
	}
} else {
	while ($dealer->getScore() < 17) {
		$deck->drawCard($dealer);
	}
}

$_SESSION['player'] = $player->hand;
$_SESSION['dealer'] = $dealer->hand;
$_SESSION['deck'] = $deck->fullDeck;
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
	<link rel="shortcut icon" href="img/Arches v2-6.jpg" />
	<title>Blackjack</title>
</head>
<body>
	<div id="navbar" class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="row">
				<div class="navbar-header">
					<a href="/" class="navbar-brand">Cameron's Codeup.dev Page</a>
					<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse navHeaderCollapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="../">Codeup.dev</a></li>
						<li><a href="../hello-world.html">Profile</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Social Media <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="https://www.facebook.com/CameronHolland">Facebook</a></li>
								<li><a href="https://twitter.com/xCAMER0Nx">Twitter</a></li>
								<li><a href="http://instagram.com/xshaftx">Instagram</a></li>
								<li><a href="http://www.linkedin.com/in/cameronholland90/">LinkedIn</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Projects<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="todo-list.php">To Do List</a></li>
								<li><a href="address_book.php">Address Book</a></li>
								<li><a href="yahtzee.php">Yahtzee</a></li>
								<li><a href="blackjack.php">Blackjack</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div style="text-align:center;margin:50px;">
		<img style="float:none;" src="data:image/png;base64,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"/>
		<h1>Welcome to Cameron Holland's Codeup.dev page</h1>
	</div>
	<hr />
	<h1>Blackjack</h1>
	<?php if ($dealer->getScore() >= 17 && ($player->getScore() === 21 || isset($_POST['stay']))) { ?>
		<?php if ($dealer->getScore() > 21) { ?>
			<h3>Player Wins! Dealer Busted!</h3>
		<?php } elseif ($player->getScore() > 21) { ?>
			<h3>Dealer Wins! Player Busted!</h3>
		<?php } elseif ($dealer->getScore() > $player->getScore()) { ?>
			<h3>Dealer Wins!</h3>
		<?php } elseif ($dealer->getScore() < $player->getScore()) { ?>
			<h3>Player Wins!</h3>
		<?php } else { ?>
			<h3>Dealer Wins!</h3>
		<?php } ?>
		<h3>Dealer Hand <?= "Score: " . $dealer->getScore(); ?></h3>
		<?php $dealer->displayHand(); ?>
		<br>
		<h3>Player Hand <?= "Score: " . $player->getScore(); ?></h3>
		<?php $player->displayHand(); ?>
		<br>
		<form method="POST" action="">
			<button name="playagain" type="submit" value="yes">Play Again</button>
		</form>
	<?php } elseif ($player->getScore() !== 21 || !isset($_POST['stay'])) { ?>
		<h3>Dealer Hand <?= "Score: ??"; ?></h3>
		<?php $dealer->displayHand(TRUE); ?>
		<br>
		<h3>Player Hand <?= "Score: " . $player->getScore(); ?></h3>
		<?php $player->displayHand(); ?>
		<br>
		<form method="POST" action="">
			<button name="hit" type="submit" value="yes">Hit</button>
			<button name="stay" type="submit" value="yes">Stay</button>
		</form>
	<?php } ?>
</body>
</html>