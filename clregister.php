<?php
	$servername = "localhost";
	$username = "mhndetqroot";
	$password = "W1m2t3l4n";
	$dbname = "mhndetqroot";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["clname"])){
			if($_POST["state"] == "new"){
				$clname = $_POST["clname"];
				$mxusers = intval($_POST["mxusers"]);
				$sqluser = 'INSERT INTO `classes` (`name`,`max_users`,`teacher`) VALUES ("'.$clname.'","'.$mxusers.'","'.$_COOKIE["mcvirt_uname"].'")';
				if(!mysqli_query($conn, $sqluser))
				{
					echo '<div>Error when trying to add a new class !</div>';
				}
				else 
				{
					setcookie("mcvirt_uname", $_POST["username"], time()+600);
					echo '<div class="testconn">Successfully added a new class<br>You will be redirected in <span id="counter">5</span> seconds</div>';
					header('refresh: 5; url=http://localhost/INEWTEK/clmanager.php');
				}
			}else if($_POST["state"] == "edit")
			{
				$clname = $_POST["clname"];
				$mxusers = intval($_POST["mxusers"]);
				$auth_id = intval($_POST["auth_id"]);
				$sqluser = 'UPDATE `classes` SET `name` = "'.$clname.'",`max_users` = "'.$mxusers.'",`teacher` = "'.$_COOKIE["mcvirt_uname"].'" WHERE auth_id = "'.$auth_id.'"';
				if(!mysqli_query($conn, $sqluser))
				{
					echo '<div>Error when trying to edit the class !</div>';
				}
				else 
				{
					setcookie("mcvirt_uname", $_POST["username"], time()+600);
					echo '<div class="testconn">Successfully modified your class<br>You will be redirected in <span id="responseint">1</span> seconds</div>';
				}
			}
		}
	}
	
	$conn->close();
?>