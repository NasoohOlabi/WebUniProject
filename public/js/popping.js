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
