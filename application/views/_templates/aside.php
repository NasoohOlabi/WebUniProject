<div id="mid-section">
    <aside class="inlineBlock">
        <script>
            let clik = () => {
                let data = {
                    element: "barium"
                };

                fetch("/mnu/QuestionBank/Add", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                }).then(res => {
                    console.log("Request complete! response:", res);
                    console.log('res.json()')
                    res.json().then(e => {
                        console.log(e);
                    })
                });
            }
        </script>
        <dl>
            <dt>Question Bank</dt>
            <!-- <dd><button onclick="clik()">Add Question</button></dd> -->
            <dd><a href="/mnu/QuestionBank/add">Add Question</a></dd>
            <dt>Exams</dt>
            <dd>Add Exam</dd>
            <dd>Take Exam</dd>
        </dl>
    </aside>