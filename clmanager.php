<html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if(isset($_COOKIE["mcvirt_uname"])){
		$sql = 'SELECT * FROM `classes` WHERE teacher = "'.$_COOKIE["mcvirt_uname"].'"';
		$result = $conn->query($sql);
		$row = $result->fetch_all(MYSQLI_ASSOC);
		if(mysqli_num_rows($result) > 0)
		{
			echo '<span class="title" style="float:left;margin-left: 50%;transform: translateX(-50%);">List of current classes registered : </span><br><br>';
		}
		foreach($row as $value){
			echo '<span class="form-input" style="user-select: none;" id="'.$value['auth_id'].'">
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$value['name'].'</label>
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$value['max_users'].'</label>
			<button class="lambda fa fa-edit" style="font-size:1.25em;width: 1.5em;height: 1.5em;background-color: red;user-select: none;" value="'.$value['auth_id'].'" onclick="showclrm(this.value)"></button>
			</span><br><br>';
		}
		$result->free();
		$conn->close();
	}

?>
<button class="customarg" style="background-color: red;display: inline-block;" id="addbt">Add a new Class</button>
<form action="mcvirt.php">
<button class="customarg" style="background-color: red;display: inline-block;" id="submit">Return to your virtual class</button>
</form>
<span id="classmod" style="display: none;position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);">
</span>
<span class="" style="display: none;" id="response">
</span>
<script type="text/javascript">
	var output = [];
	var input = [];
	output.push(`
	<label style="user-select: none;">Class Name<input type="text" name="clname" class="form-input" id="cname" required></input><br></label>
	<label style="user-select: none;">Max Users<input type="text" name="mxusers" class="form-input" id="cusers" required></input><br></label>
	<button name="sn" class="customarg" id="modclinf" onclick="editclass()">Modify Class Info</button><br>`);
	input.push(`
	<label style="user-select: none;">Class Name<input type="text" name="clname" class="form-input" id="cname" required></input><br></label>
	<label style="user-select: none;">Max Users<input type="text" name="mxusers" class="form-input" id="cusers" required></input><br></label>
	<button name="sn" class="customarg" id="addcl" onclick="addclass()">Add Class</button><br>`);
	
	function showcladd()
	{
		classmod.innerHTML = input.join('');
		addbt.style.display = "none";
		classmod.style.display = "inline";
	}
	
	function showclrm(auth)
	{
		classmod.innerHTML = output.join('');
		classmod.style.display = "inline";
		auth_id = auth;
	}
	
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
	
	function addclass()
	{
		var name = document.getElementById('cname').value;
		var mxusers = document.getElementById('cusers').value;
		data = {clname: name, mxusers: mxusers, state: "new", username: username};
		var repel = document.getElementById("response");
		repel.style.display = "inline";
		var ajaxurl = "clregister.php";
		$.post(ajaxurl, data, function (response) {
			repel.innerHTML = response;
		});
	}
	
	
	function editclass()
	{
		var name = document.getElementById('cname').value;
		var mxusers = document.getElementById('cusers').value;
		data = {clname: name, mxusers: mxusers, auth_id: auth_id, state: "edit", username: username};
		var repel = document.getElementById("response");
		repel.style.display = "inline";
		var ajaxurl = "clregister.php";
		$.post(ajaxurl, data, function (response) {
			repel.innerHTML = response;
		});
	}
	
	const addbt = document.getElementById('addbt');
	addbt.addEventListener('click', showcladd);
	
	var auth_id = null;
	
	<?php
	
	echo 'var username = "'.$_COOKIE["mcvirt_uname"].'"';
	
	?>
	
	const classmod = document.getElementById('classmod');
	const addclbt = document.getElementById('addcl');
	if(addclbt != null){
		addclbt.addEventListener('click', addclass);
	}
	const modclinfbt = document.getElementById('modclinf');
	if(modclinfbt != null){
		modclinfbt.addEventListener('click', editclass);
	}
	
</script>
</body>
</html>