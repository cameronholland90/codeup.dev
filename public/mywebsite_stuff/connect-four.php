<?php

session_start();

if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/mywebsite_stuff/connect-four.php') {
	session_destroy();
	session_start();
} elseif(isset($_POST['playagain'])) {
	session_destroy();
	session_start();
}

class Board {
	public $gameboard;											// array for gameboard
	public $blackgamepiece = 'img/black-connect-four.jpg';		// holds image location for a spot holding a black game piece
	public $redgamepiece = 'img/red-connect-four.jpg';			// holds image location for a spot holding a red game piece
	public $blanklocation = 'img/empty-connect-four.jpg';		// holds image location for a spot that does not have a game piece
	public $turnCount = 1;

	public function __construct() {
		$this->gameboard = array();
		for ($i=0; $i < 6; $i++) { 
			$this->gameboard[] = array($this->blanklocation, $this->blanklocation, $this->blanklocation, $this->blanklocation, $this->blanklocation, $this->blanklocation, $this->blanklocation);
		}
	}

	public function displayBoard() {
		foreach ($this->gameboard as $ycoord => $row) {
			echo "<div class='row'>";
			foreach ($row as $xcoord => $location) {
				echo "<div class='col-md-1' style='padding: 0px 3px;'><img class='img-responsive' src='{$location}' /></div>";
			}
			echo "</div>";
		}
	}

	public function placePiece($col) {
		$temp = '';
		foreach ($this->gameboard as $key => $row) {
			if ($row[$col] === 'img/empty-connect-four.jpg') {
				$temp = $key;
			}
		}

		if (is_numeric($temp) && ($this->turnCount%2 === 0)) {
			$this->gameboard[$temp][$col] = $this->redgamepiece;
			$this->turnCount++;
		} elseif (is_numeric($temp) && ($this->turnCount%2 === 1)) {
			$this->gameboard[$temp][$col] = $this->blackgamepiece;
			$this->turnCount++;
		}
	}

	public function checkForWin() {
		$win = FALSE;
		foreach ($this->gameboard as $rownum => $row) {
			foreach ($row as $colnum => $location) {
				if ($location !== $this->blanklocation) {
					if ($this->checkDiaganalLeft()) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					} elseif ($this->checkVertical()) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					} elseif ($this->checkHorizontal()) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					} elseif ($this->checkDiaganalRight()) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					}
				} 
			}
		}
		return $win;
	}

	public function checkHorizontal() {
		foreach ($this->gameboard as $rownum => $row) {
			foreach ($row as $colnum => $location) {
				if ($location !== $this->blanklocation) {
					if (($rownum <= 3) && ($this->gameboard[$rownum + 1][$colnum] === $location) && ($this->gameboard[$rownum + 2][$colnum] === $location) && ($this->gameboard[$rownum + 3][$colnum] === $location)) {
						return TRUE;
					} elseif (($rownum >= 3) && ($this->gameboard[$rownum - 1][$colnum] === $location) && ($this->gameboard[$rownum - 2][$colnum] === $location) && ($this->gameboard[$rownum - 3][$colnum] === $location)) {
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	public function checkVertical() {
		foreach ($this->gameboard as $rownum => $row) {
			foreach ($row as $colnum => $location) {
				if ($location !== $this->blanklocation) {
					if (($colnum <= 3) && ($this->gameboard[$rownum][$colnum + 1] === $location) && ($this->gameboard[$rownum][$colnum + 2] === $location) && ($this->gameboard[$rownum][$colnum + 3] === $location)) {
						return TRUE;
					} elseif (($colnum >= 3) && ($this->gameboard[$rownum][$colnum - 1] === $location) && ($this->gameboard[$rownum][$colnum - 2] === $location) && ($this->gameboard[$rownum][$colnum - 3] === $location)) {
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	public function checkDiaganalRight() {
		foreach ($this->gameboard as $rownum => $row) {
			foreach ($row as $colnum => $location) {
				if ($location !== $this->blanklocation) {
					if (($rownum <= 3 && $colnum <= 3) && ($this->gameboard[$rownum + 1][$colnum + 1] === $location) && ($this->gameboard[$rownum + 2][$colnum + 2] === $location) && ($this->gameboard[$rownum + 3][$colnum + 3] === $location)) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					} elseif (($rownum >= 3 && $colnum >= 3) && ($this->gameboard[$rownum - 1][$colnum - 1] === $location) && ($this->gameboard[$rownum - 2][$colnum - 2] === $location) && ($this->gameboard[$rownum - 3][$colnum - 3] === $location)) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	public function checkDiaganalLeft() {
		foreach ($this->gameboard as $rownum => $row) {
			foreach ($row as $colnum => $location) {
				if ($location !== $this->blanklocation) {
					if (($rownum >= 3 && $colnum <= 3) && ($this->gameboard[$rownum - 1][$colnum + 1] === $location) && ($this->gameboard[$rownum - 2][$colnum + 2] === $location) && ($this->gameboard[$rownum - 3][$colnum + 3] === $location)) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					} elseif (($rownum <= 3 && $colnum >= 3) && ($this->gameboard[$rownum + 1][$colnum - 1] === $location) && ($this->gameboard[$rownum + 2][$colnum - 2] === $location) && ($this->gameboard[$rownum + 3][$colnum - 3] === $location)) {
						$_SESSION['win'] = TRUE;
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	public function playerColor() {
		if (isset($_SESSION['win'])) {
			$this->turnCount-=1;
		}
		if ($this->turnCount%2 === 0) {
			return 'RED';
		} else {
			return 'BLACK';
		}
	}
}

if (empty($_SESSION['gameboard'])) {
	$_SESSION['gameboard'] = new Board();
}

if (isset($_GET['drop']) && is_numeric($_GET['drop'])) {
	$_SESSION['gameboard']->placePiece($_GET['drop']);
	header("Location: connect-four.php");
	exit(0);
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
	<title>Connect Four</title>
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

	<div class='container' style='color: rgb(192, 192, 192);background-color: #222; border-radius: 10px; padding: 50px; margin-top: 100px;'>
		<div class="page-header" style="margin-top: 0px;">
			<h1>Connect Four <small>Coded by Cameron Holland</small></h1>
		</div>
		<?php if (!($_SESSION['gameboard']->checkForWin())) { ?>
			<div class='row'>
				<?php foreach ($_SESSION['gameboard']->gameboard[0] as $key => $row): ?>
					<?= "<div class='col-md-1' style='padding: 0px 3px 3px; text-align: center;'><a href='connect-four.php?drop=$key'>" . ($key + 1) . "</a></div>" ?>
				<?php endforeach ?>
			</div>
		<?php } ?>
		<?php $_SESSION['gameboard']->displayBoard(); ?>
		<div class='row'>
			<?php if ($_SESSION['gameboard']->checkForWin()) { ?>
				<?= "<h1>GAME OVER. " . $_SESSION['gameboard']->playerColor() . " WINS!</h1>"; ?>
				<form method='POST' action=''>
					<button class='btn-md btn-success' name="playagain" type="submit" value="yes" autofocus="autofocus">Play Again</button>
				</form>
			<?php } else { ?>
				<?=	"<h3>" . $_SESSION['gameboard']->playerColor() . "'s Turn</h3>" ?>
			<?php } ?>
		</div>
	</div>
</body>
</html>