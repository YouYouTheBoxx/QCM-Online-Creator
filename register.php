<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["username"])){
			$user = $_POST["username"];
			$fname = $_POST["fname"];
			$lname = $_POST["lname"];
			$pass = $_POST["password"];
			$birth = $_POST["date"];
			$birth = date("Y-m-d",strtotime($birth));
			$class = $_POST["class"];
			$sqluser = 'INSERT INTO `users` (`username`,`first_name`,`last_name`,`password`,`birthday`,`auth_id`) VALUES ("'.$user.'","'.$fname.'","'.$lname.'","'.$pass.'","'.$birth.'","'.$class.'")';
			if(!mysqli_query($conn, $sqluser))
			{
				echo '<div>Error when trying to add user !</div>';
			}
			else 
			{
				setcookie("mcvirt_uname", $_POST["username"], time()+600);
				echo '<div class="testconn">You will be redirected to your virtual class in <span id="counter">5</span> seconds</div>
					<script type="text/javascript">
					function countdown() {
						var i = document.getElementById("counter");
						if (parseInt(i.innerHTML)<=0) {
							location.href = "mcvirt.php";
						}
					if (parseInt(i.innerHTML)!=0) {
						i.innerHTML = parseInt(i.innerHTML)-1;
					}
					}
					setInterval(function(){ countdown(); },1000);
				</script>';
				header('refresh: 5; url=http://localhost/INEWTEK/mcvirt.php');
			}
		}
	}
	
	$conn->close();
?>