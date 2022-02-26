const choices = Array.from(document.getElementsByClassName("quiz-choice-text"));

choices.forEach((choice) => {
	choice.addEventListener("click", (e) => {
		const selectedChoice = e.target;
		const selectedAnswer = selectedChoice.dataset["number"];

		selectedChoice.parentElement.classList.add("selected");
	});
});
