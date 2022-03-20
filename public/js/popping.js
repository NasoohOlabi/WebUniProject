function amIclicked(e, element) {
  e = e || event;
  var target = e.target || e.srcElement;
  if (target.id == element.id) return true;
  else return false;
}
function outerClicked(event, element) {
  if (amIclicked(event, element)) {
    const popup = document.getElementById("main-container-popup");
    popup.style.display = "none";
    document.getElementsByClassName("invalid-text")[0].style.display = "none";
    document.getElementsByClassName("invalid-text")[1].style.display = "none";
  }
}
function pop() {
  const popup = document.getElementById("main-container-popup");
  popup.style.top = "0";
  popup.style.display = "flex";
}

function toggleError() {
  const smallElements = document.getElementsByClassName("invalid-text");

  for (let elem of smallElements) {
    var display = elem.style.display;
    if (display == "none" || display == "") elem.style.display = "block";
    else elem.style.display = "none";
  }
}

function not_empty(str) {
  return str.length > 0
}

function main() {
  const inputs = document.getElementById('main-container-popup').getElementsByClassName('text-input')
  let cleanUpFunction = removeErrorMsgUnderThisElem;
  addEventsListenersToHTMLCollection(['input'], inputs, doBeforeAfterSpin(
    (event) => {
      if (event.target.value.length === 0)
        showErrorMsgUnderThisElem(event.target)
      else
        cleanUpFunction(event.target)
      cleanUpFunction = removeErrorMsgUnderThisElem
    },
    (event) => {
      if (event.target.id === 'username') {
        fetch(ourURL + `users/exist/${event.target.value}`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          }
        }).then(res => res.text())
          .then(response_text => {
            response_text = response_text.trim()
            if (response_text.includes('Username Doesn\'t exist')) {
              cleanUpFunction = showErrorMsgUnderThisElem(event.target, response_text)
            } else if (response_text.includes('Username Exists')) {
              cleanUpFunction(event.target)
            }
          })
      }
    }
  ))
}

addLoadEvent(main)