<?php

echo "<p>GET:</p>";
var_dump($_GET);

echo "<p>PUSH:</p>";
var_dump($_POST);

?>

<html>
<link rel="stylesheet" type="text/css" href="stylesheet3.css" />
<head>
	<title>My First Form</title>
</head>
<body>
	<form method="POST" action="">
	    <p>
	        <label for="username">Username</label>
	        <input id="username" name="username" type="text" placeholder="Enter your username">
	    </p>
	    <p>
	        <label for="password">Password</label>
	        <input id="password" name="password" type="password" placeholder="Enter your password">
	    </p>
	    <p>
	        <button type="submit">LOGIN</button>
	    </p>
	</form>
	<form method="POST" action="">
		<p>
			<label for="to">To</label>
	        <input id="to" name="to" type="text" placeholder="Send To">
		</p>
		<p>
			<label for="from">From</label>
	        <input id="from" name="from" type="text" placeholder="Your Email">
		</p>
		<p>
			<label for="subject">Subject</label>
	        <input id="subject" name="subject" type="text" placeholder="Subject">
		</p>
		<p>
			<label for="email_body">Message</label>
	        <textarea id="email_body" name="email_body" type="text" placeholder="Compose Email Here" rows="30" cols="50"></textarea>
		</p>
		<label for="savetosent">
		    <input type="checkbox" id="savetosent" name="savetosent" value="yes" checked>Save Copy to Sent Folder
		</label>
		<p>
	        <button type="submit">Send Email</button>
	    </p>
	</form>
	<h2>Mulitple Choice Test</h2>
	<h3>Stop. Who would cross the Bridge of Death must answer me these questions three, ere the other side he see.</h3>
	<form method="POST" action="">
		<p>What is you name?</p>
		<label for="q1a">
			<input type="radio" id="q1a" name="q1" value="Lancelot">Lancelot
		</label>
		<label for="q1b">
			<input type="radio" id="q1b" name="q1" value="Sir Robin">Sir Robin
		</label>
		<label for="q1c">
			<input type="radio" id="q1c" name="q1" value="Sir Galahad">Sir Galahad
		</label>
		<label for="q1d">
			<input type="radio" id="q1d" name="q1" value="King Arthur">King Arthur
		</label>
		
		<p>What is your quest?</p>
		<label for="q2a">
			<input type="radio" id="q2a" name="q2" value="Track the migration of African Swallows">Track the migration of African Swallows
		</label>
		<label for="q2b">
			<input type="radio" id="q2b" name="q2" value="Learn how to be a web dev">Learn how to be a web dev
		</label>
		<label for="q2c">
			<input type="radio" id="q2c" name="q2" value="I seek the Holy Grail">I seek the Holy Grail
		</label>
		<label for="q2d">
			<input type="radio" id="q2d" name="q2" value="To cross the bridge">To cross the bridge
		</label>

		<p>What is your favorite color?</p>
		<label for="q3a">
			<input type="radio" id="q3a" name="q3" value="Blue">Blue
		</label>
		<label for="q3b">
			<input type="radio" id="q3b" name="q3" value="Green">Green
		</label>
		<label for="q3c">
			<input type="radio" id="q3c" name="q3" value="Yellow">Yellow
		</label>
		<label for="q3d">
			<input type="radio" id="q3d" name="q3" value="Red">Red
		</label>

		<p>What is the capital of Assyria?</p>
		<label for="q4a">
			<input type="radio" id="q4a" name="q4" value="I don't know that">I don't know that
		</label>
		<label for="q4b">
			<input type="radio" id="q4b" name="q4" value="Assur">Assur
		</label>
		<label for="q4c">
			<input type="radio" id="q4c" name="q4" value="Calah">Calah
		</label>
		<label for="q4d">
			<input type="radio" id="q4d" name="q4" value="Nineveh">Nineveh
		</label>

		<p>What is the air-speed velocity of an unladen swallow?</p>
		<label for="q5a">
			<input type="radio" id="q5a" name="q5" value="I don't know that">I don't know that
		</label>
		<label for="q5b">
			<input type="radio" id="q5b" name="q5" value="What do you mean? An African or European Swallow?">What do you mean? An African or European Swallow?
		</label>
		<label for="q5c">
			<input type="radio" id="q5c" name="q5" value="11 meters per second">11 meters per second
		</label>
		<label for="q5d">
			<input type="radio" id="q5d" name="q5" value="24 miles an hour">24 miles an hour
		</label>

		<br>
		
	    <label for="os">What operating systems have you used? </label>
		<select id="os" name="os[]" multiple>
		    <option value="linus">Linux</option>
		    <option value="osx">OS X</option>
		    <option value="windows">Windows</option>
		</select>

		<p>
	        <button type="submit">Submit Answers</button>
	    </p>

	</form>

	<form method="POST" action="">
	<label for="seenmontypython">Have you seen Monty Python? </label>
		<select id="seenmontypython" name="seenmontypython">
		    <option value="1">Yes</option>
		    <option value="0">No</option>
		</select>

		<p>
	        <button type="submit">Submit</button>
	    </p>
	</form>
</body>
</html>