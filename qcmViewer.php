<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/7d4746b2f6.js" crossorigin="anonymous"></script>
<br>
<center>
<?php
include('menu.php');
if(isset($_COOKIE["mcvirt_uname"])){
	setcookie("mcvirt_uname", $_COOKIE["mcvirt_uname"], time()+2700);
}
?>
</center>
</head>
<body>
<div class="quiz-container">
  <div id="quiz"></div>
</div>
  <button class="customarg" id="previous" style="display: none;;">Question precedente</button>
  <button class="customarg" id="next" style="display: none;" >Question suivante</button>
  <button class="customarg" id="submit" style="display: none;;">Valider le QCM</button>
<div id="results" style="position: absolute;top: 55%;left: 50%;transform: translateX(-50%);"></div>
<script type="text/javascript">
  // Functions
  function buildQuiz(){
    // variable to store the HTML output
    const output = [];

    // for each question...
    myQuestions.forEach(
      (currentQuestion, questionNumber) => {

        // variable to store the list of possible answers
        const answers = [];


			// and for each available answer...
			for(letter in currentQuestion.answers){

			  // ...add an HTML radio button
			  if (currentQuestion.type == "unique" || currentQuestion.type == "real"){
				  answers.push(
					`<span>
					<label class="container" style="display: inline-block;">
					<input type="radio" class="form-input-checkbox" name="question${currentQuestion.quest_id}" id="i${currentQuestion.quest_id}" value="${letter}"></input>
					<span class="locke" style="display: inline-block;border-radius: 4em;" id="is${currentQuestion.quest_id}"></span>
					<label name="lquestion${currentQuestion.quest_id}" id="lquestion${currentQuestion.quest_id}${letter}" class="lquestion${currentQuestion.quest_id}" for="">
					  <!--<input type="radio" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>
					</label>
					</span><br><br>`
				  );
			  } else if (currentQuestion.type == "multi"){
				  answers.push(
					`<span>
					<label class="container" style="display: inline-block;">
					<input type="checkbox" class="form-input-checkbox" name="question${currentQuestion.quest_id}" id="i${currentQuestion.quest_id}" value="${letter}"></input>
					<span class="lock" style="display: inline-block;" id="is${currentQuestion.quest_id}"></span>
					<label name="lquestion${currentQuestion.quest_id}" id="lquestion${currentQuestion.quest_id}${letter}" class="lquestion${currentQuestion.quest_id}" for="">
					  <!--<input type="checkbox" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>
					</label>
					</span><br><br>`
				  );
			  } else if (currentQuestion.type == "precise"){
				  answers.push(
					`<span>
					<input type="radio" style="display:none;" name="question${currentQuestion.quest_id}" id="i${currentQuestion.quest_id}" value="${letter}" checked="true">
					<label name="lquestion${currentQuestion.quest_id}" id="lquestion${currentQuestion.quest_id}${letter}" class="lquestion${currentQuestion.quest_id}">
					  <!--<input type="text" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>
					</span>`
				  );
			  }
		}
			// add this question and its answers to the output
			output.push(
			  `<div class="slide" name="${currentQuestion.quest_id}">
				<div name="tquestion${currentQuestion.quest_id}" class="question tquestion${currentQuestion.quest_id}" style="display: block;"><label id="tquestion${currentQuestion.quest_id}">${currentQuestion.question}</label></div>
				<div class="answers"> ${answers.join("")} </div>
			  </div>`
			);
      }
    );

    // finally combine our output list into one string of HTML and put it on the page
    quizContainer.innerHTML = output.join('');
  }

  function showResults(){

	// gather answer containers from our quiz
	const answerContainers = quizContainer.querySelectorAll('.answers');

	// keep track of user's answers
	var numCorrect = 0;
	var numCorr = 0;
	previousButton.style['display'] = "none";
	submitButton.style['display'] = "none";

	// for each question...
	myQuestions.forEach( (currentQuestion, questionNumber) => {
		
		if(currentQuestion.type != "precise"){
			const answerContainer = answerContainers[questionNumber];
			const selector = `input[name=question${currentQuestion.quest_id}]:checked`;
			const allanwsers = answerContainer.querySelectorAll(selector);
			var isright = false;
			
			for(letter in currentQuestion.correctAnswer){
				numCorrect = numCorrect + parseFloat(currentQuestion.pointsp);
			}

			allanwsers.forEach(function(userItem) {
				for(letter in currentQuestion.correctAnswer){
					if(userItem.value === currentQuestion.correctAnswer[letter]){
						numCorr = numCorr + parseFloat(currentQuestion.pointsp);
						//document.getElementById(`lquestion${currentQuestion.quest_id}${userItem.value}`).style['color'] = 'lightgreen';
						isright = true;
					}/*else{
						isright = false;
					}*/
				}
				
				
				if (isright == false){
					//document.getElementById(`lquestion${currentQuestion.quest_id}${userItem.value}`).style['color'] = 'red';
					numCorr = numCorr - parseFloat(currentQuestion.pointsm);
					/*for(letter in currentQuestion.correctAnswer){
						document.getElementById(questionNumber + currentQuestion.correctAnswer[letter]).style.color = 'lightgreen';
					}*/
				}
				isright = false;
			});
		}else{
			const answerContainer = answerContainers[questionNumber];
			const selector = `input[name=question${currentQuestion.quest_id}]`;
			const allanwsers = answerContainer.querySelectorAll(selector);
			var isright = false;
			
			for(letter in currentQuestion.correctAnswer){
				numCorrect = numCorrect + parseFloat(currentQuestion.pointsp);
			}

			allanwsers.forEach(function(userItem) {
				for(letter in currentQuestion.correctAnswer){
					if(userItem.value === currentQuestion.correctAnswer[letter]){
						numCorr = numCorr + parseFloat(currentQuestion.pointsp);
						//document.getElementById(`lquestion${currentQuestion.quest_id}${userItem.value}`).style['color'] = 'lightgreen';
						isright = true;
					}/*else{
						isright = false;
					}*/
				}
				
				if (isright == false){
					//document.getElementById(`lquestion${currentQuestion.quest_id}${userItem.value}`).style['color'] = 'red';
					numCorr = numCorr - parseFloat(currentQuestion.pointsm);
					/*for(letter in currentQuestion.correctAnswer){
						document.getElementById(questionNumber + currentQuestion.correctAnswer[letter]).style.color = 'lightgreen';
					}*/
				}
				isright = false;
			});
		}
	});

	// show number of correct answers out of total
	resultsContainer.innerHTML = `${numCorr} / ${numCorrect}`;
	
	var result = numCorr;
	var qcm_id = myQuestions[0].id;
	
	data = {result: result, qcm_id: qcm_id};
	var ajaxurl = "saveResults.php";
	$.post(ajaxurl, data, function (response) {
		resultsContainer.innerHTML = resultsContainer.innerHTML + response;
	});
  }

  function showSlide(n) {
    slides[currentSlide].classList.remove('active-slide');
    slides[n].classList.add('active-slide');
    currentSlide = n;
    if(currentSlide === 0){
      previousButton.style.display = 'none';
    }
    else{
      previousButton.style.display = 'inline-block';
    }
    if(currentSlide === slides.length-1){
      nextButton.style.display = 'none';
      submitButton.style.display = 'inline-block';
    }
    else{
      nextButton.style.display = 'inline-block';
      submitButton.style.display = 'none';
    }
  }
  
  function showSlideEmpty(n) {
    slides[n].classList.add('active-slide');
    currentSlide = n;
    if(currentSlide === 0){
      previousButton.style.display = 'none';
    }
    else{
      previousButton.style.display = 'inline-block';
    }
    if(currentSlide === slides.length-1){
      nextButton.style.display = 'none';
      submitButton.style.display = 'inline-block';
    }
    else{
      nextButton.style.display = 'inline-block';
      submitButton.style.display = 'none';
    }
  }
  
  function showNextSlide() {
    showSlide(currentSlide + 1);
  }

  function showPreviousSlide() {
    showSlide(currentSlide - 1);
  }
	

  // Variables
  const quizContainer = document.getElementById('quiz');
  const resultsContainer = document.getElementById('results');
  const submitButton = document.getElementById('submit');
  
  var myQuestions = [];

	<?php
	$servername = "mhndetqroot.mysql.db";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = mysqli_connect($servername, $username, $password, $dbname);

	$sql = 'SELECT * FROM `quest` WHERE id='.intval($_POST["quest_id"]).' ORDER BY `quest_id` ASC';
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($result)){
		$row['answers'] = explode(",",$row['answers']);
		$row['correctAnswer'] = explode(",",$row['correctAnswer']);
		echo 'myQuestions.push('.json_encode($row).');';
	}
		
	$result->close();
	
	?>
	
	//console.log(myQuestions);
	
  // Kick things off
  buildQuiz();
  
  function countdown() {
		var i = document.getElementById("responseint");
		if(i != null){
			if (parseInt(i.innerHTML)<= 0) {
				location.href = "mcvirt.php";
			}
			if (parseInt(i.innerHTML)!=0) {
				i.innerHTML = parseInt(i.innerHTML)-1;
			}
		}
	}
	setInterval(function(){ countdown(); },1000);

  // Pagination
  const previousButton = document.getElementById("previous");
  const nextButton = document.getElementById("next");
  var slides = document.querySelectorAll(".slide");
  var idn = 0;
  const qid = 5;
  var qname = "";
  var type = null;
  
  let currentSlide = 0;

  // Show the first slide
  showSlide(currentSlide);

  // Event listeners
  submitButton.addEventListener('click', showResults);
  previousButton.addEventListener("click", showPreviousSlide);
  nextButton.addEventListener("click", showNextSlide);
</script>
</body>
</html>