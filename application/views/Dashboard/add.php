<style>
    header {
        position: relative;
        z-index: 20;
        box-shadow: 0px -1px 7px rgba(50, 50, 50);
    }

    .save-btn {
        color: black;
        width: fit-content;
        padding: 0.1em 0.3em;
        margin: 0.1em;
        border-radius: 10px;
        position: absolute;
        top: 0;
        <?= (Language::$direction === 'ltr') ? 'right' : 'left' ?>: 0;
    }
</style>
<script>
    function radio(e) {
        if (e.checked) {
            document.querySelectorAll('input[name=\'is_correct\']').forEach((el) => {
                el.checked = false;
            });
            e.checked = true;
        }
    }
</script>
<div id="main-content" class="inlineBlock">
    <div id="sub-nav">
        <a href="<?= URL ?>dashboard">
            <div class="fa fa-2x fa-arrow-<?= (Language::$direction !== 'ltr') ? 'right' : 'left' ?> back-btn"></div>
        </a>
        <div class="fa fa-2x fa-save save-btn"></div>
    </div>
    <?php
    if (!$form) {
        foreach ($forms as $val) {
            $q = new $val();
            FormForThis($q, $bm);
        }
    } else if (in_array(strtolower($form), $this->forms)) {
        $q = new $form();
        PageForThis($q, $bm, [], false);
    } else {
        return;
    }
    ?>
</div>
<script>
    var submissions = {}
    addLoadEvent(() => {

        const sv_btn = document.getElementsByClassName("save-btn")[0]
        if (sv_btn) {
            sv_btn.addEventListener("click", () => {
                let parent_sql_id = -1;

                const form = document.querySelector(".add-form-container")
                if (form == null) return;
                const n1 = formNameInScope(form)
                const read_inputs = readInputs(form)
                fetch(ourURL + `Api/create/${formNameInScope(form)}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(read_inputs)
                }).then(res => {
                    return res.text()
                }).then(res => {
                    if (res.startsWith('Operation Failed :')) {
                        let unique_problem = /'(\w|_|-| )*_UNIQUE'/.exec(res)
                        if (unique_problem) {
                            const word = unique_problem[0].substring(1, unique_problem[0].length - (`_UNIQUE'`.length))
                            Swal.fire(
                                "Sorry!",
                                `${n1.replace('_', ' ')} wasn 't created ${word} : '${read_inputs[word]}' already exists!`,
                                "error"
                            );
                        }
                    }
                    try {
                        let response_object = JSON.parse(res)
                        parent_sql_id = response_object.id
                    } catch (error) {
                        Swal.fire(
                            "Was not Added!",
                            `Your changes have not been saved. error ${error}  res ${res}`,
                            "error"
                        );
                        console.log(error)
                        console.log(res)
                        return
                    }
                    let lst = [].slice.call(document.querySelectorAll(".scrolling-wrapper"))

                    // const what_we_are_creating =
                    lst = lst.filter(wrapper => {
                        return !wrapper.id.split('-')[0].toLowerCase().includes('_has_')
                    })

                    if (lst.length === 0) return;


                    Object.values(submissions).forEach(cb => {
                        cb(parent_sql_id)
                    })


                    lst = lst
                        .filter(wrapper => wrapper.querySelectorAll('form').length > 0)
                        .flatMap(wrapper => [].slice.call(wrapper.querySelectorAll('.add-form-container')))

                    if (lst.length === 0) {
                        Swal.fire("Created!", `${n1.replace('_',' ')} was easily created.`, "success");
                        document.querySelectorAll('input, select').forEach(input => {
                            input.value = ''
                        })
                        return
                    }

                    lst.forEach(sub_form => {
                        const payload = readInputs(sub_form)
                        payload[formNameInScope(form).toLowerCase() + "_id"] = parent_sql_id
                        const n1 = formNameInScope(sub_form)
                        fetch(ourURL + `Api/create/${n1}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(payload)
                        }).then(res => {
                            return res.text()
                        }).then(res => {
                            if (res.startsWith('Operation Failed :')) {
                                let unique_problem = /'(\w|_|-| )*_UNIQUE'/.exec(res)
                                if (unique_problem) {
                                    const word = unique_problem[0].substring(1, unique_problem[0].length - (`_UNIQUE'`.length))

                                    Swal.fire({
                                        title: "Sorry!",
                                        text: `${n1.replace('_', ' ')} wasn 't created ${word} : '${payload[word]}' already exists!`,
                                        icon: "error",
                                        showCancelButton: false,
                                        confirmButtonColor: "#3085d6",
                                        cancelButtonColor: "#d33"
                                    })
                                }
                            } else {
                                Swal.fire("Created!", `${n1.replace('_',' ')} was easily created.`, "success");
                                document.querySelectorAll('input , select').forEach(input => {
                                    input.value = ''
                                })
                            }
                        })
                    })

                    lst.forEach(sub_form => sub_form.parentElement.remove())

                    if (res instanceof Object) {

                    }
                })
            })
        }
    })
</script>
</body>

</html>