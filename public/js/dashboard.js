var AllFetchedRows = [];
var deleteList = [];
var modifyMode = false;

function tableCreateIn_for_(container, id, header) {
  let tbl = document.createElement("table");
  tbl.className = "table";
  tbl.appendChild(pureHeader(id, header));
  container.appendChild(tbl);
  return tbl;
}

function eventHandler(id_to_toggle) {
  let dropped_down = false;
  let tr = document.getElementById(id_to_toggle);
  let btn = document.getElementById(id_to_toggle + "-" + "btn");
  return function (event) {
    if (tr == null || btn == null) {
      tr = document.getElementById(id_to_toggle);
      btn = document.getElementById(id_to_toggle + "-" + "btn");
    }
    dropped_down = !dropped_down;
    btn.innerText = !dropped_down ? "🔽" : "🔼";
    tr.style.display = !dropped_down ? "none" : "table-row";
  };
}

function pureHeader(id, names) {
  let tr = document.createElement("tr");
  tr.innerHTML = names
    .filter((x) => x.substring(x.length - 2) !== "id")
    .map((v) => "<th>" + v + "</th>")
    .join("");
  tr.id = id;
  return tr;
}

function pureRows(row, id) {
  const answer = [];
  let tr = document.createElement("tr");
  answer.push(tr);
  let subrows = [];
  const number_of_keys = Object.keys(row).filter(
    (x) => x.substring(x.length - 2) !== "id"
  ).length;
  for (const key in row) {
    if (Object.hasOwnProperty.call(row, key)) {
      const value = row[key];
      if (value instanceof Object) {
        let td = tr.insertCell();
        let tmp = document.createElement("button");
        tmp.innerText = "🔽";
        tmp.id = key + "-" + id + "-" + subrows.length + "-" + "btn";
        tmp.onclick = eventHandler(key + "-" + id + "-" + subrows.length);
        subrows.push(key.replace(" ", "-") + "-" + id + "-" + subrows.length);
        tmp.style =
          "background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;";
        td.appendChild(tmp);
      } else {
        if (key == `is_correct`) {
          let td = tr.insertCell();
          let v = value ? "✔" : "❌";
          td.appendChild(document.createTextNode(v));
        } else if (key.substring(key.length - 2) !== "id") {
          let td = tr.insertCell();
          td.appendChild(document.createTextNode(value));
        } else if (key == "id") {
          tr.id = window.currentTab + "-" + value;
        }
      }
    }
  }
  subrows
    .map((identifier) => {
      const key = identifier.split("-")[0];
      const res = pureSubTable(
        row[key].length && row[key].length > 1 ? row[key] : [row[key]],
        identifier,
        number_of_keys
      );
      return res;
    })
    .forEach((table_tr) => {
      answer.push(table_tr);
    });
  trashbtn = document.createElement("i");
  trashbtn.className = "fa fa-trash";
  trashbtn.ariaHidden = "true";
  let td_trash = tr.insertCell();
  td_trash.appendChild(trashbtn);
  trashbtn.addEventListener("click", deleteRow);
  edit_btn = document.createElement("i");
  edit_btn.className = "fa fa-pencil";
  edit_btn.ariaHidden = "true";
  let td_edit = tr.insertCell();
  td_edit.appendChild(edit_btn);
  edit_btn.addEventListener("click", editRow(row, tr.id));
  return answer;
}

function pureSubTable(objs, trId, parent_number_of_keys) {
  if (parent_number_of_keys === 0) return;
  let tr = document.createElement("tr");
  tr.id = trId;
  tr.style.display = "none";
  if (objs.length === 0) return;
  const container_td = tr.insertCell();
  container_td.colSpan = parent_number_of_keys;
  const contained_table = document.createElement("table");
  container_td.appendChild(contained_table);
  let tbl_name = trId.split("-")[1];
  contained_table.appendChild(pureHeader(tbl_name, Object.keys(objs[0])));

  objs
    .map((obj, index) => pureRows(obj, index))
    .forEach((trs) => {
      trs.forEach((tr) => {
        contained_table.appendChild(tr);
      });
    });

  return tr;
}

function main() {
  const home = document.getElementById("home");
  if (!home) return;
  window.currentTab = "User";
  let fetching_flag = false;

  function doSomething(scrollPos) {
    if (fetching_flag) return;
    const lastChild = home.children[home.children.length - 1];
    if (
      scrollPos >
      lastChild.offsetTop +
        lastChild.offsetHeight -
        document.body.clientHeight -
        100
    ) {
      const tbl = document.getElementsByClassName("table")[0];
      if (
        !(
          tbl &&
          AllFetchedRows[window.currentTab] &&
          AllFetchedRows[window.currentTab].length
        )
      )
        return;
      let data = {
        op: "get after",
        id: AllFetchedRows[window.currentTab][
          AllFetchedRows[window.currentTab].length - 1
        ]["id"],
      };
      fetching_flag = true;
      getFromHQ(
        data,
        (lst) => {
          for (let index = 0; index < lst.length; index++) {
            const element = lst[index];
            if (AllFetchedRows[window.currentTab])
              AllFetchedRows[window.currentTab].push(element);
            else AllFetchedRows[window.currentTab] = [element];
            const prows = pureRows(element, index);
            prows.forEach((row) => tbl.appendChild(row));
          }
          fetching_flag = false;
        },
        (e) => {
          // just keep the fetching_flag off
          // console.log(e)
        }
      );
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

function getFromHQ(POST_PAYLOAD, success, failure) {
  fetch(URL + `Api/read/${window.currentTab}/`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(POST_PAYLOAD),
  })
    .then((res) => {
      return res.json();
    })
    .then((lst) => {
      // console.log(lst);
      if (!(lst instanceof Array) || lst.length === 0) return;
      // console.log(lst);
      // lst = lst.map((row) => {
      //   if (!(row instanceof Object)) return;
      //   for (const field in row) {
      //     if (!Object.hasOwnProperty.call(row, field)) return;
      //     const attribute = row[field];
      //     if (attribute instanceof Array) continue;
      //     else if (attribute instanceof Object) {
      //       for (const attribute_key in attribute) {
      //         if (!Object.hasOwnProperty.call(attribute, attribute_key)) return;
      //         const attribute_attribute = attribute[attribute_key];
      //         row[field + " " + attribute_key] = attribute_attribute;
      //       }
      //       delete row[field];
      //     }
      //   }
      //   return row;
      // });
      // console.log(lst);
      success(lst);
    })
    .catch(failure);
}

function switchTo(Tab) {
  if (window.currentTab === Tab) return;
  window.currentTab = Tab;
  document.getElementById("title").innerText =
    document.getElementById(Tab).innerText;
  const container = document.getElementById("TTTarget");
  container.innerHTML = "";

  getFromHQ(
    {},
    (lst) => {
      const tbl = tableCreateIn_for_(
        container,
        window.currentTab,
        Object.keys(lst[0])
      );
      for (let index = 0; index < lst.length; index++) {
        const element = lst[index];
        if (AllFetchedRows[window.currentTab])
          AllFetchedRows[window.currentTab].push(element);
        else AllFetchedRows[window.currentTab] = [element];
        const prows = pureRows(element, index);
        prows.forEach((row) => tbl.appendChild(row));
      }
    },
    (e) => {
      //TODO: tell user some how that that's all
      // console.log(e)
    }
  );
  const addBtn = document.createElement("div");
  addBtn.className = "add-btn";
  addBtn.innerText = "+";
  addBtn.onclick = add;
  container.appendChild(addBtn);
}
function editRow(row, id) {
  return (evt) => {
    var arr = [].slice.call(document.getElementById(id).children);
    let tic = arr.pop();
    let x = arr.pop();
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
      let data = row;
      let sql_id = id.split("-").pop();
      let header = [].slice.call(
        evt.currentTarget.parentElement.parentElement.parentElement.children[0]
          .children
      );
      header = header.map((e) => e.innerText);
      // header.forEach(e => {
      //   console.log(e)
      // })
      // arr.forEach(e => {
      //   console.log(e.innerText)
      // })
      // console.log(row)
      data["id"] = sql_id;
      header.forEach((key, i) => {
        data[key] = arr[i].innerText;
      });

      console.log(data);

      fetch(URL + `Api/update/${window.currentTab}/`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }).then((res) => {
        console.log(res);
        return res.json();
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
  };
}
function deleteRow(evt) {
  if (!modifyMode) {
    deleteList = [];
    const target = document.getElementById("TTTarget");

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
  let rowTable = clickedRowId.split(" ")[0];
  let rowId = clickedRowId.split(" ")[1];
  //console.log(rowTable, rowId);
  deleteList.push({ table: rowTable, id: rowId, trId: clickedRowId });

  document.getElementById(clickedRowId).style.display = "none";
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

function confirmChanges() {
  var answer = window.confirm("Are you sure you want to save changes?");
  if (!answer) return;
}

function add() {
  if (modifyMode) {
    var answer = window.confirm(
      "You have unsaved changes. Are you sure you want to leave this page?"
    );
    if (!answer) return;
  }
  let newTab = window.currentTab.toLowerCase();
  newTab = newTab.split("_");
  console.log(newTab);
  let newTabStr = "";
  for (const part of newTab) {
    newTabStr += part.charAt(0).toUpperCase() + part.slice(1) + "_";
  }
  newTabStr = newTabStr.slice(0, -1);
  window.location = URL + "DashBoard/add/" + newTabStr;
}

window.onload = main;
