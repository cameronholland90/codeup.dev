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

	if (!empty($_POST['todoitem'])) {
		$_SESSION['todo']->entry = $_POST;
		try {
			$_SESSION['todo']->entry = $_POST;
			$_SESSION['todo']->addItem();
			$_SESSION['todo']->readDatabase();
		} catch(TooSmallException $ex) {
			$_SESSION['todo']->errorMessage = 'Please enter a todo';
		} catch(TooBigException $e) {
			$_SESSION['todo']->errorMessage = 'Each todo item can only be 240 characters long';
		}
	} 

	if(isset($_POST['next']) && ($_POST['next'] === 'yes')) {
		$_SESSION['page'] += 1;
		$_SESSION['todo']->readDatabase($_SESSION['page']);
	} elseif(isset($_POST['previous']) && ($_POST['previous'] === 'yes')) {
		$_SESSION['page'] -= 1;
		$_SESSION['todo']->readDatabase($_SESSION['page']);
	}

	if (!isset($_SESSION['todo'])) {
		$_SESSION['todo'] = new TodoDatafile('todolist', 'codeup_dev_db');
	} else {
		$_SESSION['todo']->readDatabase($_SESSION['page']);
		$_SESSION['todo']->setPageCount();
	}

	if (isset($_GET['complete']) && is_numeric($_GET['complete'])) {
		$remove = $_GET['complete'];
		$_SESSION['todo']->completeItem($remove);
		header("Location: todo-list-db-version.php");
		exit(0);
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
		<? foreach ($_SESSION['todo']->getQuerySet() as $key => $item) : ?>
			<tr>
				<td><?= htmlspecialchars(strip_tags($item[1])) . "</td><td><a href='todo-list-db-version.php?complete=$key'>&#10004;</a>"; ?></td>
			</tr>
		<? endforeach; ?>
		</table>
		<div class='row'>
			<form method='POST' action=''>
				<div class='col-sm-6 prev-page'>
					<?php if ($_SESSION['page'] > 0): ?>
						<button type='submit' class='btn btn-danger' id='previous' name ='previous' value='yes'>Previous</button>
					<?php endif ?>
				</div>
				<div class='col-sm-6 next-page'>
					<?php if ($_SESSION['page'] < $_SESSION['todo']->pageCount): ?>
						<button type='submit' class='btn btn-success' id='next' name='next' value='yes'>Next</button>
					<?php endif ?>
				</div>
			</form>
		</div>
		<form method="POST" action="">
			<h3>Add a new todo item:</h3>
			<p><?= $_SESSION['todo']->errorMessage; ?></p>
			<p>
		        <div class="row">
				  <div class="col-lg-6">
				    <div class="input-group">
				      <span class="input-group-btn">
				        <button class="btn btn-default" type="submit">Add</button>
				      </span>
				      <input id="todoitem" name="todoitem" autofocus="autofocus" placeholder="What do you need to do?" type="text" class="form-control">
				    </div><!-- /input-group -->
				  </div><!-- /.col-lg-6 -->
				</div>
		    </p>
		</form>
	</div>
</body>
</html>