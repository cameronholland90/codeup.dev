<?php

	require_once('classes/read-database.php');
	require_once('classes/too-small-exception.php');

	$todo = new TodoDatafile('todolist', 'codeup_dev_db');

	$page = 0;

	if(isset($_GET['page']) && is_numeric($_GET['page'])) {
		$page = $_GET['page'];
		$todo->readDatabase($page);
	}

	if (isset($_POST['complete']) && is_numeric($_POST['complete'])) {
		$remove = $_POST['complete'];
		$todo->completeItem($remove);
		$todo->setPageCount();
		if ($page > $todo->pageCount) {
			$page--;
		}
		header("Location: todo-list-db-version.php?page=$page");
		exit(0);
	}

	if (!empty($_POST['todoitem'])) {
		$todo->entry = $_POST;
		try {
			$todo->entry = $_POST;
			$todo->addItem();
		} catch(TooSmallException $ex) {
			$todo->errorMessage = 'Please enter a todo';
		} catch(TooBigException $e) {
			$todo->errorMessage = 'Each todo item can only be 240 characters long';
		}
	} 

	$todo->setPageCount();
	$todo->readDatabase($page);
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
		<? foreach ($todo->getQuerySet() as $key => $item) : ?>
			<tr>
				<td><?= htmlspecialchars(strip_tags($item[1])) ?></td><td><form method='POST' action=""><button class='btn-xs btn-danger' name='complete' type='submit' value='<?= $key ?>'>&#10004;</button></form></td>
			</tr>
		<? endforeach; ?>
		</table>
		<div class='row'>
			<div class='col-sm-6 prev-page'>
				<?php if ($page > 0): ?>
					<a class='btn btn-danger' href="todo-list-db-version.php?page=<?= ($page-1) ?>">Previous</a>
				<?php endif ?>
			</div>
			<div class='col-sm-6 next-page'>
				<?php if ($page < $todo->pageCount): ?>
					<a class='btn btn-success' href="todo-list-db-version.php?page=<?= ($page+1) ?>">Next</a>
				<?php endif ?>
			</div>
		</div>
		<form method="POST" action="">
			<h3>Add a new todo item:</h3>
			<p><?= $todo->errorMessage; ?></p>
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