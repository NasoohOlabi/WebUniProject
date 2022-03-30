var deleteList = [];
var modifyMode = false;
var fetching_flag = {};
var statistics = null;
/**
 * @type {{[index: string]:object}}
 */
var Model = {};
const schemaClasses = [
    "question",
    "role",
    "exam",
    "subject",
    "topic",
    "choice",
    "permission",
    "role_has_permission",
    "user",
    "exam_center",
    "student",
    "exam_has_question",
    "student_took_exam",
    "exam_center_has_exam",
];

var currentTab =
    getCookie("currentTab") == ""
        ? (() => {
            setCookie("currentTab", "Dashboard", 3);
            switchTo("Dashboard");
            return "Dashboard";
        })()
        : (() => {
            const curr = getCookie("currentTab");
            switchTo(curr);
            return curr;
        })();

/**
 * @param cname {string}
 * @param cvalue {string}
 * @param exdays {number}
 */
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
/**
 * @param cname {string}
 */
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
/**
 *
 * @param {string} str
 * @param {string} separator = " "
 * @returns
 */
function UpperCaseFirstLetter(str, separator = " ") {
    const arr = str.split(separator);

    const upperCasedWords = arr.map((word) => {
        return word.charAt(0).toUpperCase() + word.slice(1);
    });

    return upperCasedWords.join(separator);
}

const tasks = [];

setInterval(() => {
    while (tasks.length > 0) {
        tasks.shift()();
    }
}, 300);

/**
 * @param {(event:any)=>void} callback
 * @param {string} id
 */
function set_OnClick_For_Id(callback, id, retries = 60) {
    if (retries === 0) { console.log('retries excustid'); return }
    tasks.push(() => {
        if (document.getElementById(id)) {
            document.getElementById(id).onclick = callback;
        } else {
            set_OnClick_For_Id(callback, id, retries - 1);
        }
    });
}

/**
 *
 * @param {string} input_name
 * @param {{id:number}[]} SELECT_OPTIONS
 * @param {{id:number}} SELECTED_ELEMENT
 * @param {string} place_holder
 * @returns
 */
function select(input_name, SELECT_OPTIONS, SELECTED_ELEMENT, place_holder) {
    const objects = SELECT_OPTIONS;
    const identifying_string = (o) => {
        const arr = o.identifying_fields
            ? o.identifying_fields.map((field) => o[field])
            : Object.values(o).filter((v) => !(v instanceof Object));

        return JSON.stringify(arr)
            .replaceAll("[", "")
            .replaceAll('"', "")
            .replaceAll("]", "")
            .replaceAll(",", " ");
    };

    return `<select name="${input_name}" class="form-select valid-input" aria-label="${place_holder}">
        ${objects.map(
        (object) =>
            `<option value="${object.id}" ${SELECTED_ELEMENT.id === object.id ? "selected" : ""
            }>${identifying_string(object)}</option>`
    )}
        ?>
    </select>`;
}
/**
 *
 * @param {string} id
 * @param {string[]} header
 * @returns
 */
function MainTable(id, header) {
    return `<div id="MainTable-container"><table class="table" >
            <thead>
            ${Header(id, header)}
            </thead>
            <tbody id="MainTable">
            </tbody>
          </table></div>`;
}
/**
 *
 * @param {string} key
 * @returns {boolean}
 */
function is_display_key(key) {
    return (
        !key.endsWith("id") &&
        key !== "identifying_fields" &&
        key !== "dependents" &&
        key !== "students" &&
        !key.toLowerCase().includes("has")
    );
}
/**
 *
 * @param {HTMLTableElement} elem
 * @returns {boolean}
 */
function is_not_unicode_sth(elem) {
    return !(
        elem.children[0] && ["🔽", "🔼"].includes(elem.children[0].innerText)
    );
}
/**
 *
 * @param {string} id_to_toggle
 */
function toggleDropDown(id_to_toggle) {
    let dropped_down = false;
    let tr = document.getElementById(id_to_toggle);
    let btn = document.getElementById(id_to_toggle + "-" + "btn");
    const f = function () {
        // if you can't see them look for them
        if (!(tr && btn)) {
            tr = document.getElementById(id_to_toggle);
            btn = document.getElementById(id_to_toggle + "-" + "btn");
        }
        dropped_down = !dropped_down;
        if (btn) btn.innerText = !dropped_down ? "🔽" : "🔼";

        // if you can't see even after you looked them abort
        if (tr && btn)
            tr.style.display = !dropped_down ? "none" : "table-row";

    };
    btn.onclick = f;
    f();
}
/**
 * @param {string} identifier
 */
function revoker(identifier) {
    return (event) => {
        const perm = Model[identifier.split('::')[1]].name
        getFromHQ('delete', `role_has_permission/${identifier.split('::')[1].split('-')[0]}`, {
            unclean: (txt) => {
                if (txt == 'deleted') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: `${perm} revoked`,
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            }
        })
    }
}
/**
 * 
 * @param {string} identifier 
 */
function permissionSubRow(identifier) {
    console.log('HI');
    const row_item = Model[identifier.split("::").pop()];
    let display_keys = Object.keys(row_item).filter(is_display_key)
    if (row_item['role'] == undefined) display_keys = display_keys.filter(x => x != 'role')


    const tr = `<tr id="${identifier}"><td>${row_item['name']}</td><td><span class="permission-clickable" id="${identifier}-revoke">Revoke</span></td></tr>`;

    // TODO: add a event handler to revoke permission
    set_OnClick_For_Id(revoker(identifier), identifier + "-revoke");

    return tr;
}
/**
 *
 * @param {string} id
 * @param {string[]} names
 * @returns
 */
function Header(id, names) {
    const processed_names = (names.includes('profile_picture'))
        ? ((names.includes('role'))
            ? ["profile_picture", 'username', 'first_name', 'middle_name', 'last_name', 'role']
            : ["profile_picture", 'username', 'first_name', 'middle_name', 'last_name'])
        : names
    const th_s = processed_names.filter(is_display_key)
        .map(item => item
            .replaceAll('_', ' ')
            .split(' ')
            .map(word => word[0].toUpperCase() + word.substring(1))
            .join(' ')
        )
        .map((v) => "<th>" + v + "</th>");

    th_s.push('<th colspan="2"><th>');

    const html_string = th_s.join("");

    return `<tr id ="${id}">
      ${html_string}
    </tr>`;
}
/**
 *
 * @param {string} identifier
 * @param {boolean} inline_keys
 * @param {string} inline_key_prefix
 * @returns {string} tr element
 */
function TableRow(identifier, inline_keys = false, inline_key_prefix = "") {
    const v = identifier.split('::');
    if (v && v.length == 2 && v[0].startsWith('role') && v[1].startsWith('permission')) return permissionSubRow(identifier);
    const row_item = Model[identifier.split("::").pop()];
    let display_keys = Object.keys(row_item).filter(is_display_key)
    display_keys = (display_keys.includes('profile_picture'))
        ? ["profile_picture", 'username', 'first_name', 'middle_name', 'last_name', 'role']
        : display_keys
    if (row_item['role'] == undefined) display_keys = display_keys.filter(x => x != 'role')
    let number_of_display_columns =
        display_keys.length;
    if (inline_keys) number_of_display_columns *= 2;
    const subTables = [];
    const ancestors = identifier
        .split("::")
        .slice(0, -1)
        .map((name_id) => name_id.split("-")[0]);
    let td_s = display_keys
        .filter((key) => !ancestors.some((elem) => elem.includes(key)))
        .map((key) => {
            const value = row_item[key];
            if (key == 'profile_picture') {
                return `<td class="profile-pic"><img src="${ourURL}DB/ProfilePics/${((row_item['profile_picture']) ? row_item['profile_picture'] : 'newuser2.png')}" width="50" height="50"/></td>`
            }
            let td = inline_keys
                ? `<td style="border-top:none"><strong>${inline_key_prefix} ${key}:</strong></td>`
                : "";
            if (value instanceof Array) {
                // just making sure key is a single word
                // key.replace(' ', '-')
                // since key is a field in the object this row represent
                const id_of_tr_this_btn_will_expand = identifier + "::" + key;
                subTables.push(id_of_tr_this_btn_will_expand);
                td += `<td
                        ${inline_keys ? 'style = "border-top:none"' : ""}
                        >
                        <button
                        id="${id_of_tr_this_btn_will_expand}-btn" 
                        style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;">
                            🔽
                        </button>
                        </td>`;
                set_OnClick_For_Id(() => {
                    toggleDropDown(id_of_tr_this_btn_will_expand);
                }, id_of_tr_this_btn_will_expand + "-btn");
            } else if (key == `is_correct`) {
                td += `<td
          ${inline_keys ? 'style = "border-top:none"' : ""}
          >${value ? "✔" : "❌"}</td>`;
            } else {
                td += `<td
          ${inline_keys ? 'style = "border-top:none"' : ""}
          >${value}</td>`;
            }

            return td;
        });

    const tds_text = td_s.join("");

    const delete_edit_icons = inline_keys
        ? `<td>
      <i class="fa fa-pencil" aria-hidden="true" id="${identifier}-switcher"></i>
    </td>`
        : `<td>
      <i class="fa fa-trash" aria-hidden="true"  id="${identifier}-left"></i>
    </td>
    <td>
      <i class="fa fa-pencil" aria-hidden="true"  id="${identifier}-right"></i>
    </td>`;
    if (inline_keys) {
        set_OnClick_For_Id(edit_sub_Row(identifier), identifier + "-switcher");
    } else {
        set_OnClick_For_Id(deleteRow, identifier + "-left");
        set_OnClick_For_Id(editRow(identifier), identifier + "-right");
    }
    const tr = `<tr id="${identifier}">${tds_text}${delete_edit_icons}</tr>`;
    const subTablesWrappedInTr_s = subTables
        .map((sub_tr_identifier) => {
            const id_list = sub_tr_identifier.split("::");
            const key = id_list.pop();
            const res = subTable_tr(
                row_item[key],
                sub_tr_identifier,
                number_of_display_columns,
                identifier
            );
            return res;
        })
        .join("\n");
    return `${tr}\n${subTablesWrappedInTr_s}`;
}
function GrantPemission(identifier) {
    return (evt) => {
        console.log(`identifier : `);
        console.log(identifier);
        const One2Many_one_id = identifier.split("::")[0].split('-')[1];
        
    }
}
/**
 *
 * @param {string[]} identifiers
 * @param {string} trId
 * @param {number} parent_number_of_keys
 * @returns {string} tr containing table
 */
function subTable_tr(
    identifiers,
    trId,
    parent_number_of_keys,
    parentIdentifier
) {
    console.log(`(${identifiers}.length === 0 && ${trId}.includes('permissions')) : `);
    console.log((identifiers.length === 0 && trId.includes('permissions')));
    if (parent_number_of_keys === 0) return
    if (identifiers.length > 1 || trId.split("-").pop().endsWith("s")) {
        if (identifiers.length === 0 && !trId.includes('permissions')) return;
        const tr_s = identifiers.map((identifier) =>
            TableRow(parentIdentifier + "::" + identifier)
        );
        const header = (trId.includes('permissions'))
            ? `<th>Name</th><th colspan="2"><span id="${trId}-Grant" class="permission-clickable">Grant Permission</span></th>`
            : Header(
                trId.split("-")[1],
                Object.keys(Model[identifiers[0]])
            )

        set_OnClick_For_Id(GrantPemission(trId), trId + "-Grant");

        return `<tr id="${trId}" style="display:none" class="inner-shadowed">
              <td colspan=${parent_number_of_keys + 2}>
                <table style="width:100%">
                <thead>
                  ${header}
                </thead>
                  ${tr_s.join("")}
                </table>
              </td>
          </tr>`;
    } else if (identifiers.length === 1) {
        const identifier = identifiers[0];
        const prefixed_header_trs = TableRow(
            parentIdentifier + "::" + identifier,
            true,
            identifier.split("-")[0]
        );

        return `<tr id="${trId}" style="display:none" class="inner-shadowed">
              <td colspan=${parent_number_of_keys + 2}>
                <table style="width:100%">
                  <tbody>
                      ${prefixed_header_trs}
                  </tbody>
                </table>
              </td>
            </tr>`;
    }
}
/**
 *
 * @returns {void} window.onload
 */
function main() {
    const home = document.getElementById("home");
    if (!home) return;
    fetching_flag[currentTab] = false;

    function doSomething(scrollPos) {
        if (fetching_flag[currentTab]) return;
        const lastChild = home.children[home.children.length - 1];
        if (!(lastChild instanceof HTMLElement)) return;
        if (
            scrollPos >
            lastChild.offsetTop +
            lastChild.offsetHeight -
            2 * document.body.clientHeight
        ) {
            const tbl = document.getElementById("MainTable");
            if (
                !(tbl && Object.keys(Model).some((key) => key.startsWith(currentTab)))
            )
                return;

            const min_id = Math.max(
                ...Object.keys(Model)
                    .filter((key) => key.startsWith(currentTab))
                    .map((key) => parseInt(key.split("-").pop()))
            );
            let data = {
                op: "get after",
                id: min_id,
            };
            fetching_flag[currentTab] = true;
            getFromHQ("read", currentTab, data, {
                success: (lst) => {
                    lst.forEach((identifier) => {
                        const row = TableRow(identifier);
                        var wrapper = document.createElement('div');
                        wrapper.innerHTML = '<table><thead><th>hi</th></thead><tbody>' + row + '</tbody></table>';
                        const DOM_rows = wrapper.querySelectorAll('div > table > tbody > tr');
                        DOM_rows.forEach(elem => {
                            tbl.appendChild(elem)
                        })
                    });
                    fetching_flag[currentTab] = false;
                },
                unclean: (response_text) => {
                    if (response_text === "that's all we have") {
                        console.log(response_text)
                    }
                }
            });
        }
    }
    let lastKnownScrollPosition = 0;
    let ticking = false;
    document.addEventListener("scroll", function (e) {
        lastKnownScrollPosition = window.scrollY;
        if (!ticking) {
            window.requestAnimationFrame(function () {
                doSomething(lastKnownScrollPosition);
                ticking = false;
            });
            ticking = true;
        }
    });
}
/**
 * @param {string} ApiEndPoint
 * @param {string} kind
 * @param {object} POST_PAYLOAD
 * @param {{success?: ((identifiers :string[]) => void),failure?: (reason: any) => PromiseLike<never>,unclean?:(response_text :string) => void}} callbackObject
 */
function getFromHQ(
    ApiEndPoint,
    kind,
    POST_PAYLOAD,
    callbackObject = { success: null, failure: null, unclean: null }
) {
    const { success, failure, unclean } = callbackObject;
    /**
     * @param {object} object
     */
    let clean_format = (object) => {
        Object.keys(object).forEach((key) => {
            if (
                key.endsWith("s") &&
                schemaClasses.includes(key.slice(0, -1)) &&
                object[key] instanceof Array
            ) {
                const subClassName = key.slice(0, -1);
                object[key] = object[key].map((subItem) => {
                    Model[subClassName + "-" + subItem.id] = clean_format(subItem);
                    return subClassName + "-" + subItem.id;
                });
            } else if (
                key.endsWith("_id") &&
                object[key.slice(0, -3)] instanceof Object
            ) {
                const subClassName = key.slice(0, -3);
                Model[subClassName + "-" + object[subClassName].id] = clean_format(
                    object[subClassName]
                );
                object[subClassName] = [subClassName + "-" + object[subClassName].id];
            }
        });
        return object;
    };
    try {
        fetch(URL + `Api/${ApiEndPoint}/${kind}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(POST_PAYLOAD),
        })
            .then((res) => {
                return res.text();
            })
            .then((answer) => {
                try {
                    if (unclean != null) unclean(answer);
                    const objects = JSON.parse(answer);
                    if (success != null) {
                        if (!(objects instanceof Array)) {
                            Swal.fire({
                                title: "There has been a problem!",
                                text: objects,
                                icon: "error",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                            });
                            return;
                        }
                        objects.forEach((object) => {
                            clean_format(object);
                        });
                        const identifiers = objects.map((object) => {
                            Model[kind + "-" + object.id] = object;
                            return kind + "-" + object.id;
                        });
                        success(identifiers);
                    }
                } catch (error) {
                    if (answer == "that's all we have") return;
                    if (answer.includes("You don't have access to")) {
                        Swal.fire("Sorry", answer.substring(answer.indexOf('You')), 'info')
                    }
                    // console.log(error);
                    if (!(error instanceof SyntaxError)) console.log(error);
                    console.log(answer);
                }
            })
            .catch(failure);
    } catch (error) {
        console.log(error);
    }
}
/**
 *
 * @param {string} Tab will be set to currentTab
 * @returns {void}
 */
function switchTo(Tab) {
    fetching_flag[currentTab] = false;
    try {
        if (currentTab === Tab) return;
        currentTab = Tab;
        document.getElementById("title").innerText =
            document.getElementById(Tab).innerText;
        const container = document.getElementById("JS-App-Root");
        container.innerHTML = "";

        setCookie("currentTab", Tab, 3);
        currentTab = Tab;

        if (Tab == "Dashboard") {
            console.log("DASH");
            if (!window.statistics) loadStats();
            console.log("window.statistics : ");
            console.log(window.statistics);
            viewStats();
            return;
        }

        Object.keys(Model)
            .filter((key) => key.startsWith(currentTab)).forEach(key => {
                delete Model[key]
            })

        let parentChartDiv = document.getElementById("chartContainer");
        if (parentChartDiv) parentChartDiv.innerHTML = "";

        getFromHQ(
            "read",
            currentTab,
            {},
            {
                success:
                    /**
                     *
                     * @param {string[]} lst
                     */
                    (lst) => {
                        container.innerHTML += MainTable(
                            currentTab,
                            Object.keys(Model[lst[0]])
                        );
                        let tbl = document.getElementById("MainTable");

                        lst.forEach((identifier) => {
                            const row = TableRow(identifier);
                            if (tbl) tbl.innerHTML += row;
                        });
                    },
                failure: (e) => {
                    //TODO: tell user some how that that's all
                    // console.log(e)
                },
            }
        );
        // const addBtn = document.createElement("div");
        const addBtn = `<div class="add-btn" onclick="add()">+</div>`;
        container.innerHTML += addBtn;
    } catch (error) {
        console.error(error);
    }
}

function editRow(identifier) {
    return (evt) => {
        var arr = [].slice.call(document.getElementById(identifier).children);
        let tic = arr.pop();
        let x = arr.pop();
        arr = arr.filter(is_not_unicode_sth).filter(t => t.className != 'profile-pic');
        if (tic.children[0].className.includes("fa-pencil")) {
            for (const child of arr) {
                child.contentEditable = true;
            }
            tic.children[0].className = tic.children[0].className.replace(
                "fa-pencil",
                "fa-check"
            );
            x.children[0].className = x.children[0].className.replace(
                "fa-trash",
                "fa-close"
            );
        } else {
            //Alert before procceed

            Swal.fire({
                title: "Are you sure you want to edit this row?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, modify it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = { ...Model[identifier] };
                    let sql_id = identifier.split("-").pop();
                    let header = Object.keys(data)
                        .filter(is_display_key)
                        .filter(t => t != 'profile_picture');
                    data["id"] = sql_id;

                    header.forEach((key, i) => {
                        if (!(data[key] instanceof Object)) data[key] = arr[i].innerText;
                    });

                    getFromHQ("update", identifier.split("-")[0], data, {
                        unclean: (res) => {
                            if (res == "updated") {
                                arr.forEach((tag) => {
                                    tag.contentEditable = false;
                                });
                                tic.children[0].className = tic.children[0].className.replace(
                                    "fa-check",
                                    "fa-pencil"
                                );
                                x.children[0].className = x.children[0].className.replace(
                                    "fa-close",
                                    "fa-trash"
                                );

                                Swal.fire(
                                    "Modified!",
                                    "Your changes have been saved.",
                                    "success"
                                );
                            } else if (res == "update unsuccessful") {
                                Swal.fire(
                                    "Was not Modified!",
                                    "Your changes have not been saved.",
                                    "error"
                                );
                                deleteRow(evt);
                            }
                        },
                    });
                } else {
                    deleteRow(evt);
                }
            });
        }
    };
}
/**
 *
 * @param {string} identifier
 * @param {string} old_part
 * @returns
 */
function cancel_sub_edit(identifier, old_part) {
    return (evt) => {
        const html_element = document.getElementById(identifier);
        html_element.innerHTML = old_part;
        set_OnClick_For_Id(edit_sub_Row(identifier), identifier + "-switcher");
    };
}
/**
 *
 * @param {string} identifier
 * @returns
 */
function edit_sub_Row(identifier) {
    return (evt) => {
        const model_identifier = identifier.split("::").pop();
        const field = model_identifier.split("-").shift();
        const html_element = document.getElementById(identifier);
        if (evt.target.className.includes("fa-pencil")) {
            const step1 = html_element.innerHTML.split("<i ");
            step1[0] = step1[0].substring(0, step1[0].lastIndexOf("<td>"));
            step1[1] = "<td><i " + step1[1];
            const first_part = step1[0];
            const second_part = step1[1];

            console.log(Model);

            getFromHQ(
                "read",
                field,
                {},
                {
                    success: (identifiers) => {
                        const SELECT_OPTIONS = identifiers.map(
                            (identifier) => Model[identifier]
                        );
                        html_element.innerHTML = `<td>
              ${select(
                            field + "_id",
                            SELECT_OPTIONS,
                            Model[model_identifier],
                            field
                        )} 
              </td>
              <td>
                <i class="fa fa-close" aria-hidden="true" id="${identifier}-cancel" ></i>
              </td>
              <td>
                <i class="fa fa-check" aria-hidden="true" id="${identifier}-save" ></i>
              </td>`;
                        set_OnClick_For_Id(
                            cancel_sub_edit(identifier, first_part + second_part),
                            identifier + "-cancel"
                        );
                        set_OnClick_For_Id(edit_sub_Row(identifier), identifier + "-save");
                    },
                }
            );
        } else {
            const tr = html_element;
            const v = tr.children[0].children[0].value;

            const ancestors = html_element.id.split("::");
            const [father_name, father_id] =
                ancestors[ancestors.length - 2].split("-");

            const fk_column = `${field}_id`;
            const payload = {
                id: father_id,
            };
            payload[fk_column] = v;
            getFromHQ("update", father_name, payload, {
                unclean: (res) => {
                    if (res == "updated") {
                        const id_lst = identifier.split("::");
                        const my_identifier = id_lst[id_lst.length - 1];
                        const my_name = my_identifier.split("-")[0];
                        document.getElementById(identifier).outerHTML = TableRow(
                            identifier,
                            true,
                            my_name
                        );
                    }
                },
            });

            // html_element.innerHTML = TableRow(field + '-' + v, true, field)
        }
    };
}
function deleteRow(evt) {
    if (evt.target.className.includes("fa-trash")) {
        if (!modifyMode) {
            deleteList = [];
            const target = document.getElementById("JS-App-Root");

            const modifyDiv = document.createElement("div");
            modifyDiv.id = "modify-div";

            const saveBtn = document.createElement("button");
            saveBtn.innerText = "Save Changes";
            saveBtn.className = "modify-btn";
            saveBtn.onclick = confirmChanges;
            const cancelBtn = document.createElement("button");
            cancelBtn.innerText = "Discard";
            cancelBtn.className = "modify-btn";
            cancelBtn.onclick = reset;

            modifyDiv.appendChild(saveBtn);
            modifyDiv.appendChild(cancelBtn);

            target.appendChild(modifyDiv);

            modifyMode = true;
        }

        let clickedRowId = evt.target.parentNode.parentNode.id;
        console.log("almost:", clickedRowId);
        let rowTable = clickedRowId.split("-")[0];
        let rowId = clickedRowId.split("-")[1];
        console.log(rowTable, rowId);
        deleteList.push({ table: rowTable, id: rowId, trId: clickedRowId });

        document.getElementById(clickedRowId).style.display = "none";
    } else {
        const id = evt.target.parentElement.parentElement.id; // direct tr parent
        var arr = [].slice.call(document.getElementById(id).children);
        let tic = arr.pop();
        let x = arr.pop();
        arr = arr.filter(is_not_unicode_sth)
            .filter(t => t.className != 'profile-pic')

        arr.forEach((tag) => {
            tag.contentEditable = false;
        });
        let data = Model[id];

        let header = Object.keys(data).filter(is_display_key).filter(t => t != 'profile_picture');
        header.forEach((key, i) => {
            if (!(data[key] instanceof Object)) arr[i].innerText = data[key];
        });

        tic.children[0].className = tic.children[0].className.replace(
            "fa-check",
            "fa-pencil"
        );
        x.children[0].className = x.children[0].className.replace(
            "fa-close",
            "fa-trash"
        );
    }
}

function reset() {
    for (const elem of deleteList) {
        const tr = document.getElementById(elem.trId);
        tr.style.display = "table-row";
    }

    deleteList = [];
    modifyMode = false;

    const modifyDiv = document.getElementById("modify-div");
    modifyDiv.parentNode.removeChild(modifyDiv);
}
/**
 *
 * @param {string[]} identifiers
 */
function delete_re(identifiers, depth = 0) {
    if (!identifiers || identifiers.length == 0) return;
    const delete_ids = identifiers.map((identifier) =>
        identifier.split("-").pop()
    );
    const payload = { ids: delete_ids };
    fetch(URL + `Api/delete/${identifiers[0].split("-")[0]}/`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
    })
        .then((res) => {
            return res.text();
        })
        .then((response_text) => {
            console.log(response_text);
            if (response_text.includes("NO ACTION")) {
                Swal.fire({
                    title: "Couldn't delete!",
                    // .map((identifier) => Model[identifier]['identifying_fields'].map(field=>Model[identifier][field]))
                    // .flatMap((obj) => obj.dependents.map((dep) => Model[dep]))
                    text: `${currentTab} was not alone! we need backup to delete his bloodline including " ${identifiers
                        .map((identifier) =>
                            Model[identifier]["dependents"]
                                .map((x) => x.toLowerCase() + "s")
                                .filter(
                                    (field) =>
                                        Model[identifier][field] != undefined &&
                                        Model[identifier][field].length > 0
                                )
                                .map((field) => {
                                    return (
                                        field.replaceAll("_", " ").replaceAll("-", " ") +
                                        " [ " +
                                        Model[identifier][field]
                                            .map((sub_identifier) =>
                                                Model[sub_identifier]["identifying_fields"]
                                                    .map((id_field) => Model[sub_identifier][id_field])
                                                    .join("& ")
                                            )
                                            .join(" ") +
                                        " ] "
                                    );
                                })
                                .join(", ")
                        )
                        .join(" And ")} "`,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, do whatever's necessary!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        identifiers
                            .map((identifier) => Model[identifier])
                            .forEach((item, index) => {
                                Object.keys(item).forEach((key) => {
                                    if (
                                        item[key] instanceof Array &&
                                        item[key].length > 0 &&
                                        item[key][0].split("-").length == 2
                                    ) {
                                        const identifiers = item[key];
                                        delete_re(identifiers, depth + 1);
                                    }
                                });
                                // delete parent
                                delete_re([identifiers[index]]);
                            });
                    } else {
                        reset();
                    }
                })
            } else {
                if (document.getElementById("modify-div")) {
                    document.getElementById("modify-div").remove();
                    delete_ids.forEach((delete_id) => {
                        delete Model[currentTab + "-" + delete_id];
                    });
                }
            }
        });
}
function confirmChanges() {
    Swal.fire({
        title: "Are you sure you want to save your changes?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            const delete_ids = deleteList
                .map((elem) => elem.trId)
                .map((identifier) => identifier.split("::").pop());
            delete_re(delete_ids);



            Swal.fire("Deleted!", "Your choices has been deleted.", "success");
            // call delete api here ... right?
            console.log("deleteList : ");
            console.log(deleteList);



            modifyMode = false;
        }
    });
}

function add() {
    if (modifyMode) {
        var answer = window.confirm(
            "You have unsaved changes. Are you sure you want to leave this page?"
        );
        if (!answer) return;
    }
    let newTab = currentTab.toLowerCase();
    newTab = newTab.split("_");
    console.log(newTab);
    let newTabStr = "";
    for (const part of newTab) {
        newTabStr += part.charAt(0).toUpperCase() + part.slice(1) + "_";
    }
    newTabStr = newTabStr.slice(0, -1);
    if (currentTab == 'user')
        window.location = ourURL + "users/signup/" + newTabStr;
    else
        window.location = ourURL + "DashBoard/add/" + newTabStr;
}

function loadStats() {
    var url = URL + "Api/getStats";
    console.log(url);
    var reply = {};

    fetch(url, {
        method: "POST",
        body: JSON.stringify(reply),
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((res) => res.json())
        .then((response) => success(response))
        .catch((error) => failure(error));

    function success(json) {
        console.log("json : ");
        console.log(json);
        // console.log("AFTER: " + JSON.stringify(json));
        window.statistics = cleanStats(json);
        console.log("window.statistics : ");
        console.log(window.statistics);
        if (currentTab == "Dashboard") {
            viewStats();
        }
    }

    function failure(error) {
        console.log("ERROR: " + error);
    }
}

function cleanStats(data) {
    for (const key in data) {
        if (Object.hasOwnProperty.call(data, key)) {
            let oldKey = key;
            let newKey = null;
            switch (oldKey) {
                case "Api.create":
                    newKey = "Database Add Request";
                    break;
                case "Api.read":
                    newKey = "Database Read Request";
                    break;
                case "Api.update":
                    newKey = "Database Update Request";
                    break;
                case "Users.logout":
                    newKey = "Logout Request";
                    break;
                case "Users.validate":
                    newKey = "Login Request";
                    break;
                case "dashboard.Add":
                    newKey = "Add Form Visit";
                    break;
                case "dashboard.index":
                    newKey = "Dashboard Visit";
                    break;
                case "home.index":
                    newKey = "Homepage Visit";
                    break;

                default:
                    newKey = oldKey;
                    break;
            }

            if (newKey) {
                delete Object.assign(data, { [newKey]: data[oldKey] })[oldKey];
            }
        }
    }
    return data;
}

function viewStats() {
    let viewedData = [];

    console.log("window.statistics : ");
    console.log(window.statistics);

    for (const key in window.statistics) {
        if (Object.hasOwnProperty.call(window.statistics, key)) {
            viewedData.push({ y: window.statistics[key], name: key });
        }
    }

    console.log("viewedData : ");
    console.log(viewedData);

    var chart = new CanvasJS.Chart("chartContainer", {
        theme: "light1",
        backgroundColor: "#e4e9f7",
        exportFileName: "Website Statistics",
        exportEnabled: false,
        animationEnabled: true,
        title: {
            text: "Website Statistics",
        },
        legend: {
            cursor: "pointer",
            itemclick: explodePie,
        },
        data: [
            {
                type: "doughnut",
                innerRadius: 90,
                showInLegend: true,
                toolTipContent: "<b>{name}</b>: {y} Hits (#percent%)",
                indexLabel: "{name} - #percent%",
                dataPoints: viewedData,
            },
        ],
    });
    chart.render();

    function explodePie(e) {
        if (
            typeof e.dataSeries.dataPoints[e.dataPointIndex].exploded ===
            "undefined" ||
            !e.dataSeries.dataPoints[e.dataPointIndex].exploded
        ) {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
        } else {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
        }
        e.chart.render();
    }
}

addLoadEvent(main);
