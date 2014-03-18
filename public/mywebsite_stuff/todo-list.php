<?php
	require_once('classes/filestore.php');
	require_once('classes/too-small-exception.php');

	$todo = new Filestore("data/todo_list.txt");
	$archive = new Filestore("data/archived-list.txt");

	$errorMessage = '';
	
	$todolist = $todo->read();
	if (!empty($_POST)) {
		$todo->entry = $_POST;
		try {
			$todolist = $todo->addItem($todolist);
			$todo->write($todolist);
		} catch(TooSmallException $ex) {
			$errorMessage = 'Please enter a todo';
		} catch(TooBigException $e) {
			$errorMessage = 'Each todo item can only be 240 characters long';
		}
	} 

	if (isset($_GET['complete']) && is_numeric($_GET['complete'])) {
		$remove = $_GET['complete'];
		$archiveItems = $archive->read();
		$archiveItems[] = $todolist[$remove]; 
		unset($todolist[$remove]);
		$archive->write($archiveItems);
		$_GET = array();
		$todo->write($todolist);
		header("Location: todo-list.php");
		exit(0);
	}

	if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] == 'text/plain') {
	    // Set the destination directory for uploads
	    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
	    // Grab the filename from the uploaded file by using basename
	    $tempfilename = basename($_FILES['uploaded_file']['name']);
	    // Create the saved filename using the file's original name and our upload directory
	    $saved_filename = $upload_dir . $tempfilename;
	    // Move the file from the temp location to our uploads directory
	    move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $saved_filename);
	    $newfile = new Filestore($saved_filename);
	    $appendList = $newfile->read();
	    if ($_POST['overwrite'] == "yes") {
	    	$todolist = $appendList;
	    	$todo->write($todolist);
	    } else {
	    	$todolist = array_merge($todolist, $appendList);
	    	$todo->write($todolist);
	    }
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
	<title>Todo List App</title>
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
			<h1>Todo List</h1>
		</div>
		<table class="table table-striped">
		<? foreach ($todolist as $key => $item) : ?>
			<tr>
				<td><?= htmlspecialchars(strip_tags($item)) . "</td><td><a href='todo-list.php?complete=$key'>&#10004;</a>"; ?></td>
			</tr>
		<? endforeach; ?>
		</table>
		
		<form method="POST" action="">
			<h3>Add a new todo item:</h3>
			<p><?= $errorMessage; ?></p>
			<p>
		        <div class="row">
				  <div class="col-lg-6">
				    <div class="input-group">
				      <span class="input-group-btn">
				        <button class="btn btn-default" type="button">Add</button>
				      </span>
				      <input id="todoitem" name="todoitem" autofocus="autofocus" placeholder="What do you need to do?" type="text" class="form-control">
				    </div><!-- /input-group -->
				  </div><!-- /.col-lg-6 -->
				</div>
		    </p>
		</form>
		<form method="POST" enctype="multipart/form-data" action="">
		    <h3>Upload File</h3>
		    <?php
		    	if (count($_FILES) > 0 && $_FILES['uploaded_file']['error'] == 0 && $_FILES['uploaded_file']['type'] != 'text/plain') {
		    		echo "<p style=\"color: red\";>Please upload plain text files only.</p>";
		    	}
		    ?>
		    <p>
		        <label for="uploaded_file">What file would you like to upload?</label>
		        <input id="uploaded_file" name="uploaded_file" type="file">
		    </p>
		    <p>
			    <label for="overwrite">
				    <input type="checkbox" id="overwrite" name="overwrite" value="yes"> Check if you would like to save over old todo list
				</label>
			</p>
		    <p>
		        <button type="submit">Add</button>
		    </p>
		</form>
	</div>
</body>
</html>