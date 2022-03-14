<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
		function getCookie(cname) {
		  var name = cname + "=";
		  var ca = document.cookie.split(';');
		  for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
			  c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
			  return c.substring(name.length, c.length);
			}
		  }
		  return "";
		}
		
		function setCookie(cname, cvalue, exdays) {
		  var d = new Date();
		  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		  var expires = "expires="+d.toUTCString();
		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/INEWTEK";
		}
</script>
</head>
<body>
<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if(isset($_COOKIE["mcvirt_uname"])){
		setcookie("mcvirt_uname", $_COOKIE["mcvirt_uname"], time()+600);
		$sql = 'Select * from `users` WHERE username = "'.$_COOKIE["mcvirt_uname"].'" and Teacher = 1';
		if (mysqli_num_rows($conn->query($sql)) == 1)
		{
			echo '<select class="user" onchange="tryChange(this.selectedIndex);">' .$_COOKIE["mcvirt_uname"]. '
			<option disabled="disabled" selected="selected" value="" style="display: none;">' .$_COOKIE["mcvirt_uname"]. '</option>
			<option value="">Class Manager</option>
			<option value="">Courses Manager</option>
			<option value="">edit profile</option>
			<option value="">disconnect</option>
			</select>
			<script type="text/javascript">
			function tryChange(index)
			{
				if (index == 4)
				{
					var user = getCookie("mcvirt_uname");
					//alert("Welcome again " + user);
					setCookie("mcvirt_uname", "", 0);
					window.location.href = "http://localhost/INEWTEK/mcvirt.php";
				}else if (index == 1)
				{
					/*var user = getCookie("mcvirt_uname");
					setCookie("mcvirt_uname", "", 600);*/
					window.location.href = "http://localhost/INEWTEK/clmanager.php";
				}
			}
			</script>
			<form action="qcmEditor.php">
			<button class="lambda fa fa-edit" style="font-size:1.25em;width: auto;height: auto;background-color: red;user-select: none;" value="">Add a new QCM</button>
			</form>';
			
			$sqlu = 'Select * from `classes` WHERE teacher = "'.$_COOKIE["mcvirt_uname"].'"';
			$resultu = $conn->query($sqlu);
			$rowu = $resultu->fetch_all(MYSQLI_ASSOC);
			$rowd = '';
			foreach($rowu as $value){
				$rowd = $rowd + 'auth_id = '.$value['auth_id'].' OR ';
			}
			
			$sqlq = 'Select * from `qcm` WHERE '.$rowd.'auth_id = 0';
			$resultq = $conn->query($sqlq);
			$rowq = $resultq->fetch_all(MYSQLI_ASSOC);
			echo '<div class="flexover">';
			foreach($rowq as $value){
				echo '<form name="quest_id'.$value['id'].'" method="POST" action="qcmUserViewer.php">
					<button name="quest_id" class="qcmClick overd" id="auth_id" value="'.$value['id'].'">
					<input type="text" name="auth_id" value="'.$value['auth_id'].'" style="display: none;"></input>
					<input type="text" name="maxScore" value="'.$value['maxScore'].'" style="display: none;"></input>
					<input type="text" name="name" value="'.$value['name'].'" style="display: none;"></input>
					<marquee direction="left" scrollamount="8" behavior="scroll"><adl style="user-select: none;font-size: 1.5em;" data-text="'.$value['name'].'">'.$value['name'].'</adl></marquee>
					</button>
				</form>';
			}
			echo '</div>';
			}
		else
		{
			echo '<select class="user" onchange="tryChange(this.selectedIndex);">' .$_COOKIE["mcvirt_uname"]. '
			<option disabled="disabled" selected="selected" value="" style="display: none;">' .$_COOKIE["mcvirt_uname"]. '</option>
			<option value="">edit profile</option>
			<option value="">disconnect</option>
			</select>
			<script type="text/javascript">
			function tryChange(index)
			{
				if (index == 2)
				{
					var user = getCookie("mcvirt_uname");
					//alert("Welcome again " + user);
					setCookie("mcvirt_uname", "", 0);
					window.location.href = "http://localhost/INEWTEK/mcvirt.php";
				}else if (index == 1)
				{
					window.location.href = "http://localhost/INEWTEK/edtp.php";
				}
			}
			</script>';
			echo '<div class="flexover">';
			$sqlu = 'Select * from `users` WHERE username = "'.$_COOKIE["mcvirt_uname"].'"';
			$resultu = $conn->query($sqlu);
			$rowu = $resultu->fetch_all(MYSQLI_ASSOC);
			foreach($rowu as $value){
				$auth_id = $value['auth_id'];
				$uid = $value['user_id'];
			}
			$sql = 'Select * from `qcm` WHERE auth_id = "0" OR auth_id= "'.$auth_id.'"';
			$result = $conn->query($sql);
			$row = $result->fetch_all(MYSQLI_ASSOC);
			foreach($row as $value){
				$mxAttempt = $value['maxAttempts'];
				$mxScore = $value['maxScore'];
				$sqls = 'Select * from `qcm_results` WHERE user_id = "'.$uid.'" AND qcm_id="'.$value['id'].'" ORDER BY `result` DESC';
				$results = $conn->query($sqls);
				$rows = $results->fetch_all(MYSQLI_ASSOC);
				if($results->num_rows >= $mxAttempt){
					$btscore = $rows[0]['result'];
					$bestScore = ($results->num_rows > 0) ? ''.$btscore.'/'.$mxscore.'' : 0;
					echo '<button class="qcmClick overd" value="'.$value['id'].'" style="background-color: gray;" disabled="true">
					<marquee direction="left" scrollamount="8" behavior="scroll"><adl style="user-select: none;font-size: 1.5em;" data-text="'.$value['name'].'">'.$value['name'].'</adl></marquee>
					<div class="ctgrid">
					<adl style="user-select: none;font-size: 1.5em;">Best Score : '.$bestScore.'</adl>
					<adl style="user-select: none;font-size: 1.5em;">Remaining Attempts : 0</adl>
					</div>
					</button>';
				}else{
					$AttemptNext = $mxAttempt - $results->num_rows;
					if($results->num_rows > 0){
						$btscore = $rows[0]['result'];
					}
					$bestScore = ($results->num_rows > 0) ? $btscore : 0;
					echo '<form name="quest_id'.$value['id'].'" method="POST" action="qcmViewer.php">
					<button name="quest_id" class="qcmClick overd" id="quest_id" value="'.$value['id'].'">
					<marquee direction="left" scrollamount="8" behavior="scroll"><adl style="user-select: none;font-size: 1.5em;" data-text="'.$value['name'].'">'.$value['name'].'</adl></marquee>
					<div class="ctgrid">
					<adl style="font-size: 1.5em;user-select: none;">Best Score : '.$bestScore.'</adl>
					<adl style="font-size: 1.5em;user-select: none;">Remaining Attempts : '.$AttemptNext.'</adl>
					</div>
					</button>
					</form>';
				}
			}
			echo '</div>';
		}
		$sql = '';
		$currentDate = date('Y-m-d H:i:s');
		$currentDate = date('Y-m-d H:i:s', strtotime($currentDate));
		
	}else{
		echo '<span style="position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);">
		<a href="si.php"><button class="customarg" id="signin">Sign-In</button></a>
<a href="sn.php"><button class="customarg" id="signup">Sign-Up</button></a>
</span>';
	}

?>
</body>
</html>