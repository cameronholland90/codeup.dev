<?php

class Filestore {

    private $filename = "";
    public $errorMessage = "";
    public $entry;
    private $is_csv = FALSE;

    function __construct($location = '') {
    	$this->filename = $location;
        if (substr($this->filename, -3) === 'csv') {
            $this->is_csv = TRUE;
        } else {
            $this->is_csv = FALSE;
        }
    }

    public function get_name() {
        return $this->filename;
    }

    public function set_name($location) {
        $this->filename = $location;
    }

    public function read() {
        if ($this->is_csv) {
            return $this->read_csv();
        } else {
            return $this->read_lines();
        }
    }

    public function write($array) {
        if ($this->is_csv) {
            $this->write_csv($array);
        } else {
            $this->write_lines($array);
        }
    }

    /**
     * Returns array of lines in $this->filename
     */
    private function read_lines()
    {
    	$handle = fopen($this->filename, "r");
		$filesize = filesize($this->filename);
		if($filesize != 0) {
			$openList = fread($handle, $filesize);
			$openList = explode("\n", $openList);
		} else {
			$openList = array();
		} 
		fclose($handle);
		return $openList;
    }

    /**
     * Writes each element in $array to a new line in $this->filename
     */
    private function write_lines($array)
    {
    	$handle = fopen($this->filename, "w");
		$saveList = implode("\n", $array);
		fwrite($handle, $saveList);
		fclose($handle);
    }

    /**
     * Reads contents of csv $this->filename, returns an array
     */
    private function read_csv()
    {
    	$handle = fopen($this->filename, "r");
		$filesize = filesize($this->filename);
		$openList = array();
		if($filesize != 0) {
			while(($data = fgetcsv($handle)) !== FALSE) {
				$openList[] = $data;
			}
		} else {
			$openList = array();
		} 
		fclose($handle);
		return $openList;
    }

    /**
     * Writes contents of $array to csv $this->filename
     */
    private function write_csv($arrays)
    {
    	// Code to write $array to file $this->filename
        $handle = fopen($this->filename, 'w');
		foreach ($arrays as $fields) {
			if (!empty($fields)) {
				fputcsv($handle, $fields);
			}
		}
		fclose($handle);
    }

    public function addItem($array) {
        if (empty($this->entry['todoitem'])) {
            throw new TooSmallException('One of the required fields was left blank');
        } elseif (strlen($this->entry['todoitem']) > 240){
            throw new TooBigException('One of the required fields was over 240 characters');
        } else {
            $array[] = $this->entry['todoitem'];
            $this->errorMessage = "";
        }
        return $array;
    }

}