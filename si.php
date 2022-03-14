<?php
	echo '<html>
	<head>
	<link rel="stylesheet" type="text/css" href="styles/default.css">
	<title>Sign-in to your virtual class</title>
	</head>
	<body>
	<form method="POST" action="testconn.php">
	<span style="position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);">
	<label style="user-select: none;">Username<br><input type="text" name="username" class="form-input"></input><br></label>
	<label style="user-select: none;">Password<br><input type="password" name="password" class="form-input"></input><br></label>
	<button name="si" class="customarg">Connect</button><br>
	</span>
	</form>
	</body>
	</html>'
?>