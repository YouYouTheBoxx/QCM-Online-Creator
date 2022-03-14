<html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Courses Manager</title>
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
ul {
  list-style-type: none;
}
</style>
</head>
<body>
<ul>
<?php
	$servername = "mhndetqroot.mysql.db";
	$username = "mhndetqroot";
	$password = "W1m2t3l4n";
	$dbname = "mhndetqroot";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	if(isset($_COOKIE["mcvirt_uname"])){
		$sql = 'SELECT * FROM `courses` WHERE teacher = "'.$_COOKIE["mcvirt_uname"].'"';
		$result = $conn->query($sql);
		$row = $result->fetch_all(MYSQLI_ASSOC);
		if(mysqli_num_rows($result) > 0)
		{
			echo '<span class="title" style="float:left;margin-left: 50%;transform: translateX(-50%);">Liste de mes cours : </span><br><br>';
		}
		echo '<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">Cours</label>';
		foreach($row as $value){
			$sqlc = 'SELECT * FROM `classes` WHERE auth_id = "'.$value['auth_id'].'"';
			$resultc = $conn->query($sqlc);
			$rowc = $resultc->fetch_all(MYSQLI_ASSOC);
			foreach($rowc as $valuec){
				$class_id = $valuec['name'];
			}
			echo '<li><span class="form-input" style="user-select: none;" id="'.$value['id'].'">
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$value['name'].'</label>
			<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$class_id.'</label>
			<button class="lambda fa fa-edit" style="font-size:1.25em;width: 1.5em;height: 1.5em;background-color: red;user-select: none;" value="'.$value['id'].'" onclick="showclrm(this.value, `course`)"></button>
			</span><br><br>';
			$sqls = 'SELECT * FROM `chapters` WHERE course_id = "'.$value['id'].'"';
			$results = $conn->query($sqls);
			$rows = $results->fetch_all(MYSQLI_ASSOC);
			echo '<ul><label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">Chapitres</label>';
			foreach($rows as $values){
				echo '<li>
				<span class="form-input" style="user-select: none;" id="'.$values['id'].'">
				<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$values['name'].'</label>
				<button class="lambda fa fa-edit" style="font-size:1.25em;width: 1.5em;height: 1.5em;background-color: red;user-select: none;" value="'.$values['id'].'" onclick="showclrm(this.value, `chapter`)"></button>
				</span><br><br>';
				$sqlsa = 'SELECT * FROM `categories` WHERE chapter_id = "'.$values['id'].'"';
				$resultsa = $conn->query($sqlsa);
				$rowsa = $resultsa->fetch_all(MYSQLI_ASSOC);
				echo '<ul><label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">Catégories</label>';
				foreach($rowsa as $valuesa){
					echo '<li style="margin-left: 2em;">
					<span class="form-input" style="user-select: none;" id="'.$valuesa['id'].'">
					<label class="lambda" style="display: inline-block;user-select: none;cursor: initial;">'.$valuesa['name'].'</label>
					<button class="lambda fa fa-edit" style="font-size:1.25em;width: 1.5em;height: 1.5em;background-color: red;user-select: none;" value="'.$valuesa['id'].'" onclick="showclrm(this.value, `category`)"></button>
					</span><br><br>';
				}
				echo '</li>';
				echo '</ul>';
				$resultsa->free();
				echo '</li>';
			}
			echo '</ul>';
			$results->free();
		}
		echo'</li>';
		$result->free();
	}

?>
<button class="customarg" style="background-color: red;display: inline-block;" id="addbt">Ajouter un nouveau cours</button>
</ul>
<form action="mcvirt.php">
<button class="customarg" style="display: inline-block;" id="submit">Retourner à sa classe virtuelle</button>
</form>
<span id="classmod" style="display: none;position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);">
</span>
<span class="" style="display: none;" id="response">
</span>
<script type="text/javascript">
	var output = [];
	var input = [];
	var outputchapt = [];
	var outputcat = [];
	output.push(`
	<label style="user-select: none;">Nom du cours<input type="text" name="clname" class="form-input" id="cname" required></input><br></label>
	<label style="user-select: none;">Classe<select name="class" id="class" class="form-input-select" required>
    <option disabled="disabled" selected="selected" value="" style="display: none;">Please select a class</option>
	<?php
	
	$sqlcc = 'SELECT * FROM `classes`';
	$resultcc = $conn->query($sqlcc);
	$rowcc = $resultcc->fetch_all(MYSQLI_ASSOC);
	$val_ghost = "";

	if(mysqli_num_rows($resultcc) > 0)
	{
		foreach($rowcc as $value)
		{
			/*if($val_ghost != $value['teacher'])
			{
				echo '<optgroup class="form-input-select" style="background-color: #999999;" label="'.$value['teacher'].'"></optgroup>';
			}*/
			echo '<option value="'.$value['auth_id'].'" class="form-input-select">'.$value['name'].'</option>';
			$val_ghost = $value['teacher'];
		}
	}
	?>
	</select></label><button name="sn" class="customarg" id="modclinf" onclick="editclass()">Modifier les informations d'un cours</button><br>`);
	
	outputchapt.push(`
	<label style="user-select: none;">Nom du chapitre<input type="text" name="clname" class="form-input" id="cname" required></input><br></label>
	<button name="sn" class="customarg" id="modclinf" onclick="editclass()">Modifier le nom du chapitre</button><br>`);
	
	outputcat.push(`
	<label style="user-select: none;">Nom de la catégorie<input type="text" name="clname" class="form-input" id="cname" required></input><br></label>
	<button name="sn" class="customarg" id="modclinf" onclick="editclass()">Modifier le nom de la catégorie</button><br>`);
	
	input.push(`
	<label style="user-select: none;">Nom du cours<input type="text" name="clname" class="form-input" id="cname" required></input><br></label>
	<label style="user-select: none;">Classe<select name="class" id="class" class="form-input-select" required>
    <option disabled="disabled" selected="selected" value="" style="display: none;">Please select a class</option>
	<?php
	
	$sqlcc = 'SELECT * FROM `classes`';
	$resultcc = $conn->query($sqlcc);
	$rowcc = $resultcc->fetch_all(MYSQLI_ASSOC);
	$val_ghost = "";

	if(mysqli_num_rows($resultcc) > 0)
	{
		foreach($rowcc as $value)
		{
			/*if($val_ghost != $value['teacher'])
			{
				echo '<optgroup class="form-input-select" style="background-color: #999999;" label="'.$value['teacher'].'"></optgroup>';
			}*/
			echo '<option value="'.$value['auth_id'].'" class="form-input-select">'.$value['name'].'</option>';
			$val_ghost = $value['teacher'];
		}
	}
	?>
	</select></label></div>
	<button name="sn" class="customarg" id="addcl" onclick="addclass()">Ajouter un cours</button><br>`);
	
	function showcladd()
	{
		classmod.innerHTML = input.join('');
		addbt.style.display = "none";
		classmod.style.display = "inline";
	}
	
	function showclrm(auth, type)
	{
		if(type === "course"){
			classmod.innerHTML = output.join('');
			classmod.style.display = "inline";
			auth_id = auth;
		}else if(type === "chapter"){
			classmod.innerHTML = outputchapt.join('');
			classmod.style.display = "inline";
		}else if(type === "category"){
			classmod.innerHTML = outputcat.join('');
			classmod.style.display = "inline";
		}
	}
	
	function addclass()
	{
		var name = document.getElementById('cname').value;
		var mxusers = document.getElementById('cusers').value;
		data = {clname: name, mxusers: mxusers, state: "new", username: username};
		var repel = document.getElementById("response");
		repel.style.display = "inline";
		var ajaxurl = "curegister.php";
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
		var ajaxurl = "curegister.php";
		$.post(ajaxurl, data, function (response) {
			repel.innerHTML = response;
		});
	}
	
	const addbt = document.getElementById('addbt');
	addbt.addEventListener('click', showcladd);
	
	var auth_id = null;
	
	<?php
	
	echo 'var username = "'.$_COOKIE["mcvirt_uname"].'"';
	$conn->close();
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