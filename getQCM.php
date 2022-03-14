	<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = mysqli_connect($servername, $username, $password, $dbname);

	$sql = 'SELECT * FROM `quest` WHERE id="5"';
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($result)){
		$row['choices'] = explode(",",$row['choices']);
		$row['answers'] = explode(",",$row['answers']);
		echo 'myQuestions.push('.json_encode($row).');';
	}
		
	$result->close();
	
	?>