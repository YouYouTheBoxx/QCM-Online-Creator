<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/7d4746b2f6.js" crossorigin="anonymous"></script>
<br>
<center>
<?php
include('menu.php');
?>
</center>
</head>
<body>
<div class="quiz-container">
  <div id="quiz"></div>
</div>
  <button class="customarg" id="previous" style="position: absolute;display: none; top:50%;">Question precedente</button>
  <button class="customarg" id="next" style="position: absolute;display: none; top:50%;" >Question suivante</button>
  <button class="customarg" id="submit" style="position: absolute;display: none; top:50%;transform: translateX(-50%);">Valider le QCM</button>
<div id="results" style="position: absolute;top: 47%;left: 50%;transform: translateX(-50%);"></div>
<script type="text/javascript">
(function(){
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
					<input type="radio" class="form-input-checkbox" name="question${currentQuestion.id}" id="i${currentQuestion.id}" value="${letter}"></input>
					<span class="lock" style="display: inline-block" id="is${currentQuestion.id}"></span>
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="lquestion${currentQuestion.id}" for="">
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
					<input type="checkbox" class="form-input-checkbox" name="question${currentQuestion.id}" id="i${currentQuestion.id}" value="${letter}"></input>
					<span class="lock" style="display: inline-block" id="is${currentQuestion.id}"></span>
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="lquestion${currentQuestion.id}" for="">
					  <!--<input type="checkbox" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>
					</label>
					</span><br><br>`
				  );
			  } else if (currentQuestion.type == "precise"){
				  answers.push(
					`<span>
					<input type="radio" style="display:none;" name="question${currentQuestion.id}" id="i${currentQuestion.id}" value="${letter}" checked="true">
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="lquestion${currentQuestion.id}">
					  <!--<input type="text" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>
					</span>`
				  );
			  }
		}
			// add this question and its answers to the output
			output.push(
			  `<div class="slide" name="${currentQuestion.id}">
				<div name="tquestion${currentQuestion.id}" class="question tquestion${currentQuestion.id}" style="display: block;"><label id="tquestion${currentQuestion.id}">${currentQuestion.question}</label></div>
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
	let numCorrect = 0;

	// for each question...
	myQuestions.forEach( (currentQuestion, questionNumber) => {

		if(currentQuestion.type != "precise"){
			const answerContainer = answerContainers[questionNumber];
			const selector = `input[name=question${currentQuestion.id}]:checked`;
			const allanwsers = answerContainer.querySelectorAll(selector);
			var numcorr = 0;
			var isright = false;

			allanwsers.forEach(function(userItem) {
				for(letter in currentQuestion.correctAnswer){
					if(userItem.value === currentQuestion.correctAnswer[letter]){
						numcorr = numcorr + currentQuestion.pointsp;
						document.getElementById(`lquestion${currentQuestion.id}${userItem.value}`).style['color'] = 'lightgreen';
						isright = true;
					}
					numCorrect = numCorrect + currentQuestion.pointsp;
				}
				
				
				if (isright == false){
					document.getElementById(`lquestion${currentQuestion.id}${userItem.value}`).style['color'] = 'red';
					numcorr = numcorr - currentQuestion.pointsm;
					/*for(letter in currentQuestion.correctAnswer){
						document.getElementById(questionNumber + currentQuestion.correctAnswer[letter]).style.color = 'lightgreen';
					}*/
				}
				isright = false;
			});
		}else{
						const answerContainer = answerContainers[questionNumber];
			const selector = `input[name=question${currentQuestion.id}]`;
			const allanwsers = answerContainer.querySelectorAll(selector);
			var numcorr = 0;
			var isright = false;

			allanwsers.forEach(function(userItem) {
				for(letter in currentQuestion.correctAnswer){
					if(userItem.value === currentQuestion.correctAnswer[letter]){
						numcorr = numcorr + 1;
						document.getElementById(`lquestion${currentQuestion.id}${userItem.value}`).style['color'] = 'lightgreen';
						isright = true;
					}
				}
				
				if (isright == false){
					document.getElementById(`lquestion${currentQuestion.id}${userItem.value}`).style['color'] = 'red';
					/*for(letter in currentQuestion.correctAnswer){
						document.getElementById(questionNumber + currentQuestion.correctAnswer[letter]).style.color = 'lightgreen';
					}*/
				}
				isright = false;
			});
			if (numcorr == Object.keys(currentQuestion.correctAnswer).length){
				numCorrect++;
			}
		}
	});

	// show number of correct answers out of total
	var percent = Math.round((numCorrect/myQuestions.length)*10);
	resultsContainer.innerHTML = `${percent} / 10`;
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
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = mysqli_connect($servername, $username, $password, $dbname);

	$sql = 'SELECT * FROM `quest` WHERE id='.intval($_POST["quest_id"]).'';
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($result)){
		$row['answers'] = explode(",",$row['answers']);
		$row['correctAnswer'] = explode(",",$row['correctAnswer']);
		echo 'myQuestions.push('.json_encode($row).');';
	}
		
	$result->close();
	
	?>
  var myAnswers = [];
  
  <?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = mysqli_connect($servername, $username, $password, $dbname);

	$sql = 'SELECT * FROM `qcm_attempt_feedback` WHERE qcm_id='.intval($_POST["quest_id"]).'';
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($result)){
		$row['answers'] = explode(",",$row['answers']);
		$row['correctAnswer'] = explode(",",$row['correctAnswer']);
		echo 'myQuestions.push('.json_encode($row).');';
	}
		
	$result->close();
	
	?>
	
  // Kick things off
  buildQuiz();

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
})();
</script>
</body>
</html>