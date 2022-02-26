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
	}
}
function innerClicked(event, element) {
	if (amIclicked(event, element)) {
		alert("Two is clicked");
	}
}
function pop() {
	const popup = document.getElementById("main-container-popup");
	popup.style.top = "0";
	popup.style.display = "flex";
}
