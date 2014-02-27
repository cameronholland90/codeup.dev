<?php
	class AddressDataStore {

	    public $filename = "";
	    public $errorMessage = "";

	    function __construct($location = '') {
	    	$this->filename = $location;
	    }

	    function read_address_book() {
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

	    function write_address_book($addresses_array) {
	        // Code to write $addresses_array to file $this->filename
	        $handle = fopen($this->filename, 'w');
			foreach ($addresses_array as $fields) {
				if ($fields != "") {
					fputcsv($handle, $fields);
				}
			}
			fclose($handle);
	    }

	    function addItem($addressBook) {
			$temp = $_POST;
			if (($temp['name'] == '' || $temp['address'] == '' || $temp['city'] == '' || $temp['state'] == '' || $temp['zip'] == '') && (empty($_FILES))) {
				$this->errorMessage = "Please enter required information";
			} else {
				$addressBook[] = $temp;
				$this->errorMessage = "";
			}
			return $addressBook;
		}

	}

	$book = new AddressDataStore("data/address_book.csv");
	$newlist = new AddressDataStore();

	$addressBook = $book->read_address_book();

	if (!empty($_POST) && empty($_FILES) ) {
		$addressBook = $book->addItem($addressBook);
	}

	if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
		$remove = $_GET['remove'];	
		unset($addressBook[$remove]);
		$book->write_address_book($addressBook);
		$_GET = array();
		header("Location: address_book.php");
		exit(0);
	}

	if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] == 'text/csv') {
	    // Set the destination directory for uploads
	    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
	    // Grab the filename from the uploaded file by using basename
	    $tempfilename = basename($_FILES['uploaded_file']['name']);
	    // Create the saved filename using the file's original name and our upload directory
	    $newlist->filename = $upload_dir . $tempfilename;
	    // Move the file from the temp location to our uploads directory
	    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $newlist->filename);
	    $appendList = $newlist->read_address_book();
	    if ($_POST['overwrite'] == "yes") {
	    	$addressBook = $appendList;
	    	$book->write_address_book($addressBook);
	    } else {
	    	$addressBook = array_merge($addressBook, $appendList);
	    	$book->write_address_book($addressBook);
	    }
	}

	$book->write_address_book($addressBook);
	$addressBook = $book->read_address_book();

?>
<!DOCTYPE html>
<html>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
	<head>
		<link rel="shortcut icon" href="img/Arches v2-6.jpg" />
		<title>Address Book</title>
	</head>
	<body>
		<div style="text-align:center;margin:50px;">
			<img style="float:none;" src="data:image/png;base64,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"/>
			<h1>Welcome to Cameron Holland's Codeup.dev page</h1>
		</div>
		<div id = "navbar">
			<hr />
			<a href="/">Codeup.dev</a> |
			<a href="hello-world.html">My Profile Page</a> |
			<a href="todo-list.php">My Todo List</a> | 
			<a href="address_book.php">Address Book</a>
			<hr />
		</div>
		<h1>Address Book Entries</h1>
		<table>
			<tr><th>Name</th><th>Address</th><th>City</th><th>State</th><th>Zip</th><th>Phone</th></tr>
			<? foreach ($addressBook as $key => $address) : ?>
				<tr>
					<? if ($address != '') : ?>
						<? foreach ($address as $item) : ?>
							<td><?= htmlspecialchars(strip_tags($item)) ?></td>
						<? endforeach; ?>
						<td><?= " | <a href='/address_book.php?remove=$key'>Delete Address</a>"; ?></td>
					<? endif; ?>
				</tr>
			<? endforeach; ?>
		</table>
		<form method = "POST" enctype="multipart/form-data" action = "">
			<h3>Add a new address:</h3>
			<p><?= $book->errorMessage; ?></p>
			<p>
		        <label for="name">Name: </label>
		        <input id="name" name="name" type="text" autofocus = "autofocus" placeholder="Name">
		    </p>
		    <p>
		        <label for="address">Address: </label>
		        <input id="address" name="address" type="text" placeholder="Address">
		    </p>
		    <p>
		        <label for="city">City: </label>
		        <input id="city" name="city" type="text" placeholder="City">
		    </p>
		    <p>
		        <label for="state">State: </label>
		        <input id="state" name="state" type="text" placeholder="State">
		    </p>
		    <p>
		        <label for="zip">Zip: </label>
		        <input id="zip" name="zip" type="text" placeholder="Zip">
		    </p>
		    <p>
		        <label for="phone">Phone: </label>
		        <input id="phone" name="phone" type="text" placeholder="Phone">
		    </p>
		    <p>
		        <label for="uploaded_file">What file would you like to upload?</label>
		        <input id="uploaded_file" name="uploaded_file" type="file">
		    </p>
		    <p>
			    <label for="overwrite">
				    <input type="checkbox" id="overwrite" name="overwrite" value="yes"> Check if you would like to save over old address Book
				</label>
			</p>
		    <p>
		        <button type="submit">Add</button>
		    </p>
		</form>
	</body>
</html>