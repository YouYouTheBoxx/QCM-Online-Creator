<html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>QCM Users View</title>
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
<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if(isset($_COOKIE["mcvirt_uname"])){
		echo '<span class="title" style="float:left;margin-left: 50%;transform: translateX(-50%);">'..'</span><br><br>';
		$sql = 'Select * from `users` WHERE Teacher= 0 AND auth_id = 0 OR auth_id="'.$_POST['auth_id'].'"';
		$result = $conn->query($sql);
		$row = $result->fetch_all(MYSQLI_ASSOC);
		$mxscore = $_POST['maxScore'];
		echo '<span class="form-input" style="user-select: none;">
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">Username</label>
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">Number of attempts</label>
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">Best Attempt</label>
			</span><br><br>';
		
		foreach($row as $value){
			$sqls = 'Select * from `qcm_results` WHERE user_id = "'.$value['user_id'].'" AND qcm_id="'.$_POST['quest_id'].'" ORDER BY `result` DESC';
			$results = $conn->query($sqls);
			$rows = $results->fetch_all(MYSQLI_ASSOC);
			
			$btscore = $rows[0]['result'];
			$bestScore = ($results->num_rows > 0) ? ''.$btscore.'/'.$mxscore.'' : 0;
			$attempts = ($results->num_rows > 0) ? $results->num_rows : 'No Attempt yet !';
			echo '<span class="form-input" style="user-select: none;">
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$value['username'].'</label>
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$attempts.'</label>
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$bestScore.'</label>
			</span><br><br>';
		}
	}

?>
<form action="http://formatec33.com/mcvirt.php">
<button class="customarg" style="background-color: red;display: inline-block;" id="submit">Return to your virtual class</button>
</form>
<span id="classmod" style="display: none;position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);">
</span>
<span class="" style="display: none;" id="response">
</span>
<script type="text/javascript">
	
	function countdown() {
		var i = document.getElementById("responseint");
		if (parseInt(i.innerHTML)<=0) {
			location.href = "clmanager.php";
		}
		if (parseInt(i.innerHTML)!=0) {
			i.innerHTML = parseInt(i.innerHTML)-1;
		}
	}
	setInterval(function(){ countdown(); },1000);
	
</script>
</body>
</html>