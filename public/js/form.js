// ids = Object.keys(readInputs)
// const ids = [
// 	"email",
// 	"password",
// 	"first_name",
// 	"last_name",
// 	"confirm-password",
// 	"phone",
// 	"AccountType",
// ];

/**
 * A set of validator functions to use while validating forms
 */
const validators = {
	/**
	 * 
	 * @param {string} email 
	 * @returns {boolean}
	 */
	email: (email) => {
		// validate email format
		const emailRegex =
			/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		const anotherRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	},
	/**
	 * 
	 * @param {string} password 
	 * @returns {boolean}
	 */
	password: (password) => {
		// validate password format
		return (
			/[a-z]/.test(password) &&
			/[A-Z]/.test(password) &&
			/[0-9]/.test(password)
		);
	},
	/**
	 * 
	 * @param {string} first_name 
	 * @returns {boolean}
	 */
	first_name: (first_name) => first_name.length > 0,
	/**
	 * 
	 * @param {string} last_name 
	 * @returns {boolean}
	 */
	last_name: (last_name) => last_name.length > 0,
	/**
	 * 
	 * @param {string} confirm_password 
	 * @param {string} password 
	 * @returns {boolean}
	 */
	"confirm-password": (confirm_password, password) =>
		password === confirm_password,
	/**
 * 
 * @param {string} PhoneNumber 
 * @returns {boolean}
 */
	phone: (PhoneNumber) => /09[0-9]{8}/.test(PhoneNumber),
	/**
	 * 
	 * @param {string} type 
	 * @returns {boolean}
	 */
	AccountType: (type) => type.length > 0
};

let parsing_input_interval = 0;

/**
 *	Note that multiple calls to doBeforeAfterSpin will only take into account last 1000 ms
 * @param {(event)=>void} init
 * @param {(event)=>void} callBack
 * @returns function that execute init and then execute callBack after 1000 ms
 */
const doBeforeAfterSpin = (init, callBack) => (event) => {
	const scope = event.target.parentElement.parentElement.parentElement
	init(event);
	if (parsing_input_interval) {
		clearInterval(parsing_input_interval);
	}
	if (scope.querySelector("#spinner"))
		scope.querySelector("#spinner").style.display = "inline";
	scope.querySelector(".form-block > button").disabled = true;
	scope.querySelector("#submit-btn").disabled = true;
	parsing_input_interval = setTimeout(() => {
		if (scope.querySelector("#spinner"))
			scope.querySelector("#spinner").style.display = "none";
		callBack(event);
	}, 1000);
};
/**
 * Adds callBack as eventLister to listOfEventNames events to the nodes in HtmlCollection
 * @param {string[]} listOfEventNames
 * @param {HTMLCollectionOf<Element>} collection
 * @param {(event)=>void} callBack
 */
const addEventsListenersToHTMLCollection = (
	listOfEventNames,
	collection,
	callBack
) => {
	for (let elem of collection) {
		listOfEventNames.forEach((eventName) => {
			elem.addEventListener(eventName, callBack);
		});
	}
};
/**
 *
 * @returns Object with {(input/select).name : value}
 */
/**
 * 
 * @param {HTMLElement} form 
 * @returns {object} with {(input/select).name : value}
 */
function readInputs(form) {
	const dic = {};
	form.querySelectorAll("input").forEach(elem => {
		dic[elem.name] = elem.value.trim();
	})
	form.querySelectorAll("select").forEach(elem => {
		dic[elem.name] = elem.value.trim();
	})
	return dic;
}
/**
 * object of key form_element.name mapped to string value
 * @param {{string : boolean}}  form_obj
 */
function isValidForm(form_obj) {
	/**
	 * @type {{string?:boolean}}
	 */
	const res = {};
	for (const key in form_obj) {
		if (Object.hasOwnProperty.call(form_obj, key)) {
			if (key === "confirm-password") {
				const receivedValue = form_obj[key];
				res[key] = validators[key](receivedValue, form_obj["password"]);
			} else if (
				key === "password" &&
				document.querySelector("button#submit-btn").innerText === "Login"
			) {
				// no need to validate login password
				res[key] = true;
			} else if (validators[key]) {
				const receivedValue = form_obj[key];
				res[key] = validators[key](receivedValue);
			} else {
				res[key] = true
			}
		}
	}
	return res;
}
function showErrorMsgUnderThisElem(elem) {
	elem.className = "text-input invalid-input";
	elem.nextElementSibling.style.display = "block";
}
function removeErrorMsgUnderThisElem(elem) {
	elem.className = "text-input valid-input";
	elem.nextElementSibling.style.display = "none";
}
/**
 * Highlight elements that needs to be red Highlighted
 * and removes highlighting on those that don't need it accordingly
 * @param {{string?:boolean}} flags 
 * @param {Element} scopeElement 
 * @param {{string?:boolean}} Touched 
 */
function setFormStatus(flags, scopeElement, Touched = {}) {
	scopeElement.querySelectorAll(".text-input").forEach(elem => {
		if (Touched[elem.id + " is Touched"] && !flags[elem.id]) {
			showErrorMsgUnderThisElem(elem);
		} else {
			removeErrorMsgUnderThisElem(elem);
		}
	})
}

function main() {
	// Unfilled, untouched fields shouldn't be highlighted in red
	const Touched = {}
	addEventsListenersToHTMLCollection(
		["focus"],
		document.getElementsByClassName("text-input"),
		(event) => {
			Touched[event.target.id + " is Touched"] = true;
		}
	);

	// Immediately after performing an input or a focusout the red highlighting will be removed
	// After 1s of performing an input or a focusout the form will validate it self
	addEventsListenersToHTMLCollection(
		["input", "focusout"],
		document.getElementsByClassName("text-input"),
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
				const scope = event.target.parentElement.parentElement
				const read_form = readInputs(scope);
				const validFormFlag = isValidForm(read_form);
				setFormStatus(validFormFlag, scope, Touched);
			}
		)
	);
	document.querySelectorAll('form .form-block button').forEach(element => {
		element.addEventListener("onclick", (event) => {
			const scope = event.target.parentElement.parentElement
			const read_form = readInputs(scope);
			const validFormFlag = isValidForm(read_form);
			setFormStatus(validFormFlag, scope, Touched);
		})
	})
}

window.onload = main;
