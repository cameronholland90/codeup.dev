<?php

echo "<p>GET:</p>";
var_dump($_GET);

echo "<p>PUSH:</p>";
var_dump($_POST);

?>

<html>
<head>
	<title>Todo List App</title>
</head>
<body>
	<h3>TODO List:</h3>
	<ul>
		<li>Take out the trash</li>
		<li>Walk the dog</li>
		<li>BUY MORE BACON!</li>
	</ul>
	<form method="POST" action="">
		<h3>Add a new todo item:</h3>
		<p>
	        <label for="todoitem">Task Todo</label>
	        <input id="todoitem" name="todoitem" type="text" placeholder="What do you need todo?">
	    </p>
	</form>
</body>
</html>