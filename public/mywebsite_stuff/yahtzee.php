<?php

session_start();

if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/mywebsite_stuff/yahtzee.php') {
	session_destroy();
	session_start();
}

class diceHand {
	public $displayDice = array(0, "&#9856;", "&#9857;", "&#9858;", "&#9859;", "&#9860;", "&#9861;");
	public $dice;											// array that holds face values of dice
	public $diceToReroll;									// array that will hold index of dice to reroll each turn
	public $rollcount = 0;									// keeps track of how many times the user has rolled(3 is maximum including initial roll)
	public $gameScore = 0;									// keeps track of score for the whole game
	public $handOptions = array();							// holds score options for user at end of turn
	public $scores = array();								// holds scores for each option for the users hand
	public $remainingOptions = array("Yahtzee", "Four of a Kind", "Full House", "Three of a Kind", "Large Straight", "Small Straight", 
	"Chance");

	function __construct() {
		if (isset($_SESSION['count']) && isset($_POST['donerolling']) &&$_POST['donerolling']==='yes') {
			$_SESSION['count'] = 2;
			$rollcount = 3;
		}
		if (!isset($_SESSION['count'])) {
			$this->dice = array(0, 0, 0, 0, 0);					// array that holds face values of dice
			$this->diceToReroll = array(0, 1, 2, 3, 4);			// array that will hold index of dice to reroll each turn
			$this->gameScore = 0;								// keeps track of score
			$this->remainingOptions = array("Yahtzee", "Four of a Kind", "Full House", "Three of a Kind", "Large Straight", "Small Straight", 
	"Chance");
		}
		elseif ($_SESSION['count'] === 3) {
			$this->dice = array(0, 0, 0, 0, 0);										// array that holds face values of dice
			$this->diceToReroll = array(0, 1, 2, 3, 4);								// array that will hold index of dice to reroll each turn
			$this->gameScore = $_SESSION['gamescore'];								// keeps track of score
			$this->remainingOptions = $_SESSION['remaining'];
			$this->remainingOptions = array_values($this->remainingOptions);
			if (isset($_POST['choice'])) {
				$key = $_POST['choice'];
				$choice = array_search($_SESSION['handoptions'][$key], $this->remainingOptions);
				$this->gameScore += $_SESSION['scoreoptions'][$key];
				unset($this->remainingOptions[$choice]);
			}
		} else {
			$this->dice = $_SESSION['dice'];
			if (isset($_POST['dice'])) {
				$this->diceToReroll = $_POST['dice'];
			} else {
				$this->diceToReroll = array();
			}
			$this->gameScore = $_SESSION['gamescore'];
			$this->rollcount = $_SESSION['count'];
			$this->remainingOptions = $_SESSION['remaining'];
			$this->remainingOptions = array_values($this->remainingOptions);
			if (isset($_POST['choice'])) {
				$key = $_POST['choice'];
				$choice = array_search($_SESSION['handOptions'][$key], $this->remainingOptions);
				$this->gameScore += $_SESSION['scoreoptions'][$key];
				unset($this->remainingOptions[$choice]);
			}
		}

	}

	public function rollDice() {
		if ($this->rollcount === 3) {
			$this->rollcount = 0;
		}

		for ($i = 0; $i < count($this->diceToReroll); $i++) { 
			$temp = $this->diceToReroll[$i];
			$this->dice[$temp] = mt_rand(1, 6);
		}
		$this->diceToReroll = array();
		$this->rollcount++;
		$_SESSION['count'] = $this->rollcount;
	}

	public function typeOfHand() {
		$this->handOptions = array();
		$this->scores = array();
		$valueCount = array(0, 0, 0, 0, 0, 0);
		$tempScore = 0;

		// counts how many dice are at each value
		$valueCount[0] = count(array_keys($this->dice, 1));
		$valueCount[1] = count(array_keys($this->dice, 2));
		$valueCount[2] = count(array_keys($this->dice, 3));
		$valueCount[3] = count(array_keys($this->dice, 4));
		$valueCount[4] = count(array_keys($this->dice, 5));
		$valueCount[5] = count(array_keys($this->dice, 6));
		$tempScore = array_sum($this->dice);

		// based on how many of each value you have, $this->handOptions gets each category your dice qualify for
		if (in_array(5, $valueCount) ) {
			$this->handOptions[] = "Yahtzee";
			$this->scores[] = 50;
		} 
		if (in_array(4, $valueCount) && in_array("Four of a Kind", $this->remainingOptions)) {
			$this->handOptions[] = "Four of a Kind";
			$this->scores[] = $tempScore;
		} 
		if ((in_array(3, $valueCount) && in_array(2, $valueCount)) && in_array("Full House", $this->remainingOptions)) {
			$this->handOptions[] = "Full House";
			$this->scores[] = 25;
		} 
		if (in_array(3, $valueCount) && in_array("Three of a Kind", $this->remainingOptions)) {
			$this->handOptions[] = "Three of a Kind";
			$this->scores[] = $tempScore;
		} 
		if (((array_search(0, $valueCount) === 0 || array_search(0, $valueCount) === 5) && (count(array_keys($valueCount, 0)) === 1)) 
			&& in_array("Large Straight", $this->remainingOptions)) {
			$this->handOptions[] = "Large Straight";
			$this->scores[] = 40;
		}

		if ((($valueCount[0] >= 1 && $valueCount[1] >= 1 && $valueCount[2] >= 1 && $valueCount[3] >= 1) || 
				($valueCount[1] >= 1 && $valueCount[2] >= 1 && $valueCount[3] >= 1 && $valueCount[4] >= 1) || 
				($valueCount[2] >= 1 && $valueCount[3] >= 1 && $valueCount[4] >= 1 && $valueCount[5] >= 1))
				&& in_array("Small Straight", $this->remainingOptions)) {
			$this->handOptions[] = "Small Straight";
			$this->scores[] = 30;
		}
		if (in_array("Chance", $this->remainingOptions)) {
			$this->handOptions[] = "Chance";
			$this->scores[] = $tempScore;
		}
		if (empty($this->handOptions) && empty($this->scores)) {
			$this->handOptions = $this->remainingOptions;
			foreach ($this->remainingOptions as $key => $value) {
				$this->scores[] = 0;
			}
		}
	}
}

$hand = new diceHand();
$hand->rollDice();
$_SESSION['gamescore'] = $hand->gameScore;
$_SESSION['dice'] = $hand->dice;
$_SESSION['remaining'] = $hand->remainingOptions;
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
	<title>Yahtzee</title>
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
		<div class='page-header'>
			<h1>Yahtzee <small>Coded by Cameron Holland</small></h1>
		</div>
		<?php if (!empty($hand->remainingOptions)) { ?>
			<h4><?= "Your score: " . $hand->gameScore; ?></h4>
			<h4><?= "You have rolled " . $hand->rollcount . " time(s)." ?></h4>
			<br>
			<?php if ($hand->rollcount < 3) { ?>
				<form method="POST" action="">

					<p>Which dice would you like to reroll?</p>
					<?php foreach ($hand->dice as $key => $value): ?>
						<?= "<label for='die{$key}'><input type='checkbox' id='die{$key}' name='dice[]' value='{$key}'><span class = 'die'> " . $hand->displayDice[$value] . "</span></label>"; ?>
					<?php endforeach; ?>
					<p>
						<label for='donerolling'><input type='checkbox' id='donerolling' name='donerolling' value='yes'>I would like to keep my dice</label>
					</p>
					<p>
				        <button class='btn-md btn-success' type="submit">Roll</button>
				    </p>
				</form>
			<?php } else { ?>
				<?php foreach ($hand->dice as $key => $value): ?>
					<span class = 'die'><?= $hand->displayDice[$value] . " "; ?></span>
				<?php endforeach ?>
			<?php } ?>
			<?php if ($hand->rollcount === 3): ?>
				<form method="POST" action="">
					<?php $hand->typeOfHand(); ?>
					<?php $_SESSION['handoptions'] = $hand->handOptions; ?>
					<?php $_SESSION['scoreoptions'] = $hand->scores; ?>
					<?php foreach ($hand->handOptions as $key => $value): ?>
						<?= "<p><label for='choice{$key}'><input type='radio' id='choice{$key}' name='choice' value='{$key}' checked> " . $value . " for " . $hand->scores[$key] . " points</label></p>"; ?>
					<?php endforeach; ?>
					<p>
				        <button class='btn-md btn-success' type="submit">Submit choice/Start Next Turn</button>
				    </p>
				</form>
			<?php endif; ?>
		<?php } else { ?>
			<h3><?= "GAME OVER"?></h3>
			<h3><?= "Your final score was " . $_SESSION['gamescore'] . "!" ?></h3>
			<form method="POST" action="">
				<button class='btn-md btn-success' type="submit">Play Again</button>
			</form>
			<?php session_destroy(); ?>
		<?php } ?>
	</div>

</body>
</html>