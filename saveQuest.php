<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST["questions"])){
			$array = $_POST["questions"];
			$id = file_get_contents("lastid.txt");
			$id = intval($id) + 1;
			$qname = $_POST["qname"];
			$auth_id = $_POST["auth_id"];
			$openDate = $_POST["openDate"];
			$openTime = $_POST["openTime"];
			$openDate = date("Y-m-d",strtotime($openDate));
			$opendt = "".$openDate." ".$openTime."";
			//echo ''.$opendt.'<br>';
			$closeDate = $_POST["closeDate"];
			$closeTime = $_POST["closeTime"];
			$locked = $_POST["lock"];
			$closeDate = date("Y-m-d",strtotime($closeDate));
			$closedt = "".$closeDate." ".$closeTime."";
			$mxScore = 0;
			foreach($array as $value){
				$mxScore = $mxScore + (floatval($value['pointsp']) * count($value['correctAnswer']));
			}
			//echo $closedt;
			$sqlqcm = 'INSERT INTO `qcm`(`name`,`auth_id`,`openDate`,`closeDate`,`isLocked`,`maxScore`,`id`) VALUES ("'.$qname.'","'.$auth_id.'","'.$opendt.'","'.$closedt.'","'.$locked.'","'.$mxScore.'","'.$id.'")';
			if(!$conn->query($sqlqcm))
			{
				echo '<div>Error: '.$conn->error.'</div>';
			};
			foreach($array as $value){
				if(count($value['answers']) > 1)
				{
					$answers = implode(",", $value['answers']);
				}else
				{
					$answers = $value['answers'][0];
				}
				$questname = $value['question'];
				if(count($value['correctAnswer']) > 1)
				{
					$correctanswers = implode(",",$value['correctAnswer']);
				}else
				{
					$correctanswers = $value['correctAnswer'][0];
				}
				$questype = $value['type'];
				$questid = $value['id'];
				$quid = "".$questid.",".$id."";
				$pp = $value['pointsp'];
				$pm = $value['pointsm'];
				$sql = 'INSERT INTO `quest`(`question`, `answers`, `correctAnswer`,`quest_id`,`id`,`type`,`pointsp`,`pointsm`,`quest_id-id`) VALUES ("'.$questname.'","'.$answers.'","'.$correctanswers.'","'.$questid.'","'.$id.'","'.$questype.'","'.$pp.'","'.$pm.'","'.$quid.'")';
				if(!$conn->query($sql))
				{
					echo '<div>Error: '.$conn->error.'</div>';
				}else
				{
					file_put_contents("lastid.txt",$id);
					setcookie("mcvirt_uname", $_COOKIE["mcvirt_uname"], time()+600);
					echo '<div class="testconn">Successfully added your new QCM<br>You will be redirected in <span id="responseint">5</span> seconds</div>';
				};
			}
		}
	}
	
	$conn->close();
?>