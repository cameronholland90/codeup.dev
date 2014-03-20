<?php

	require_once('classes/read-database.php');
	require_once('classes/too-small-exception.php');

	session_start();

	if (!isset($_SESSION['todo'])) {
		$_SESSION['todo'] = new TodoDatafile('todolist', 'codeup_dev_db');
	}

	if (!isset($_SESSION['todoOrComplete'])) {
		$_SESSION['todoOrComplete'] = 'Todo';
	}

	$page = 0;

	if(isset($_GET['page']) && is_numeric($_GET['page'])) {
		$page = $_GET['page'];
		$_SESSION['todo']->readDatabase($page, $_SESSION['todoOrComplete']);
	}

	if (isset($_GET['todoOrComplete']) && ($_GET['todoOrComplete'] === 'Completed' || $_GET['todoOrComplete'] === 'Todo')) {
		$page = 0;
		$_SESSION['todo']->readDatabase($page, $_GET['todoOrComplete']);
		if ($_SESSION['todoOrComplete'] === 'Completed') {
			$_SESSION['todoOrComplete'] = 'Todo';
		} else {
			$_SESSION['todoOrComplete'] = 'Completed';
		}
		header("Location: todo-list-db-version.php?page=$page");
		exit(0);
	}

	if (isset($_POST['complete']) && is_numeric($_POST['complete'])) {
		$remove = $_POST['complete'];
		$_SESSION['todo']->completeItem($remove);
		$_SESSION['todo']->setPageCount($_SESSION['todoOrComplete']);
		if ($page > $_SESSION['todo']->pageCount) {
			$page--;
		}
		header("Location: todo-list-db-version.php?page=$page");
		exit(0);
	}

	if (!empty($_POST['todoitem'])) {
		$_SESSION['todo']->entry = $_POST['todoitem'];
		try {
			$_SESSION['todo']->entry = $_POST;
			$_SESSION['todo']->addItem();
		} catch(TooSmallException $ex) {
			$_SESSION['todo']->errorMessage = 'Please enter a todo';
		} catch(TooBigException $e) {
			$_SESSION['todo']->errorMessage = 'Each todo item can only be 240 characters long';
		}
	} elseif (!empty($_POST['itemCount'])) {
		$_SESSION['todo']->setItems($_POST['itemCount']);
	}

	$_SESSION['todo']->readDatabase($page, $_SESSION['todoOrComplete']);
	$_SESSION['todo']->setPageCount($_SESSION['todoOrComplete']);
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
			<h1><?= $_SESSION['todoOrComplete'] ?> List</h1>
		</div>
		<table class="table table-striped">
		<? foreach ($_SESSION['todo']->getQuerySet() as $key => $item) : ?>
			<tr>
				<td class='col-sm-10'><?= htmlspecialchars(strip_tags($item[1])) ?></td>
				<? if($_SESSION['todoOrComplete'] === 'Todo') : ?>
					<td><form method='POST' action=""><button class='btn-xs btn-danger col-sm-2' name='complete' type='submit' value='<?= $key ?>'>&#10004;</button></form></td>
				<? endif; ?>
			</tr>
		<? endforeach; ?>
		</table>
		<div class='row'>
			<div class='col-sm-5 prev-page'>
				<?php if ($page > 0): ?>
					<a class='btn btn-danger' href="todo-list-db-version.php?page=<?= ($page-1) ?>">Previous</a>
				<?php endif; ?>
			</div>
			<div class='col-sm-2'>
				<form method='POST' action=''>
			        <div>
			        	<input class="form-control items-per" id="itemCount" name="itemCount" type="text" value="<?= $_SESSION['todo']->getItems() ?>" required/>
			    	</div>
					<label class="control-label" for="address">Items per page: </label>
				</form>
			</div>
			<div class='col-sm-5 next-page'>
				<?php if ($page < $_SESSION['todo']->pageCount): ?>
					<a class='btn btn-success' href="todo-list-db-version.php?page=<?= ($page+1) ?>">Next</a>
				<?php endif; ?>
			</div>
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
		<a class='btn btn-success' href="todo-list-db-version.php?todoOrComplete=<?= $_SESSION['todoOrComplete'] ?>">Complete/Todo</a>
	</div>
</body>
</html>