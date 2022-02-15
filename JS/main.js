const ids = [
  "email",
  "password",
  "first_name",
  "last_name",
  "confirm-password",
  "phone",
  "AccountType",
];

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
      /[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password)
    );
  },
  first_name: (first_name) => first_name.length > 0,
  last_name: (last_name) => last_name.length > 0,
  "confirm-password": (confirm_password, password) =>
    password === confirm_password,
  phone: (PhoneNumber) => /09[0-9]{8}/.test(PhoneNumber),
  AccountType: (type) => type.length > 0,
};

const doAfterSpin = (callBack) => (event) => {
  var result = callBack();
  console.log(result);
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
    setFormStatus(result[1]["details"]);
    callBack();
  }, 1000);
};

const addEventListenerToHTMLCollection = (eventName, collection, callBack) => {
  for (let elem of collection) {
    elem.addEventListener(eventName, callBack);
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
  const res = [];
  for (const key in form_obj) {
    if (Object.hasOwnProperty.call(form_obj, key)) {
      if (key === "confirm-password") {
        const receivedValue = form_obj[key];
        res.push({
          key: key,
          v: validators[key](receivedValue, form_obj["password"]),
        });
      } else {
        const receivedValue = form_obj[key];
        res.push({ key: key, v: validators[key](receivedValue) });
      }
    }
  }
  return {
    flag: res.map((v) => v.v).every((bool) => bool),
    details: res.reduce((acc, elem) => {
      acc[elem["key"]] = elem["v"];
      return acc;
    }, {}),
  };
}

function setFormStatus(flags) {
  let elem = document.activeElement;
  console.log(elem.id);
  if (ids.find((str) => str === elem.id)) {
    if (flags[elem.id]) {
      elem.className = "text-input valid-input";
      elem.nextElementSibling.style.display = "none";
    } else {
      elem.className = "text-input invalid-input";
      elem.nextElementSibling.style.display = "block";
    }
  }
}

function main() {
  addEventListenerToHTMLCollection(
    "input",
    document.getElementsByClassName("text-input"),
    doAfterSpin(() => {
      const read_form = readInputs();
      const validFormFlag = isValidForm(read_form);
      return [read_form, validFormFlag];
    })
  );
}

window.onload = main;
