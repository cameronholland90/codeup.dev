<?php

session_start();


require_once('classes/read-database.php');
$colOptions = array('date_established', 'name', 'area_in_acres', 'location');

if (isset($_GET['organize']) && in_array($_GET['organize'], $colOptions)) {
	if ($_SESSION['sort'] === $_GET['organize']) {
		$_SESSION['sort'] = $_GET['organize'] . " DESC";
	} else {
		$_SESSION['sort'] = $_GET['organize'];
	}
}

if (isset($_SESSION['sort'])) {
	$_SESSION['tableData'] = new Datafile('national_parks', 'codeup_mysqli_test_db', $_SESSION['sort']);
} else {
	$_SESSION['tableData'] = new Datafile('national_parks', 'codeup_mysqli_test_db');
}

if (!empty($_POST)) {
	$_SESSION['tableData']->entry = $_POST;
	$_SESSION['tableData']->addItem();
	$_SESSION['tableData']->readDatabase();
}


$parks = $_SESSION['tableData']->getQuerySet();

?>
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
	<link rel="shortcut icon" href="img/Arches v2-6.jpg" />
	<title>mysqli-national-parks.php</title>
</head>
<body>
	<div class='container main-container'>
		<div class="page-header">
			<h1>List of National Parks <small>From Wikipedia</small></h1>
		</div>
		<table class='table table-bordered table-striped'>
			<thead>
				<tr>
					<?= "<th class='col-md-1'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=name'>NAME</a></th>

					<th class='col-md-1'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=location'>LOCATION</a></th><th class='col-md-2'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=date_established'>DATE ESTABLISHED</a></th><th class='col-md-1'><a href='/mywebsite_stuff/mysqli-national-parks.php?organize=area_in_acres'>AREA</a></th><th class='col-md-7'>DESCRIPTION</th>"; ?>
				</tr>
			</thead>
			<?php foreach ($parks as $park) { ?>
				<?= "<tr>"; ?>
					<?= "<td>{$park['name']}</td><td>{$park['location']}</td><td>{$park['date_established']}</td><td> ". number_format($park['area_in_acres'], 2, '.', ',') . " acres</td><td>{$park['description']}</td>"; ?>
				<?= "</tr>"; ?>
			<?php } ?>
		</table>
		<form class="form-horizontal" method = "POST" action = "">
			<h3>Add a new National Park:</h3>
			<p><?= $_SESSION['tableData']->errorMessage; ?></p>
			<div class='form-group'>
		        <label class="col-sm-2 control-label" for="name">* Name: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="name" name="name" type="text" placeholder="Name">
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="location">* Location: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="location" name="location" type="text" placeholder="Location">
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="date_established">* Date Established: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="date_established" name="date_established" type="text" placeholder="Date Established(YYYY-MM-DD)">
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="area_in_acres">* Area in Acres: </label>
		        <div class="col-sm-10">
		        	<input class="form-control" id="area_in_acres" name="area_in_acres" type="text" placeholder="Area in Acres(no ',')">
		    	</div>
		    </div>
		    <div class='form-group'>
		        <label class="col-sm-2 control-label" for="description">* Description: </label>
		        <div class="col-sm-10">
		        	<textarea class="form-control" id="description" name="description" type="text" placeholder="Description"></textarea>
		    	</div>
		    </div>
		    <div class='form-group'>
		    	<label class="col-sm-2 control-label">* = required </label>
		    	<div class="col-sm-10">
		        	<button class="btn btn-default" type="submit">Add</button>
		    	</div>
		    </div>
		</form>
	</div>
</body>
</html>