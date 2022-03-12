
<?php
require_once 'component/input.php';

function stdclastoidstirng($stdClass)
{
    $columns = $stdClass::SQL_COLUMNS();
    $wanted_names = $stdClass->identifying_fields;
    $answer = [];
    foreach ($wanted_names as $prop) {
        $answer[] = $stdClass->$prop;
    }
    $answer = implode(' ', $answer);
    return $answer;
}
function FormForThis(Table $cls, BaseModel $bm)
{
    $required_fields = $cls::SQL_Columns();
    unset($required_fields[0]);
    // print_r($required_fields);
    $inputs = [];
    $SELECT_OPTIONS = [];
    foreach ($required_fields as  $field) {
        if (endsWith($field, "_id")) {
            $inputs[$field] = "select";
            // not solid nor Layered
            $schemaClass = ucfirst(substr($field, 0, strlen($field) - 3));
            $objects = $bm->select([], $schemaClass);
            $id_indexed_objects = [];
            foreach ($objects as $value) {
                $id_indexed_objects[$value->id] = $value;
            }
            $v = array_map('stdclastoidstirng', $id_indexed_objects);
            $SELECT_OPTIONS[$field] = $v;
        } else
            $inputs[$field] = 'text';
    }
    require '__form.php';
    if (count($cls->dependents) > 0) {
        echo '<style>.scrolling-wrapper div {margin:1em;}</style>';
        foreach ($cls->dependents as $sub_cls) {

            echo '<script>';
            echo 'let i = 2;  ';
            echo
            'addLoadEvent(()=>{
                document.getElementById("add-dependant-btn").onclick = (event)=>{
                console.log("catchMe");
                const btn = document.getElementById("add-dependant-btn");
                btn.insertAdjacentHTML("beforebegin",`';
            FormForThis(new $sub_cls(), $bm);
            echo '`.replaceAll(`  `,``).replaceAll(`\n`,``).replace(/<div class="form-block"><select name="' . strtolower(get_class($cls)) . '_id".*<\/select><\/div>/s , ""))
                    for (let btn of document.querySelectorAll(`form .form-block button`)){
                        btn.style.display = `none`;
                    }
                }
                const sv_btn = document.getElementsByClassName("save-btn")[0]
                if (sv_btn){
                    sv_btn.addEventListener("click",()=>{
                        let parent_sql_id = -1;
                        
                        const form = document.querySelector(".login-container")
                        
                        fetch(ourURL+`Api/create/${formNameInScope(form)}`,{
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(readInputs(form))
                        }).then(res=>{
                            try{
                                return res.json()
                            }catch(error){
                                return res.text()
                            }
                        }).then(res =>{
                            // if (res instanceof Object){
                                const lst = [].slice.call(document.querySelectorAll(".login-container"))
                                lst.shift()
                                console.log("res")
                                console.log(res)
                                parent_sql_id = res.id
                                lst.forEach(sub_form =>{
                                    const payload = readInputs(sub_form)
                                    payload[formNameInScope(form).toLowerCase()+"_id"] = parent_sql_id
                                    fetch(ourURL + `Api/create/${formNameInScope(sub_form)}`,{
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                        },
                                        body: JSON.stringify(payload)
                                    }).then(res=>{
                                        try{
                                            return res.json()
                                        }catch(error){
                                            return res.text()
                                        }
                                    }).then(res=>{
                                        console.log(res)
                                    })
                                })
                            // }
                        })
                    }  ) 
                }
            
    
    
    })';
            echo '</script>';
            echo '<div class="form-block"><button onclick="
            document.getElementById(`' . $sub_cls . '-title`).style.display = (document.getElementById(`' . $sub_cls . '-title`).style.display == `none`)?`block`:`none`;
            document.getElementById(`' . $sub_cls . '-container`).style.display = (document.getElementById(`' . $sub_cls . '-container`).style.display == `none`)?`flex`:`none`;
            document.querySelector(`.form-block button`).style.display = (document.getElementById(`' . $sub_cls . '-container`).style.display == `none`)?`block`:`none`
            ">Add Choice For This Question</button></div>';
            echo '<h2 style="display:none;" id="' . $sub_cls . '-title">' . $sub_cls . 's</h2>';
            echo '<div id="' . $sub_cls . '-container" class="scrolling-wrapper" style="display:none;">';
            echo '<button id="add-dependant-btn">+</button>';
            echo '</div>';
        }
    }
}
