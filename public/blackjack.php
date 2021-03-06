<?php

session_start();
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/blackjack.php') {
	session_destroy();
	session_start();
} elseif (isset($_POST['restart']) || ((isset($_POST['playagain']) && count($_SESSION['deck']->fullDeck)) < 10)) {
	session_destroy();
	session_start();
} elseif (isset($_POST['playagain'])) {
	unset($_SESSION['player']);
	unset($_SESSION['dealer']);
	unset($_SESSION['win']);
}

class Card {
	private $cardValue;
	private $cardSuit;
	private $cardFaceValue;
	private $color;

	public function __construct($value, $suit) {
		$this->cardFaceValue = $value;
		$this->cardSuit = $suit;
		$this->cardValue = $this->setValue();
		if ($this->cardSuit === '&hearts;' || $this->cardSuit === '&diams;') {
			$this->color = 'red';
		} else {
			$this->color = 'black';
		}
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

	public function getColor() {
		return $this->color;
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
	// create an array for suits
	public $suits = array('&clubs;', '&hearts;', '&spades;', '&diams;');

	// create an array for cards
	public $cards = array('A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K');

	public function __construct() {
	}

	public function drawCard(&$player) {
		array_push($player->hand, $this->fullDeck[0]);
		unset($this->fullDeck[0]);
		$this->fullDeck = array_values($this->fullDeck);
	}

	public function shuffleDeck() {
		shuffle($this->fullDeck);
	}

	public function displayDeck() {
		foreach ($this->fullDeck as $key => $card) {
			if ($key === 0) {
				echo "<img class='outline shadow rounded cardback' src='img/music-card-back.jpg'/>";
			} else {
				echo "<img class='outline shadow rounded deckoverlay cardback' src='img/music-card-back.jpg'/>";
			}
		}
	}
}

class Hand {
	public $hand = array();

	public function __construct() {
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
	}

	public function getScore($hidden = FALSE) {
		if ($hidden === TRUE) {
			return '??';
		}
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
		$overlay = '';
		if ($hidden) {
			foreach ($this->hand as $key => $card) {
				$overlay = 'overlay';
				if ($card->getColor() === 'red') {
					$cardColor = "#cc0033";
				} else {
					$cardColor = "#000001";
				}	
				if ($key == 0) {
					echo "<img class='outline shadow rounded cardback' src='img/music-card-back.jpg'/>";
				} else {
					echo "<div class='outline shadow rounded $overlay' style='color: $cardColor;'>
					  <div class='top'>" . $card->getFace() . $card->getSuit() . "</div>
					  <h1>" . $card->getSuit() . "</h1>
					  <div class='bottom'><br>" . $card->getSuit() . $card->getFace() . "</div>
					  </div>";
				}	
			}
		} else {
			foreach ($this->hand as $key => $card) {
				if ($key > 0) {
					$overlay = 'overlay';
				}
				if ($card->getColor() === 'red') {
					$cardColor = "#cc0033";
				} else {
					$cardColor = "#000001";
				}
				echo "<div class='outline shadow rounded $overlay' style='color: $cardColor;'>
					  <div class='top'>" . $card->getFace() . $card->getSuit() . "</div>
					  <h1>" . $card->getSuit() . "</h1>
					  <div class='bottom'><br>" . $card->getSuit() . $card->getFace() . "</div>
					  </div>";
			}
		}
	}
}
if (empty($_SESSION['deck'])) {
	$_SESSION['deck'] = new Deck();
	foreach ($_SESSION['deck']->suits as $suit) {
		foreach ($_SESSION['deck']->cards as $card) {
			$_SESSION['deck']->fullDeck[] = new Card($card, $suit);
		}
	}
	$_SESSION['deck']->shuffleDeck();
}

if (empty($_SESSION['player']) && empty($_SESSION['dealer'])) {
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
						<li><a href="https://github.com/cameronholland90">GitHub</a></li>
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
	<div class="board">
		<?php $hide = TRUE; ?>
		<?php if ((($_SESSION['player']->getScore() === 21 || $_SESSION['player']->getScore() > 21) || isset($_POST['stay'])) || (isset($_SESSION['win']))) { ?>
			<?php $hide = FALSE; ?>
		<?php } ?>
		<div class='boardrow'>
			<div class='hand'>
				<h3>Player Hand <?= "Score: " . $_SESSION['player']->getScore(); ?></h3>
				<?php $_SESSION['player']->displayHand(); ?>
			</div>
			<div class='hand'>
				<h3>Dealer Hand <?= "Score: " . $_SESSION['dealer']->getScore($hide); ?></h3>
				<?php $_SESSION['dealer']->displayHand($hide); ?>
			</div>
			<div class="deck">
				<h3>Deck <?= "Card Count: " . count($_SESSION['deck']->fullDeck); ?></h3>
				<?php $_SESSION['deck']->displayDeck(); ?>
			</div>
		</div>
		<div class='boardrow'>
			<div class='userstuff'>
				<?php if ((($_SESSION['player']->getScore() === 21 || $_SESSION['player']->getScore() > 21) || isset($_POST['stay'])) || (isset($_SESSION['win']))) { ?>
					<form method="POST" action="">
						<button name="playagain" type="submit" value="yes" autofocus="autofocus">Play Again</button>
						<button name="restart" type="submit" value="yes">Restart Game</button>
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
						<button name="hit" type="submit" value="yes" autofocus="autofocus">Hit</button>
						<button name="stay" type="submit" value="yes">Stay</button>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
</body>
</html>