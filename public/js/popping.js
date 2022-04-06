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
    popup.style.top = "0px";
    popup.style.display = "flex";
    document.getElementById('username').focus()
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
    let cleanUpFunction = removeErrorMsgUnderThisElem
    let submitted = false
    addEventsListenersToHTMLCollection(['input'], inputs, doBeforeAfterSpin(
        (event) => {
            submitted = false
            if (event.target.value.length === 0)
                showErrorMsgUnderThisElem(event.target)
            else
                cleanUpFunction(event.target)
            cleanUpFunction = removeErrorMsgUnderThisElem
        },
        (event) => {
            if (submitted) return
            if (event.target.id === 'username' && event.target.value.length > 0) {
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
                            submitted = true
                        } else if (response_text.includes('Username Exists')) {
                            cleanUpFunction(event.target)
                        }
                    })
            } else {
                cleanUpFunction(event.target)
            }
        }
    ))
    document.querySelector('#main-container-popup #submit-btn').addEventListener('click',
        async (evt) => {
            evt.preventDefault();
            const form_inputs = readInputs(document.getElementById('main-container-popup'))
            const res = await fetch(ourURL + 'users/validate', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(form_inputs),
            })
            const response_text = (await res.text()).trim()

            if (response_text.startsWith('Operation Failed : ')) {
                cleanUpFunction = showErrorMsgUnderThisElem(document.getElementById('password'), response_text.substring('Operation Failed : '.length))
                submitted = true
            } else {
                location.reload()
            }
            submitted = true
        }
    )
}

addLoadEvent(main)