<?php

	$servername = "mhndetqroot.mysql.db";
	$username = "mhndetqroot";
	$password = "W1m2t3l4n";
	$dbname = "mhndetqroot";

	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = 'SELECT * FROM `classes`';
	$result = $conn->query($sql);
	$row = $result->fetch_all(MYSQLI_ASSOC);

	echo '<html>
	<head>
	<link rel="stylesheet" type="text/css" href="styles/default.css">
	<title>Sign-in to your virtual class</title>
	<style>
	label {
	  display: block;
	  width: 15em;
	  background-color: #1e847f;
	  font-family: "Work Sans", sans-serif;
	  font-size: 22px;
	  color: #fff;
	  border: 0px;
	  border-radius: 3px;
	  padding: 5px;
	  margin-bottom: 5px;
	  cursor: default;
	}
	</style>
	</head>
	<body>
	<form method="POST" action="register.php">
	<span style="position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);">
	<div>
        <label style="user-select: none;">Username<input type="text" name="username" class="form-input" required></input><br></label>
        <label style="user-select: none;">Password<input type="password" name="password" class="form-input" required></input><br></label>
        <label style="user-select: none;">Class <select name="class" class="form-input-select" required>
        <option disabled="disabled" selected="selected" value="" style="display: none;">Please select a class</option>
	';
	if(mysqli_num_rows($result) > 0)
	{
		foreach($row as $value)
		{
			if($val_ghost != $value['teacher'])
			{
				echo '<optgroup class="form-input-select" style="background-color: #999999;" label="'.$value['teacher'].'"></optgroup>';
			}
			echo '<option value="'.$value['auth_id'].'" class="form-input-select">'.$value['name'].'</option>';
			$val_ghost = $value['teacher'];
		}
	}
	echo'</select></label></div>';
	echo'
	<div>
	<label style="user-select: none;">First Name<input type="text" name="fname" class="form-input" required></input><br></label>
	<label style="user-select: none;">Last Name<input type="text" name="lname" class="form-input" required></input><br></label>
	<label style="user-select: none;">Birthday<input type="date" name="date" class="form-input" required></input><br></label>
        </div><br>
	<button name="sn" class="customarg">Inscription à ma classe virtuelle</button><br>
	</span>
	</form>
	</body>
	</html>';
	$result->free();
	$conn->close();
?>	