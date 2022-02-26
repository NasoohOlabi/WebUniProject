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
	email: (email) => {
		// validate email format
		const emailRegex =
			/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		const anotherRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	},
	password: (password) => {
		// validate password format
		return (
			/[a-z]/.test(password) &&
			/[A-Z]/.test(password) &&
			/[0-9]/.test(password)
		);
	},
	first_name: (first_name) => first_name.length > 0,
	last_name: (last_name) => last_name.length > 0,
	"confirm-password": (confirm_password, password) =>
		password === confirm_password,
	phone: (PhoneNumber) => /09[0-9]{8}/.test(PhoneNumber),
	AccountType: (type) => type.length > 0,
	ProfileImg: (fakePath) => fakePath.length > 0,
};

/**
 *	Note that multiple calls to doBeforeAfterSpin will only take into account last 1000 ms
 * @param {(event)=>void} init
 * @param {(event)=>void} callBack
 * @returns function that execute init and then execute callBack after 1000 ms
 */
const doBeforeAfterSpin = (init, callBack) => (event) => {
	init(event);
	if (window.parsing_input_interval) {
		clearInterval(window.parsing_input_interval);
	}
	if (document.getElementById("spinner"))
		document.getElementById("spinner").style.display = "inline";
	document.querySelector(".form-block > button").disabled = true;
	document.getElementById("submit-btn").disabled = true;
	window.parsing_input_interval = setTimeout(() => {
		if (document.getElementById("spinner"))
			document.getElementById("spinner").style.display = "none";
		callBack(event);
	}, 1000);
};
/**
 * Adds callBack as eventLister to listOfEventNames events to the nodes in HtmlCollection
 * @param {string[]} listOfEventNames
 * @param {HtmlCollection} collection
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
function readInputs() {
	const dic = {};
	for (let elem of document.getElementsByTagName("input")) {
		dic[elem.name] = elem.value.trim();
	}
	for (let elem of document.getElementsByTagName("select")) {
		dic[elem.name] = elem.value.trim();
	}
	return dic;
}
/**
 * object of key form_element.name mapped to string value
 * @param {string : boolean} form::element
 */
function isValidForm(form_obj) {
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
			} else {
				const receivedValue = form_obj[key];
				res[key] = validators[key](receivedValue);
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
 * @param {} flags
 */
function setFormStatus(flags) {
	for (let elem of document.getElementsByClassName("text-input")) {
		if (window[elem.id + " is Touched"] && !flags[elem.id]) {
			showErrorMsgUnderThisElem(elem);
		} else {
			removeErrorMsgUnderThisElem(elem);
		}
	}
}

function main() {
	// Unfilled, untouched fields shouldn't be highlighted in red
	addEventsListenersToHTMLCollection(
		["focus"],
		document.getElementsByClassName("text-input"),
		(event) => {
			window[event.currentTarget.id + " is Touched"] = true;
		}
	);

	// Immediately after performing an input or a focusout the red highlighting will be removed
	// After 1s of performing an input or a focusout the form will validate it self
	addEventsListenersToHTMLCollection(
		["input", "focusout"],
		document.getElementsByClassName("text-input"),
		doBeforeAfterSpin(
			(event) => {
				removeErrorMsgUnderThisElem(event.currentTarget);
			},
			(event) => {
				const read_form = readInputs();
				const validFormFlag = isValidForm(read_form);
				setFormStatus(validFormFlag);
			}
		)
	);
}

window.onload = main;
