<?php

require_once('filestore.php');

class AddressDataStore extends Filestore {

	public function __construct($location = '') {
    	$location = strtolower($location);
    	parent::__construct($location);
    }

    public function read_address_book() {
        return $this->read();
    }

    public function write_address_book($addresses_array) {
    	$this->write($addresses_array);
    }

    public function addItem($addressBook) {
		if (($this->entry['name'] == '' || $this->entry['address'] == '' || $this->entry['city'] == '' || $this->entry['state'] == '' || $this->entry['zip'] == '') && (empty($_FILES))) {
			throw new TooSmallException('One of the required fields was left blank');
		} elseif (strlen($this->entry['name']) > 125 || strlen($this->entry['address']) > 125 || strlen($this->entry['city']) > 125 || strlen($this->entry['state']) > 125 || strlen($this->entry['zip']) > 125){
			throw new TooBigException('One of the required fields was over 125 characters');
		} else {
			$addressBook[] = $this->entry;
			$this->errorMessage = "";
		}
		return $addressBook;
	}

}

?>