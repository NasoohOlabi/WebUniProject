<style>
    .scrolling-wrapper>div {
        margin: 1em;
    }

    .mini {
        margin: 0.5em;
    }
</style>


<div class="form-block"><button onclick="
            document.getElementById(`<?= $sub_cls ?>-title`).style.display =
             (document.getElementById(`<?= $sub_cls ?>-title`).style.display == `none`)?`block`:`none`;
            
             document.getElementById(`<?= $sub_cls ?>-container`).style.display = 
            (document.getElementById(`<?= $sub_cls ?>-container`).style.display == `none`)?`flex`:`none`;
            
            document.querySelector(`.form-block button`).style.display = 
            (document.getElementById(`<?= $sub_cls ?>-container`).style.display == `none`)?`block`:`none`
            this.children[this.children.length-1].className = (this.children[this.children.length-1].className === 'fas fa-angle-down')?'fas fa-angle-up':'fas fa-angle-down'
            ">Add <?= ($sub_cls === 'Role_Has_Permission') ? 'Permissions' : str_replace('_', ' ', $sub_cls) ?> For This <?= str_replace('_', ' ', get_class($cls)) ?><i class="fas fa-angle-down" style="margin-left:50px"></i></button></div>
<h2 style="display:none;padding-left:10%;text-decoration:underline" id="<?= $sub_cls ?>-title"><?= str_replace('_', ' ', $sub_cls) ?>s:</h2>
<div id="<?= $sub_cls ?>-container" class="scrolling-wrapper" style="display:none;">
    <div class="add-form-container">
        <div class="form-block">
            <h1>Add <?= $the_other ?>
            </h1>
        </div>
        <form>
            <?php
            if (in_array('date', $sub_cls::SQL_Columns())) {
                date_input('date');
            }
            datalist_input($the_other, $SELECT_OPTIONS);
            ?>

            <input type="submit" value="Add" id="add-<?= $the_other ?>-dependant-btn" class="add-tile-dependant-btn">

            </input>
        </form>
    </div>
    <div style="display: flexbox;"></div>
</div>
<script>
    let i = 2;

    var Model = {}

    function cleanInputs(form) {
        form.querySelectorAll("input").forEach(elem => {
            if (elem.type != 'submit')
                elem.value = '';
        })
        form.querySelectorAll("select").forEach(elem => {
            elem.value = '';
        })
    }

    function readInputs(form) {
        /**
         * @type {{[index:string]:string}}
         */
        const dic = {};
        form.querySelectorAll("input").forEach(elem => {
            dic[elem.name] = elem.value.trim();
        })
        form.querySelectorAll("select").forEach(elem => {
            dic[elem.name] = elem.value.trim();
        })
        return dic;
    }
    addLoadEvent(() => {
            document.getElementById("add-<?= $the_other ?>-dependant-btn").addEventListener('click', (event) => {
                    event.preventDefault();
                    const form_element = document.getElementById("add-<?= $the_other ?>-dependant-btn").parentElement;
                    const loner = form_element.querySelectorAll('.form-block').length === 1
                    const form_obj = readInputs(form_element)
                    const datalist = form_element.querySelector('datalist')
                    const dateValue = form_obj['date']
                    const input_text = form_obj['<?= $the_other ?>']
                    if (![].slice.call(datalist.options).map(option => option.value).includes(input_text)) return;
                    let selected_option;
                    for (const option of datalist.options) {
                        if (option.value == input_text) {
                            if (loner)
                                option.disabled = true;
                            selected_option = option;
                            break;
                        }
                    }
                    form_element.querySelectorAll('.text-input').forEach(elem => {
                        if (elem.type != 'submit') elem.value = ''
                    })
                    const newKid = document.createElement('div')
                    newKid.innerHTML = input_text
                    newKid.className = "add-form-container mini"
                    newKid.value = selected_option.label
                    if (Model['<?= $sub_cls ?>'] == undefined)
                        Model['<?= $sub_cls ?>'] = []

                    Model['<?= $sub_cls ?>'].push({
                        '<?= strtolower($the_other) ?>_id': selected_option.label,
                        date: dateValue
                    })

                    const del_X = document.createElement('i')
                    del_X.className = "fa fa-close"
                    del_X.style = "float:right;font-size:1.2em;margin-left:15px;cursor:pointer;"
                    del_X.onclick = () => {
                        if (loner)
                            selected_option.disabled = false
                        del_X.parentElement.remove()
                    }
                    newKid.appendChild(del_X);
                    form_element.parentElement.parentElement.children[form_element.parentElement.parentElement.children.length - 1].appendChild(newKid)
                    submissions['<?= $sub_cls ?>'] = (parent_sql_id) => {

                        list = Model['<?= $sub_cls ?>'].map(obj => {
                            obj['<?= strtolower(get_class($the_one)) ?>_id'] = parent_sql_id;
                            return obj
                        })

                        list.forEach(payload => {
                            try {
                                fetch(`<?= URL ?>Api/create/<?= $sub_cls ?>`, {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                        },
                                        body: JSON.stringify(payload),
                                    }).then(response => response.text())
                                    .then(txt => {
                                        try {
                                            const successful_insert = JSON.parse(txt)
                                            console.log(successful_insert)
                                            document.getElementById('<?= $sub_cls ?>-container').querySelectorAll('.add-form-container.mini').forEach(elem => elem.children[elem.children.length - 1].onclick())
                                            document.querySelectorAll('.add-form-container form').forEach(form => cleanInputs(form))
                                        } catch (error) {
                                            console.log('so close')
                                            console.log(error)
                                        }
                                    })
                            } catch (error) {
                                console.log('ops!')
                                console.log(error)
                            }
                        })
                    }
                    for (let btn of document.querySelectorAll(`form .form-block button`)) {
                        btn.style.display = `none`;
                    }
                } //close event for btn
            )
        } //close addLoadEvent
    )
</script>