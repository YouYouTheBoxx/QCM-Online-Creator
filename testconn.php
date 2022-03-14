<html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
</head>
<body>
<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if(isset($_POST["username"]) and isset($_POST["password"])){
		$sql = 'SELECT * FROM users WHERE username = "' .$_POST["username"]. '" and password = "' .$_POST["password"]. '"';
		if(mysqli_num_rows(mysqli_query($conn, $sql)) > 0)
		{
			setcookie("mcvirt_uname", $_POST["username"], time()+600);
			header('Location: http://localhost/INEWTEK/mcvirt.php');
		}
		else
		{
			echo '<div class="testconn" style="position: absolute; left: 50%;top: 45%;transform: translate(-50%,-50%);z-index: 3;">Invalid Username or Password</div>';
			echo '<div class="testconn" style="background-color: gray;position: absolute; left: 50%;top: 50%;transform: translate(-50%,-50%);z-index: 2;">You will be redirected to your virtual class in <span id="counter">5</span> seconds</div>
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
			header('refresh: 3; url=http://localhost/INEWTEK/mcvirt.php');
		}
	}
	
	$conn->close();
?>
</body>
</html>