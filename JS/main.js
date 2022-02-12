function SayHi() {
	alert("Hi");
}

function main() {
	window.parsing_input_interval = undefined;
	for (let elem of document.getElementsByClassName("text-input")) {
		elem.addEventListener("input", (event) => {
			console.log(`event`);
			if (window.parsing_input_interval) {
				clearInterval(window.parsing_input_interval);
			}
			document.getElementById("spinner").style.display = "inline";
			document.querySelector(".form-block > button").disabled = true;
			window.parsing_input_interval = setTimeout(() => {
				document.getElementById("spinner").style.display = "none";
				document.querySelector(".form-block > button").disabled = false;
			}, 1000);
		});
	}
}

window.onload = main;
