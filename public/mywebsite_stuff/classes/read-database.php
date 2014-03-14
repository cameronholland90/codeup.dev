<?php

class Datafile {
	private $querySet;
	private $user = 'codeup';
	private $password = 'password';
	private $table;
	private $database;
	private $sortBy;
	private $mysqli;

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
			$result = $this->mysqli->query("SELECT * FROM national_parks");
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
			$result = $this->mysqli->query("SELECT * FROM national_parks ORDER BY {$this->sortBy};");
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
}

?>