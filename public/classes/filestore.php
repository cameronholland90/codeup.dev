<?php

class Filestore {

    public $filename = "";

    function __construct($location = '') {
    	$this->filename = $location;
    }

    /**
     * Returns array of lines in $this->filename
     */
    function read_lines()
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
    function write_lines($array)
    {
    	$handle = fopen($this->filename, "w");
		$saveList = implode("\n", $array);
		fwrite($handle, $saveList);
		fclose($handle);
    }

    /**
     * Reads contents of csv $this->filename, returns an array
     */
    function read_csv()
    {
    	$handle = fopen($this->filename, "r");
		$filesize = filesize($this->filename);
		$openList = [];
		if($filesize != 0) {
			while(!feof($handle)) {
				$openList[] = fgetcsv($handle);
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
    function write_csv($array)
    {
    	// Code to write $array to file $this->filename
        $handle = fopen($this->filename, 'w');
		foreach ($array as $fields) {
			if ($fields != '') {
				fputcsv($handle, $fields);
			}
		}
		fclose($handle);
    }

}