<?php

session_start();

if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/yahtzee.php') {
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
	<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
	<link rel="shortcut icon" href="img/Arches v2-6.jpg" />
	<title>Yahtzee</title>
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
								<li><a href="/todo-list.php">To Do List</a></li>
								<li><a href="/address_book.php">Address Book</a></li>
								<li><a href="/yahtzee.php">Yahtzee</a></li>
								<li><a href="/blackjack.php">Blackjack</a></li>
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
	<h3>Yahtzee</h3>
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
			        <button type="submit">Roll</button>
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
			        <button type="submit">Submit choice/Start Next Turn</button>
			    </p>
			</form>
		<?php endif; ?>
	<?php } else { ?>
		<h3><?= "GAME OVER"?></h3>
		<h3><?= "Your final score was " . $_SESSION['gamescore'] . "!" ?></h3>
		<form method="POST" action="">
			<button type="submit">Play Again</button>
		</form>
		<?php session_destroy(); ?>
	<?php } ?>

</body>
</html>