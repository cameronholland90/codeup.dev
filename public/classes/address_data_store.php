<?php

require_once('filestore.php');

class AddressDataStore extends Filestore {

	public $errorMessage = "";
	public $entry;

    function read_address_book() {
        return $this->read_csv();
    }

    function write_address_book($addresses_array) {
    	$this->write_csv($addresses_array);
    }

    function addItem($addressBook) {
		if (($this->entry['name'] == '' || $this->entry['address'] == '' || $this->entry['city'] == '' || $this->entry['state'] == '' || $this->entry['zip'] == '') && (empty($_FILES))) {
			$this->errorMessage = "Please enter required information";
		} else {
			$addressBook[] = $this->entry;
			$this->errorMessage = "";
		}
		return $addressBook;
	}

}

?>