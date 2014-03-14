<?php

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
		foreach ($this->suits as $suit) {
			foreach ($this->cards as $card) {
				$this->fullDeck[] = new Card($card, $suit);
			}
		}
		$this->shuffleDeck();
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
					  <h1 style='color: $cardColor;'>" . $card->getSuit() . "</h1>
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
					  <h1 style='color: $cardColor;'>" . $card->getSuit() . "</h1>
					  <div class='bottom'><br>" . $card->getSuit() . $card->getFace() . "</div>
					  </div>";
			}
		}
	}
}

class GoFishHand extends Hand {
	private $fishCount = 0;

	public function __construct() {
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
		$_SESSION['deck']->drawCard($this);
	}

	public function displayHand($hidden = FALSE) {
		$overlay = '';
		// if ($hidden) {
		// 	foreach ($this->hand as $key => $card) {
		// 		$overlay = 'gofish';
		// 		if ($card->getColor() === 'red') {
		// 			$cardColor = "#cc0033";
		// 		} else {
		// 			$cardColor = "#000001";
		// 		}	
		// 		if ($key == 0) {
		// 			echo "<img class='outline shadow rounded cardback' src='img/music-card-back.jpg'/>";
		// 		} else {
		// 			echo "<img class='outline shadow rounded cardback $overlay' src='img/music-card-back.jpg'/>";
		// 		}	
		// 	}
		// } else {
			foreach ($this->hand as $key => $card) {
				$overlay = '';
				if ($key > 0) {
					$overlay = 'gofish';
				}
				if ($card->getColor() === 'red') {
					$cardColor = "#cc0033";
				} else {
					$cardColor = "#000001";
				}
				echo "<div class='outline shadow rounded $overlay' style='color: $cardColor;'>
					  <div class='top'>" . $card->getFace() . $card->getSuit() . "</div>
					  <h1 style='color: $cardColor;'>" . $card->getSuit() . "</h1>
					  <div class='bottom'><br>" . $card->getSuit() . $card->getFace() . "</div>
					  </div>";
			}
		// }
	}

	public function displayOptions() {
		$values = array();
		foreach ($this->hand as $key => $card) {
			if (!in_array($card->getFace(), $values)) {
				$values[] = $card->getFace();
			}
		}
		asort($values);
		echo "Do you have any...";
		echo "<form method='POST' action=''>";
		foreach ($values as $key => $value) {
			echo "<button class='btn-md btn-success' name='ask' value='{$value}'>{$value}'s</button>";
		}
		echo "</form>";
	}

	public function checkForFish() {

	}

	public function getFishCount() {

	}

	public function computerQuestion() {
		$values = array();
		foreach ($this->hand as $key => $card) {
			if (!in_array($card->getFace(), $values)) {
				$values[] = $card->getFace();
			}
		}
		asort($values);
		$computerChoice = mt_rand(0, count($values));
		return $values[$computerChoice];
	}

	public function checkHandForCard($faceValue) {
		$cardsToGive = array();
		foreach ($this->hand as $key => $card) {
			if ($faceValue === $card->getFace()) {
				$cardsToGive[] = $card;
				unset($this->hand[$key]);
			}
		}
		$this->hand = array_values($this->hand);
		if (empty($cardsToGive)) {
			return FALSE;
		} else {
			return $cardsToGive;
		}
	}

	public function addCardsToHand($cardsToRecieve) {
		$this->hand = array_merge($this->hand, $cardsToRecieve);
		$this->hand = array_values($this->hand);
	}
}

?>