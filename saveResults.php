<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = new mysqli($servername, $username, $password, $dbname);
	$sqlu = 'Select * from `users` WHERE username = "'.$_COOKIE["mcvirt_uname"].'"';
	$resultu = $conn->query($sqlu);
	$rowu = $resultu->fetch_all(MYSQLI_ASSOC);
	foreach($rowu as $value){
		$uid = $value['user_id'];
	}
	$sqlqcm = 'INSERT INTO `qcm_results`(`user_id`, `result`, `qcm_id`) VALUES ('.$uid.','.$_POST["result"].','.$_POST["qcm_id"].')';
	
	if(!$conn->query($sqlqcm))
	{
		echo '<div>Error: '.$conn->error.'</div>';
	}else{
		setcookie("mcvirt_uname", $_COOKIE["mcvirt_uname"], time()+600);
		echo '<div class="testconn">Successfully modified your class<br>You will be redirected in <span id="responseint">5</span> seconds</div>';
	}
	
?>