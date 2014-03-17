<?php

class Datafile {
	private $querySet;
	private $user = 'codeup';
	private $password = 'password';
	private $table;
	private $database;
	private $sortBy;
	private $mysqli;
	public $errorMessage = '';
	public $entry;

	public function __construct($table, $database, $sortBy = '') {
		$this->table = $table;
		$this->database = $database;
		$this->sortBy = $sortBy;
		$this->readDatabase();
	}

	public function readDatabase() {
		if ($this->sortBy == '') {
			$this->mysqli = new mysqli('127.0.0.1', $this->user, $this->password, $this->database);

			// Check for errors
			if ($this->mysqli->connect_errno) {
			    echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
			}

			// Retrieve a result set using SELECT
			$result = $this->mysqli->query("SELECT * FROM {$this->table}");
			$this->querySet = array();

			// Use print_r() to show rows using MYSQLI_ASSOC
			while ($row = $result->fetch_assoc()) {
			    $this->querySet[] = $row;
			}
		} else {
			$this->mysqli = new mysqli('127.0.0.1', $this->user, $this->password, $this->database);

			// Check for errors
			if ($this->mysqli->connect_errno) {
			    echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
			}

			// Retrieve a result set using SELECT
			$result = $this->mysqli->query("SELECT * FROM {$this->table} ORDER BY {$this->sortBy};");
			$this->querySet = array();
			// Use print_r() to show rows using MYSQLI_ASSOC
			while ($row = $result->fetch_assoc()) {
			    $this->querySet[] = $row;
			}
		}
	}

	public function getQuerySet() {
		return $this->querySet;
	}

	public function addItem() {
		if (empty($this->entry['name']) || empty($this->entry['location'])  || empty($this->entry['date_established'])  || empty($this->entry['area_in_acres'])  || empty($this->entry['description'])) {
			$this->errorMessage = 'Please enter text into the required fields';
			return FALSE;
		}
		try {
			$this->errorMessage = '';
			// Create the prepared statement
			$stmt = $this->mysqli->prepare("INSERT INTO {$this->table} (name, location, date_established, area_in_acres, description) VALUES (?, ?, ?, ?, ?)");

			// bind parameters
			$stmt->bind_param("sssds", $this->entry['name'], $this->entry['location'], $this->entry['area_in_acres'], $this->entry['date_established'], $this->entry['description']);
			// execute query, return result
			$stmt->execute();
			$this->mysqli->close();
		} catch (Exception $e) {
			$this->errorMessage = 'Please enter valid information into each field';
		}
	}
}

?>