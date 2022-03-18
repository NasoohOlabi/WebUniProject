<div id="main-content" class="inlineBlock">
    <a href="<?= URL ?>dashboard">
        <div class="fa fa-2x fa-arrow-left back-btn"></div>
    </a>
    <div class="fa fa-2x fa-save save-btn"></div>
    <?php
    if (!$form) {
        foreach ($forms as $val) {
            $q = new $val();
            FormForThis($q, $bm);
        }
    } else if (in_array(strtolower($form), $this->forms)) {
        $q = new $form();
        PageForThis($q, $bm);
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

                const form = document.querySelector(".login-container")
                try {
                    const n1 = formNameInScope(form)
                    const n2 = readInputs(form)
                    fetch(ourURL + `Api/create/${formNameInScope(form)}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(readInputs(form))
                    }).then(res => {
                        return res.text()
                    }).then(res => {
                        try {
                            let respose_object = JSON.parse(res)
                            let lst = [].slice.call(document.querySelectorAll(".scrolling-wrapper"))

                            // const what_we_are_creating =
                            lst = lst.filter(wrapper => {
                                return !wrapper.id.split('-')[0].toLowerCase().includes('_has_')
                            })

                            if (lst.length === 0) return;

                            parent_sql_id = respose_object.id

                            Object.values(submissions).forEach(cb => {
                                cb(parent_sql_id)
                            })


                            lst = lst
                                .filter(wrapper => wrapper.querySelectorAll('form').length > 0)
                                .flatMap(wrapper => [].slice.call(wrapper.querySelectorAll('.login-container')))
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
                                    console.log(res)
                                })
                            })
                            Swal.fire({
                                title: "Created!",
                                text: `${n1.replace('_',' ')} was easily created.`,
                                icon: "info",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#d33"
                            })
                            lst.forEach(sub_form => sub_form.remove())

                        } catch (error) {
                            console.log(error)
                            console.log(res)
                        }
                        if (res instanceof Object) {

                        }
                    })
                } catch (error) {

                }
            })
        }
    })
</script>
</body>

</html>