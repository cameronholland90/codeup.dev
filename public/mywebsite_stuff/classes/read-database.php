<?php

class Datafile {
	protected $querySet;
	protected $user = 'codeup';
	protected $password = 'password';
	protected $table;
	protected $database;
	protected $sortBy;
	public $errorMessage = '';
	public $entry;

	public function __construct($table, $database, $sortBy = '') {
		$this->table = $table;
		$this->database = $database;
		$this->sortBy = $sortBy;
		$this->readDatabase();
	}

	public function readDatabase() {
		$mysqli = $this->connectToDb();

		if ($this->sortBy == '') {

			// Check for errors
			if ($mysqli->connect_errno) {
			    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
			}

			// Retrieve a result set using SELECT
			$result = $mysqli->query("SELECT * FROM {$this->table}");
			$this->querySet = array();

			while ($row = $result->fetch_assoc()) {
			    $this->querySet[] = $row;
			}
		} else {

			// Check for errors
			if ($mysqli->connect_errno) {
			    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
			}

			// Retrieve a result set using SELECT
			$result = $mysqli->query("SELECT * FROM {$this->table} ORDER BY {$this->sortBy};");
			$this->querySet = array();

			while ($row = $result->fetch_assoc()) {
			    $this->querySet[] = $row;
			}
		}
	}

	public function getQuerySet() {
		return $this->querySet;
	}

	public function addItem() {
		$mysqli = $this->connectToDb();

		if (empty($this->entry['name']) || empty($this->entry['location'])  || empty($this->entry['date_established'])  || empty($this->entry['area_in_acres'])  || empty($this->entry['description'])) {
			$this->errorMessage = 'Please enter text into the required fields';
			return FALSE;
		}
		$this->errorMessage = '';
		// Create the prepared statement
		$stmt = $mysqli->prepare("INSERT INTO {$this->table} (name, location, area_in_acres, date_established, description) VALUES (?, ?, ?, ?, ?)");

		// bind parameters
		$stmt->bind_param("ssdss", $this->entry['name'], $this->entry['location'], $this->entry['area_in_acres'], $this->entry['date_established'], $this->entry['description']);
		// execute query, return result
		$stmt->execute();
	}

	protected function connectToDb() {
		return new mysqli('127.0.0.1', $this->user, $this->password, $this->database);
	}
}

class TodoDatafile extends Datafile {
	public $pageCount;

	public function __construct($table, $database, $sortBy = '') {
		parent::__construct($table, $database, $sortBy);
		$this->setPageCount();
	}

	public function addItem() {
		$mysqli = $this->connectToDb();

		if (empty($this->entry['todoitem'])) {
			throw new TooSmallException('One of the required fields was left blank');
		} elseif (strlen($this->entry['todoitem']) > 240) {
			throw new TooBigException('One of the required fields was over 240 characters');
		}

		$this->errorMessage = '';
		// Create the prepared statement
		
		$stmt = $mysqli->prepare("INSERT INTO {$this->table} (todo_item) VALUES (?)");

		// bind parameters
		$stmt->bind_param("s", $this->entry['todoitem']);
		// execute query, return result
		$stmt->execute();

	}

	public function setPageCount() {
		$mysqli = $this->connectToDb();
		$result = $mysqli->query("SELECT * FROM {$this->table} WHERE completed = 0");
		$row_cnt = $result->num_rows;
		$this->pageCount = (int)($row_cnt / 10);
	}

	public function completeItem($remove) {
		$mysqli = $this->connectToDb();

		$completed = $this->querySet[$remove][0];
		$stmt = $mysqli->prepare("UPDATE {$this->table} SET completed = 1 WHERE id = ?");

		$stmt->bind_param("i", $completed);
		$stmt->execute();
		$mysqli->close();
	}

	public function readDatabase($page = 0) {
		$mysqli = $this->connectToDb();

		// Check for errors
		if ($mysqli->connect_errno) {
		    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		}

		$pageStart = $page * 10;
		// Retrieve a result set using SELECT
		$result = $mysqli->query("SELECT * FROM {$this->table} WHERE completed = 0 LIMIT {$pageStart}, 10");
		$this->querySet = array();

		while ($row = $result->fetch_row()) {
		    $this->querySet[] = $row;
		}
	}
}

?>