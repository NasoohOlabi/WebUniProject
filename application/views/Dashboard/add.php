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
    addLoadEvent(() => {
        const sv_btn = document.getElementsByClassName("save-btn")[0]
        if (sv_btn) {
            sv_btn.addEventListener("click", () => {
                let parent_sql_id = -1;

                const form = document.querySelector(".login-container")
                try {
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
                            const lst = [].slice.call(document.querySelectorAll(".login-container"))
                            lst.shift()
                            if (lst.length === 0) return;
                            parent_sql_id = respose_object.id
                            lst.forEach(sub_form => {
                                const payload = readInputs(sub_form)
                                payload[formNameInScope(form).toLowerCase() + "_id"] = parent_sql_id
                                fetch(ourURL + `Api/create/${formNameInScope(sub_form)}`, {
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
                        } catch (error) {
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