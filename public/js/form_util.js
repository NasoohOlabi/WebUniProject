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
     * @param {string} username 
     * @returns {boolean}
     */
    username: (username) => {
        return username.length !== 0;
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
 * @returns {{[index:string]:string}} with {(input/select).name : value}
 */
function readInputs(form) {
    /**
     * @type {{[index:string]:string}}
     */
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
 * 
 * @param {HTMLElement} form 
 * @returns {void} with {(input/select).name : value}
 */
function cleanInputs(form) {
    form.querySelectorAll("input").forEach(elem => {
        elem.value = '';
    })
    form.querySelectorAll("select").forEach(elem => {
        elem.value = '';
    })
}
/**
 * object of key form_element.name mapped to string value
 * @param {{string? : boolean}}  form_obj
 */
function isValidForm(form_obj) {
    /**
     * @type {{[index:string]:boolean}}
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
/**
 * 
 * @param {Element} scope 
 * @returns 
 */
function formNameInScope(scope) {
    const elem = scope.querySelector("h1")
    return elem.innerText
}
function showErrorMsgUnderThisElem(elem, error_msg = null) {
    elem.className = "text-input invalid-input";
    elem.nextElementSibling.style.display = "block";
    const old_innerText = elem.nextElementSibling.innerText
    if (error_msg)
        elem.nextElementSibling.innerText = error_msg;
    return (element) => {
        element.className = "text-input valid-input";
        element.nextElementSibling.innerText = old_innerText
        element.nextElementSibling.style.display = "none";
    }
}
function removeErrorMsgUnderThisElem(elem) {
    elem.className = "text-input valid-input";
    elem.nextElementSibling.style.display = "none";
}
/**
 * Highlight elements that needs to be red Highlighted
 * and removes highlighting on those that don't need it accordingly
 * @param {{[index:string]:boolean}} flags 
 * @param {Element} scopeElement 
 * @param {{[index:string]:boolean}} Touched 
 */
function showFormStatus(flags, scopeElement, Touched = {}) {
    scopeElement.querySelectorAll(".text-input").forEach(elem => {
        if (Touched[elem.id + " is Touched"] && !flags[elem.id]) {
            showErrorMsgUnderThisElem(elem);
        } else {
            removeErrorMsgUnderThisElem(elem);
        }
    })
}
