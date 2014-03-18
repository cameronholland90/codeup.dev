<?php
	
	require_once('classes/read-database.php');
	require_once('classes/too-small-exception.php');

	session_start();

	if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != 'http://codeup.dev/mywebsite_stuff/todo-list-db-version.php') {
		session_destroy();
		session_start();
	}

	if (!isset($_SESSION['page'])) {
		$_SESSION['page'] = 0;
	}

	if (!empty($_POST)) {
		$book->entry = $_POST;
		try {
			$addressBook = $book->addItem($addressBook);
		} catch (TooSmallException $e){
			$book->errorMessage = "Please enter required information";
		} catch (TooBigException $ex) {
			$book->errorMessage = "Each field can only be 125 characters long";
		}
	}

	if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
		$remove = $_GET['remove'];	
		unset($addressBook[$remove]);
		$book->write_address_book($addressBook);
		$_GET = array();
		header("Location: address_book.php");
		exit(0);
	}

	if (!isset($_SESSION['address_book'])) {
		$_SESSION['address_book'] = new TodoDatafile('todolist', 'codeup_dev_db');
	} else {
		$_SESSION['address_book']->readDatabase($_SESSION['page']);
		$_SESSION['address_book']->setPageCount();
	}

?>
<!DOCTYPE html>
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
	<title>Address Book App</title>
</head>
<body>
	<!-- navbar -->
	<!-- <div id="navbar" class="navbar navbar-inverse navbar-fixed-top">
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
								<li><a href="todo-list.php">To Do List</a></li>
								<li><a href="address_book.php">Address Book</a></li>
								<li><a href="yahtzee.php">Yahtzee</a></li>
								<li><a href="blackjack.php">Blackjack</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div> -->
	<!-- end navbar -->
	<div class='container main-container'>
		<div class="page-header">
			<h1>Address Book</h1>
		</div>
		<table class = "table table-hover table-bordered">
			<tr><th>Name</th><th>Address</th><th>City</th><th>State</th><th>Zip</th><th>Phone</th><th></th></tr>
			<? foreach ($addressBook as $key => $address) : ?>
				<tr>
					<? if ($address != '') : ?>
						<? foreach ($address as $item) : ?>
							<td><?= htmlspecialchars(strip_tags($item)) ?></td>
						<? endforeach; ?>
						<td><?= "<a href='/address_book.php?remove=$key'><span class='glyphicon glyphicon-trash'></span></a>"; ?></td>
					<? endif; ?>
				</tr>
			<? endforeach; ?>
		</table>
		<form class="form-horizontal" method = "POST" action = "">
			<h3>Add a new address:</h3>
			<p><?= $book->errorMessage; ?></p>
			<div class='form-group'>
		        <label class="col-sm-2 control-label" for="name">* Name: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="name" name="name" type="text" autofocus = "autofocus" placeholder="Name" required>
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="address">* Address: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="address" name="address" type="text" placeholder="Address" required>
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="city">* City: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="city" name="city" type="text" placeholder="City" required>
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="state">* State: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="state" name="state" type="text" placeholder="State" required>
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="zip">* Zip: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="zip" name="zip" type="text" placeholder="Zip" required>
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="phone">Phone: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="phone" name="phone" type="text" placeholder="Phone">
		    	</div>
		    </div>
		    <div class='form-group'>
		    	<label class="col-sm-2 control-label" for="phone">* = required </label>
		    	<div class="col-sm-10">
		        	<button class="btn btn-default" type="submit">Add</button>
		    	</div>
		    </div>
		</form>
	</div>
</body>
</html>