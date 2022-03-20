function main() {
	document.querySelectorAll(".login-container").forEach(form => {
		// Unfilled, untouched fields shouldn't be highlighted in red
		/**
		 * @type {{[index:string]:boolean}}
		 */
		const Touched = {}
		addEventsListenersToHTMLCollection(
			["focus"],
			form.getElementsByClassName("text-input"),
			(event) => {
				Touched[event.target.id + " is Touched"] = true;
			}
		);

		// Immediately after performing an input or a focusout the red highlighting will be removed
		// After 1s of performing an input or a focusout the form will validate it self
		addEventsListenersToHTMLCollection(
			["input", "focusout"],
			form.getElementsByClassName("text-input"),
			doBeforeAfterSpin(
				/**
				 * 
				 * @param {Event} event 
				 */
				(event) => {
					removeErrorMsgUnderThisElem(event.target);
				},
				/**
				 * 
				 * @param {Event} event 
				 */
				(event) => {
					const scope = event.target.parentElement.parentElement.parentElement
					const read_form = readInputs(scope);
					const validFormFlag = isValidForm(read_form);
					showFormStatus(validFormFlag, scope, Touched);
				}
			)
		);
		if (formNameInScope(form) == "Login") return;
		const buttons = form.querySelectorAll('form .form-block button:not(.default-form)')
		if (buttons.length !== 0)
			buttons.forEach(element => {
				element.addEventListener("click", (event) => {
					event.preventDefault();
					const scope = event.target.parentElement.parentElement.parentElement
					const read_form = readInputs(scope);
					const validFormFlag = isValidForm(read_form);
					showFormStatus(validFormFlag, scope, Touched);
					const name = formNameInScope(scope)
					if (Object.values(validFormFlag).every(e => e)) { // all fields are valid
						fetch(ourURL + `Api/create/${name}`, {
							method: "POST",
							headers: {
								"Content-Type": "application/json",
							},
							body: JSON.stringify(read_form)
						}
						).then(response => response.text()).then(response_text => {
							console.log(response_text)
							if (response_text.startsWith('Operation Failed :')) {
								let unique_problem = /'(\w|_|-| )*_UNIQUE'/.exec(response_text)
								if (unique_problem) {
									const word = unique_problem[0].substring(1, unique_problem[0].length - (`_UNIQUE'`.length))

									Swal.fire({
										title: "Sorry!",
										text: `${name} wasn 't created ${word} : '${read_form[word]}' already exists!`,
										icon: "error",
										showCancelButton: false,
										confirmButtonColor: "#3085d6",
										cancelButtonColor: "#d33"
									})
								}
							} else {
								cleanInputs(scope)
								Swal.fire(
									"Done!",
									`${name.split('_').map(word => word[0].toUpperCase() + word.substring(1)).join('_')}.`,
									"info"
								)
							}
						})
					}
				})
			})
	})
}

addLoadEvent(main);
