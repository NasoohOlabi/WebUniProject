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
	addEventsListenersToHTMLCollection(
		["focus"],
		document.getElementsByClassName("text-input"),
		(event) => {
			window[event.currentTarget.id + " is Touched"] = true;
		}
	);
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
