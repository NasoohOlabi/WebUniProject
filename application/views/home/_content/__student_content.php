<script>
    const payload = {
        'limit': 1e9
    };
    try {
        fetch(`<?= URL ?>Api/read/exam`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(payload),
            }).then(response => response.text())
            .then(txt => {
                // console.log(txt);
                try {
                    let exams_list = JSON.parse(txt)
                    console.log("exams fetched")
                    // console.log(exams_list[0]['subject']['name']);

                    const select = document.getElementById("exams-list");

                    var def_text = (select.length ? "Select an exam" : "You don't have any exams to take");

                    document.getElementById('exam-default').innerText = def_text;

                    for (const exam of exams_list) {
                        const name = exam['subject']['name'];
                        select.options[select.options.length] = new Option(name, name);
                    }

                } catch (error) {
                    console.log('so close')
                    console.log(error)
                }
            })
    } catch (error) {
        console.log('ops!')
        console.log(error)
    }

    try {
        fetch(`<?= URL ?>Api/read/exam_center`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(payload),
            }).then(response => response.text())
            .then(txt => {
                // console.log(txt);
                try {
                    let exam_centers_list = JSON.parse(txt)
                    console.log("exams centers fetched")
                    console.log(exam_centers_list[0]);

                    const select = document.getElementById("exam-centers-list");

                    var def_text = (select.length ? "Select an exam center" : "There are no exam centers available");

                    document.getElementById('exam-center-default').innerText = def_text;

                    for (const exam_center of exam_centers_list) {
                        const name = exam_center['name'];
                        select.options[select.options.length] = new Option(name, name);
                    }

                } catch (error) {
                    console.log('so close')
                    console.log(error)
                }
            })
    } catch (error) {
        console.log('ops!')
        console.log(error)
    }
</script>


<div class="user-content">
    <h2>Take an exam:</h2>

    <select name="exam" id="exams-list" class="form-select">
        <option id="exam-default" value="" disabled selected></option>
    </select>

    <select name="exam-center" id="exam-centers-list" class="form-select" style="margin-top : 0.5em;">
        <option id="exam-center-default" value="" disabled selected></option>
    </select>

    <div class="form-block">
        <button type="submit">Take Exam</button>
    </div>
</div>