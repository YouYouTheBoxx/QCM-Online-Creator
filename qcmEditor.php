<!DOCTYPE html>
<head>
<link rel="stylesheet" type="text/css" href="styles/default.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://kit.fontawesome.com/7d4746b2f6.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="questmenu" style="display: none;margin: 12em;">
<span><button class="editbutton" id="edit" value="enedit"></button><span class="tooltiptext">edit question</span></span>
<span><button class="addbutton" id="add" value="addnew"></button><span class="tooltiptext">add a question</span></span>
<span><button class="changebutton" id="change" value="change"></button><span class="tooltiptext">change question type</span></span>
<span><button class="delbutton" id="del" value="del"></button><span class="tooltiptext">remove a question</span></span>
</div>
<div class="quiz-container">
  <div id="quiz"></div>
</div>
<button class="customarg usebutton" id="previous" style="display: none;">Previous Question</button>
<button class="customarg usebutton" id="next" style="display: none;" >Next Question</button>
<button class="customarg usebutton" id="submit" style="display: none;">Save QCM</button> 
<div id="results" style="display: none;position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);"></div>
<div class="pop-up fas fa-exclamation-triangle" style="font-size: 1.5em;user-select: none;height: auto;padding: 0.5em;" id="pop-up"><i style="font-size: 1em;user-select: none;"></i></div>
<div class="overlay2" id="newqname">
<label class="title" style="user-select: none; cursor: initial;">Enter QCM Title :</label><br>
<span class="title txt" id="lqname" contenteditable="true">Default Title</span><br>
<acl>
<label class="title" style="user-select: none; cursor: initial;">Select Class :</label><br>
<select class="form-input-select" id="auth_id">
<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "qcmdatabase";

	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = 'SELECT * FROM `classes`';
	$result = $conn->query($sql);
	$row = $result->fetch_all(MYSQLI_ASSOC);


	echo'<option selected="selected" value="0">All</option>
	';
	if(mysqli_num_rows($result) > 0)
	{
		foreach($row as $value)
		{
			if($val_ghost != $value['teacher'])
			{
				echo '<optgroup class="form-input-select" style="background-color: #999999;" label="'.$value['teacher'].'"></optgroup>';
			}
			echo '<option value="'.$value['auth_id'].'" class="form-input-select">'.$value['name'].'</option>';
			$val_ghost = $value['teacher'];
		}
	}
?>
</select>
</acl>
<div>
<acl>
<span style="width: 16em;">
<label class="title" style="display: inline;user-select: none; cursor: initial;">Open Date :</label><br>
<input type="date" class="form-input" style="width: 10em;" id="openDate" value="2000-01-01"></input>
<input type="time" class="form-input" style="width: 5em;" id="openTime" value="00:00"></input>
</span>
</acl>
<br>
<acl>
<span style="width: 16em;">
<label class="title" style="display: inline;user-select: none; cursor: initial;">Close Date :</label><br>
<input type="date" class="form-input" style="width: 10em;" id="closeDate" value="2037-01-01"></input>
<input type="time" class="form-input" style="width: 5em;" id="closeTime" value="00:00"></input>
</span>
</acl>
</div>
<center><button class="customarg" id="saveqname" style="display: none;" >Save QCM name</button></center>
</div>
<div class="overlay" id="newquest">
<label class="cent"  style="user-select: none; cursor: initial;">Choisser le type de la nouvelle question :</label>
<br>
<button class="nquest" id="fakereal">Vrai ou Faux</button>
<button class="nquest" id="multichoices">Choix Multiples</button>
<br>
<button class="nquest" id="uniquechoice">Choix Unique</button>
<button class="nquest" id="preciseanwser">Reponse Precise</button>
</div>
<script type="text/javascript">
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
					`<input type="radio" name="question${currentQuestion.id}" id="i${currentQuestion.id}" class="hidd form-input-checkbox" value="${letter}">
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="lquestion${currentQuestion.id}" for="" contenteditable="false">
					  <!--<input type="radio" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>`
				  );
			  } else if (currentQuestion.type == "multi"){
				  answers.push(
					`<input type="checkbox" name="question${currentQuestion.id}" id="i${currentQuestion.id}" class="hidd form-input-checkbox" value="${letter}">
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="lquestion${currentQuestion.id}" for="" contenteditable="false">
					  <!--<input type="checkbox" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>`
				  );
			  } else if (currentQuestion.type == "precise"){
				  answers.push(
					`<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="lquestion${currentQuestion.id}" contenteditable="false">
					  <!--<input type="text" name="question${currentQuestion.id}" id="${questionNumber}${letter}" value="${letter}">-->
					  ${currentQuestion.answers[letter]}
					</label>`
				  );
			  }
		}
		
			// add this question and its answers to the output
			output.push(
			  `<div class="slide">
				<div name="lquestion${currentQuestion.id}" class="question lquestion${currentQuestion.id}" contenteditable="false"> ${currentQuestion.question} </div>
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

		if (currentQuestion.type == "unique" || currentQuestion.type == "real"){
		  // find selected answer
		  const answerContainer = answerContainers[questionNumber];
		  const selector = `input[name=question${currentQuestion.id}]:checked`;
		  const userAnswer = (answerContainer.querySelector(selector) || {}).value;

		  // if answer is correct
		  if(userAnswer === currentQuestion.correctAnswer){
			// add to the number of correct answers
			numCorrect++;

			// color the answers green
			document.getElementById(answerContainer.querySelector(selector).name + answerContainer.querySelector(selector).value).style.color = 'lightgreen';
		  }
		  // if answer is wrong or blank
		  else{
			// color the answers red
			document.getElementById(answerContainer.querySelector(selector).name + answerContainer.querySelector(selector).value).style.color = 'red';
			/*document.getElementById(questionNumber + currentQuestion.correctAnswer).style.color = 'lightgreen';*/
		  }
		}else if (currentQuestion.type == "multi"){
			// find selected answer
			const answerContainer = answerContainers[questionNumber];
			const selector = `input[name=question${currentQuestion.id}]:checked`;
			const allanwsers = answerContainer.querySelectorAll(selector);
			var numcorr = 0;
			var isright = false;

			allanwsers.forEach(function(userItem) {
				// if answer is correct
				for(letter in currentQuestion.correctAnswer){
					if(userItem.value === currentQuestion.correctAnswer[letter]){
						// add to the number of correct answers
						numcorr = numcorr + 1;
						// color the answers green
						document.getElementById(userItem.name + userItem.value).style.color = 'lightgreen';
						isright = true;
					}
				}
				
				if (isright == false){
					// color the answers red
					document.getElementById(userItem.name + userItem.value).style.color = 'red';
					/*for(letter in currentQuestion.correctAnswer){
						document.getElementById(questionNumber + currentQuestion.correctAnswer[letter]).style.color = 'lightgreen';
					}*/
				}
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
	changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
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
	changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
  }
  
  function showNextSlide() {
	const selector = `.lquestion${currentSlide}`;
	const alllabels = document.querySelectorAll(selector);
	const selectorIn = `#i${currentSlide}`;
	const allinputs = document.querySelectorAll(selectorIn);
	const allspan = `#is${myQuestions[currentSlide].id}`;
	const allspans = document.querySelectorAll(allspan);
	alllabels.forEach(function(userItem) {
		userItem.setAttribute("contenteditable", "false");
		editButton.value = "enedit";
		editButton.classList.remove('savebutton');
		editButton.classList.add('editbutton');
		//editButton.innerHTML = '<span class="tooltiptext">edit question</span>';
	});
	allinputs.forEach(function(userItem) {
		userItem.style.display = "none";
	});
	allspans.forEach(function(userItem) {
		userItem.style.display = "none";
	});
    showSlide(currentSlide + 1);
	changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
  }

  function showPreviousSlide() {
	const selector = `.lquestion${currentSlide}`;
	const alllabels = document.querySelectorAll(selector);
	const selectorIn = `#i${currentSlide}`;
	const allinputs = document.querySelectorAll(selectorIn);
	const allspan = `#is${myQuestions[currentSlide].id}`;
	const allspans = document.querySelectorAll(allspan);
	alllabels.forEach(function(userItem) {
		userItem.setAttribute("contenteditable", "false");
		editButton.value = "enedit";
		editButton.classList.remove('savebutton');
		editButton.classList.add('editbutton');
		//editButton.innerHTML = '<span class="tooltiptext">edit question</span>';
	});
	allinputs.forEach(function(userItem) {
		userItem.style.display = "none";
	});
	allspans.forEach(function(userItem) {
		userItem.style.display = "none";
	});
    showSlide(currentSlide - 1);
	changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
  }
  function resetPopup(){
	pop = document.getElementById("pop-up")
	pop.style['opacity'] = 0;
	pop.style['visibility'] = "hidden";
  }
  
  function addSlide(){
	const output = [];
	
    // for each question...
    myQuestions.forEach(
      (currentQuestion, questionNumber) => {
		  
		const answers = [];

		if (questionNumber == slides.length){
			// variable to store the list of possible answers

			// and for each available answer...
			for(letter in currentQuestion.answers){

			  // ...add an HTML radio button
			  if (currentQuestion.type == "unique" || currentQuestion.type == "real"){
				  answers.push(
					`<span>
					<label class="container" style="display: flex;cursor: default;">
					<input type="radio" class="form-input-checkbox" name="question${currentQuestion.id}" id="i${currentQuestion.id}" value="${letter}" onchange="resetPopup()"></input>
					<span class="locke" style="font-size: 1.25em;margin-right: 1em;border-radius: 4em;" id="is${currentQuestion.id}"></span>
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="overlay2 lquestion${currentQuestion.id}" style="margin: 0;text-align: center;min-width: 12em;line-height: 1.5em;min-height: 1.5em;opacity: 1;border: none;" value="${letter}" for="" contenteditable="true" onfocus="resetPopup()">
					  ${currentQuestion.answers[letter]}
					</label>
					</label>
					</span><br><br>`
				  );
			  } else if (currentQuestion.type == "multi"){
				  answers.push(
					`<span>
					<label class="container" style="display: flex;cursor: default;">
					<input type="checkbox" class="form-input-checkbox" name="question${currentQuestion.id}" id="i${currentQuestion.id}" value="${letter}" onchange="resetPopup()"></input>
					<span class="lock" style="font-size: 1.25em;margin-right: 1em;" id="is${currentQuestion.id}"></span>
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="overlay2 lquestion${currentQuestion.id}" style="margin: 0;text-align: center;min-width: 12em;line-height: 1.5em;min-height: 1.5em;opacity: 1;border: none;" value="${letter}" for="" contenteditable="true" onfocus="resetPopup()">
					  ${currentQuestion.answers[letter]}
					</label>
					</label>
					</span><br><br>`
				  );
			  } else if (currentQuestion.type == "precise"){
				  answers.push(
					`<span>
					<label class="container" style="display: flex;cursor: default;">
					<input type="radio" class="form-input-checkbox" style="display:none;" name="question${currentQuestion.id}" id="i${currentQuestion.id}" value="${letter}" checked="true">
					<label name="lquestion${currentQuestion.id}" id="lquestion${currentQuestion.id}${letter}" class="overlay2 lquestion${currentQuestion.id}" style="margin: 0;text-align: center;min-width: 12em;line-height: 1.5em;min-height: 1.5em;opacity: 1;border: none;" value="${letter}" for="" contenteditable="true" onfocus="resetPopup()">
					  ${currentQuestion.answers[letter]}
					</label>
					</label>
					</span></br></br>`
				  );
			  }
		}
		
			answers.push(`<br><div name="pt${currentQuestion.id}" style="display: none;"><nus>Good answer points (+)</nus><div type="text" class="form-input" style="width: 1.75em; display:inline-block; margin: 1.25em;" id="pp${currentQuestion.id}" contenteditable="true" onfocus="resetPopup()">1</div><nus>Bad answer points (-)</nus><div type="text" class="form-input" id="pm${currentQuestion.id}" style="width: 1.75em; display:inline-block; margin: 1.25em;" contenteditable="true" onfocus="resetPopup()">0</div></div>`);
			// add this question and its answers to the output
			output.push(
			  `<div class="slide" name="${currentQuestion.id}">
				<div name="tquestion${currentQuestion.id}" class="question tquestion${currentQuestion.id}" contenteditable="true" onfocus="resetPopup()"><label id="tquestion${currentQuestion.id}" class="overlay2" style="min-width: 0.125em;opacity: 1;">${currentQuestion.question}</label></div>
				<div class="answers"> ${answers.join("")} </div>
			  </div>`
			);
		}
      }
    );
	
	// finally combine our output list into one string of HTML and put it on the page
    quizContainer.innerHTML = quizContainer.innerHTML + output.join('');
	
	slides = document.querySelectorAll(".slide");
	if (slides.length-1 == 0){
		showSlide(0);
		changeButton.style['visibility'] = "visible";
		changeButton.style['opacity'] = 1;
	}else{
		showNextSlide();
	}
	editQuestion();
  }
  
  function editQuestion() {
	const selector = `.lquestion${myQuestions[currentSlide].id}`;
	const alllabels = document.querySelectorAll(selector);
	const selectorIn = `#i${myQuestions[currentSlide].id}`;
	const allinputs = document.querySelectorAll(selectorIn);
	const allspan = `#is${myQuestions[currentSlide].id}`;
	const allspans = document.querySelectorAll(allspan);
	const allinputsCheck = document.querySelectorAll(`#i${myQuestions[currentSlide].id}:checked`);
	const titl = document.querySelector(`label[id='tquestion${myQuestions[currentSlide].id}']`);
	const pts = document.querySelector(`div[name='pt${myQuestions[currentSlide].id}']`);
	const ptp = document.querySelector(`div[id='pp${myQuestions[currentSlide].id}']`);
	const ptm = document.querySelector(`div[id='pm${myQuestions[currentSlide].id}']`);
	var editres = [];
	var answersedit = [];
	if (editButton.value == "enedit"){
		alllabels.forEach(function(userItem) {
			userItem.setAttribute("contenteditable", "true");
		});
		titl.setAttribute("contenteditable", "true");
		pts.style.display = "block";
		editButton.value = "disedit";
		submitButton.style['display'] = "none";
		editButton.classList.remove('editbutton');
		editButton.classList.add('savebutton');
		/*editButton.innerHTML = '<span class="tooltiptext">save question</span>';*/
		allinputs.forEach(function(userItem) {
			userItem.style.display = "inline-block";
		});
		allspans.forEach(function(userItem) {
			userItem.style.display = "inline-block";
		});
	}else {
		if(allinputsCheck.length === 0){
			pop = document.getElementById("pop-up")
			pop.style['opacity'] = 1;
			pop.style['visibility'] = "visible";
			pop.style['top'] = "55.75%";
			pop.innerHTML = `<a style="font-size: 1em;user-select: none;">You have to select at least one valid answer !</a><i class="fas fa-exclamation-triangle" style=""></i>`;
		}else{
			alllabels.forEach(function(userItem) {
				userItem.setAttribute("contenteditable", "false");
				var text = userItem.textContent;
				text = text.replace(/(\r\n|\n|\r)/gm,"");
				text = text.replace(/(^\s+|\s+$)/g, "")
				answersedit.push(text);
			});
			titl.setAttribute("contenteditable", "false");
			pts.style.display = "none";
			myQuestions[currentSlide].pointsp = parseFloat(ptp.textContent);
			myQuestions[currentSlide].pointsm = parseFloat(ptm.textContent);
			editButton.value = "enedit";
			editButton.classList.remove('savebutton');
			editButton.classList.add('editbutton');
			submitButton.style['display'] = "inline-block";
			var title = titl.textContent;
			/*editButton.innerHTML = '<span class="tooltiptext">edit question</span>';*/
			allinputsCheck.forEach(function(userItem) {
				editres.push(userItem.value);
			});
			myQuestions[currentSlide].correctAnswer = editres;
			myQuestions[currentSlide].answers = answersedit;
			myQuestions[currentSlide].question = title;
			allinputs.forEach(function(userItem) {
				userItem.style.display = "none";
			});
			allspans.forEach(function(userItem) {
				userItem.style.display = "none";
			});
		}
	}
  }
  
  function changeQuestion(){
	  
  }
  
	function setReal(){
		type = "real";
		idn++;
		addQuestion();
		changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
	}

	function setUnique(){
		type = "unique";
		idn++;
		addQuestion();
		changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;		
	}
	
	function setMulti(){
		type = "multi";
		idn++;
		addQuestion();
		changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
	}
	
	function setPrecise(){
		type = "precise";
		idn++;
		addQuestion();
		changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;
	}
	
	function setShow(){
		type = "show";	
		addQuestion();
	}
  
  function addQuestion() {
	if (type == "show") {
		$('#newquest')[0].style['opacity'] = 1;
		$('#newquest')[0].style['visibility'] = "visible";
		const selector = `.lquestion${currentSlide}`;
		const alllabels = document.querySelectorAll(selector);
		const selectorIn = `#i${currentSlide}`;
		const allinputs = document.querySelectorAll(selectorIn);
		if (editButton.value == "disedit") {
			alllabels.forEach(function(userItem) {
				userItem.setAttribute("contenteditable", "false");
				editButton.value = "enedit";
				editButton.classList.remove('savebutton');
				editButton.classList.add('editbutton');
				/*editButton.innerHTML = "Edit Question";*/
			});
			allinputs.forEach(function(userItem) {
				userItem.style.display = "none";
			});
		}
	}else if (type == "real"){
	  var item = {
		question: "Question par default",
		type: "real",
		id: `${idn}`,
		answers: {
			0: "Vrai",
			1: "Faux"
		},
		correctAnswer: "1",
		pointsp: 1,
		pointsm: 0
	  }
	  myQuestions.push(item);
	  $('#newquest')[0].style['opacity'] = 0;
	  $('#newquest')[0].style['visibility'] = "hidden";
	  console.log(myQuestions);
	  idn += 1;
	  addSlide();
	}else if (type == "unique"){
	  var item = {
		question: "Question par default",
		type: "unique",
		id: `${idn}`,
		answers: {
			0: "1er reponse",
			1: "2eme reponse",
			2: "3eme reponse",
			3: "4eme reponse",
		},
		correctAnswer: "2",
		pointsp: 1,
		pointsm: 0
	  }
	  myQuestions.push(item);
	  $('#newquest')[0].style['opacity'] = 0;
	  $('#newquest')[0].style['visibility'] = "hidden";
	  addSlide();
	}else if (type == "multi"){
	  var item = {
		question: "Question par default",
		type: "multi",
		id: `${idn}`,
		answers: {
			0: "1er reponse",
			1: "2eme reponse",
			2: "3eme reponse",
			3: "4eme reponse",
		},
		correctAnswer: { 
			0: "2",
			1: "3"
		},
		pointsp: 1,
		pointsm: 0
	  }
	  myQuestions.push(item);
	  $('#newquest')[0].style['opacity'] = 0;
	  $('#newquest')[0].style['visibility'] = "hidden";
	  addSlide();
	}else if (type == "precise"){
	  var item = {
		question: "Question par default",
		type: "precise",
		id: `${idn}`,
		answers: {
			0: "Unique Answer"
		},
		correctAnswer: "0",
		pointsp: 1,
		pointsm: 0
	  }
	  myQuestions.push(item);
	  $('#newquest')[0].style['opacity'] = 0;
	  $('#newquest')[0].style['visibility'] = "hidden";
	  addSlide();
	}
  }
  
  function delCurrentSlide(){
	var currentdiv = document.querySelector(`div[name='${myQuestions[currentSlide].id}']`);
	if (slides.length-1 == 0){
		myQuestions.splice(currentSlide, 1);
		changeButton.style['visibility'] = "hidden";
		changeButton.style['opacity'] = 0;
		currentdiv.remove();
		slides = document.querySelectorAll(".slide");
		type = "show";
		addQuestion();
	}else if(currentSlide == 0 && slides.length-1 > currentSlide) {
		myQuestions.splice(currentSlide, 1);
		currentdiv.remove();
		slides = document.querySelectorAll(".slide");
		showSlideEmpty(currentSlide);
	}else{
		myQuestions.splice(currentSlide, 1);
		currentdiv.remove();
		slides = document.querySelectorAll(".slide");
		showSlideEmpty(currentSlide - 1);
	}
  }
  
  function saveQname(){
	document.getElementById("newqname").style['opacity'] = 0;
	document.getElementById("newqname").style['visibility'] = "hidden";
	qname = document.getElementById("lqname").textContent;
	auth_id = parseInt(document.getElementById("auth_id").value);
	qname = qname.replace(/(\r\n|\n|\r)/gm,"");
	qname = qname.replace(/(^\s+|\s+$)/g, "");
	qnameButton.style['display'] = "none";
	document.getElementById("questmenu").style['display'] = "inline";
	openDate = document.getElementById("openDate").value;
	openTime = document.getElementById("openTime").value;
	closeDate = document.getElementById("closeDate").value;
	closeTime = document.getElementById("closeTime").value;
	type = "show";
	addQuestion();
  }
  
  function saveQCM(){
	  data = {questions: myQuestions, qid: qid, qname: qname, auth_id: auth_id, openDate: openDate, closeDate: closeDate, openTime: openTime, closeTime: closeTime, lock: isLocked};
	  var elementx = document.getElementById("results");
	  var ajaxurl = "saveQuest.php";
	  $.post(ajaxurl, data, function (response) {
		  elementx.innerHTML = response;
	  });
  }

  // Variables
  const quizContainer = document.getElementById('quiz');
  const resultsContainer = document.getElementById('results');
  const submitButton = document.getElementById('submit');
  var myQuestions = [];

  // Kick things off
  buildQuiz();

  // Pagination
  const previousButton = document.getElementById("previous");
  const nextButton = document.getElementById("next");
  const editButton = document.getElementById("edit");
  const addButton = document.getElementById("add");
  const delButton = document.getElementById("del");
  const changeButton = document.getElementById("change");
  const qnameButton = document.getElementById("saveqname");
  var slides = document.querySelectorAll(".slide");
  var idn = 0;
  var openDate = "2000-01-01";
  var openTime = "00:00:00";
  var closeDate = "2037-01-01";
  var closeTime = "00:00:00";
  isLocked = 0;
  <?php
  $qid = file_get_contents("lastid.txt");
  $qid = intval($qid) + 1;
  echo 'const qid = '.$qid.';
  ';
  ?>
  var qname = "";
  var auth_id = 0;
  var type = null;
  
  const realButton = document.getElementById("fakereal");
  const uniqueButton = document.getElementById("uniquechoice");
  const multiButton = document.getElementById("multichoices");
  const preciseButton = document.getElementById("preciseanwser");
  let currentSlide = 0;

  // Show the first slide
  /*showSlide(currentSlide);*/
  if(qname == ""){
	document.getElementById("newqname").style['opacity'] = 1;
	document.getElementById("newqname").style['visibility'] = "visible";
	qnameButton.style['display'] = "block";
  }
  
  /*changeButton.style['background-image'] = `url("icons/${myQuestions[currentSlide].type}.png")`;*/

  // Event listeners
  submitButton.addEventListener('click', saveQCM);
  previousButton.addEventListener("click", showPreviousSlide);
  nextButton.addEventListener("click", showNextSlide);
  editButton.addEventListener("click", editQuestion);
  addButton.addEventListener("click", setShow);
  addButton.addEventListener("hover", setShow);
  realButton.addEventListener("click", setReal);
  uniqueButton.addEventListener("click", setUnique);
  multiButton.addEventListener("click", setMulti);
  preciseButton.addEventListener("click", setPrecise);
  delButton.addEventListener("click", delCurrentSlide);
  changeButton.addEventListener("click", changeQuestion);
  qnameButton.addEventListener("click", saveQname);
</script>
</body>
</html>