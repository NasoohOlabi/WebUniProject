var AllFetchedRows = [];
var deleteList = [];
var modifyMode = false;
var statistics = null;

function UpperCaseFirstLetter(str, separator = " ") {
  const arr = str.split(separator);

  const upperCasedWords = arr.map(word => {
    return word.charAt(0).toUpperCase() + word.slice(1)
  })

  return upperCasedWords.join(separator);
}
function select(input_name, SELECT_OPTIONS) {
  const objects = SELECT_OPTIONS;
  const identifying_string = (o) => {
    const arr = (o.identifying_fields) ?
      o.identifying_fields.map(field => o[field])
      :
      Object.values(o).filter(v => !(v instanceof Object))

    return JSON.stringify(arr).replaceAll('[', '').replaceAll('"', '').replaceAll(']', '').replaceAll(',', ' ')
  }
  return `<select name="${input_name}" class="form-select valid-input" aria-label="${place_holder}">
        <option value="" disabled selected hidden>
            ${place_holder}
        </option>
        ${objects.map(object => `<option value="${object.id}">${identifying_string(object)}</option>`)}
        ?>
    </select>`
}
function MainTable(id, header) {
  return `<table class="table" id="MainTable">
            <thead>
            ${Header(id, header)}
            </thead>
          </table>`
}
function is_display_key(key) {
  return !key.endsWith('id') && key !== 'identifying_fields' && key !== 'profile_picture'
}
function is_not_unicode_sth(elem) {
  return !(elem.children[0] && ['🔽', '🔼'].includes(elem.children[0].innerText))
}
function toggleDropDown(id_to_toggle) {
  let dropped_down = false;
  let tr = document.getElementById(id_to_toggle);
  let btn = document.getElementById(id_to_toggle + "-" + "btn");
  btn.onclick = function () {
    if (!(tr && btn)) {
      tr = document.getElementById(id_to_toggle);
      btn = document.getElementById(id_to_toggle + "-" + "btn");
    }
    dropped_down = !dropped_down;
    btn.value = !dropped_down ? "🔽" : "🔼"
    tr.style.display = !dropped_down ? "none" : "table-row";
  };
  btn.onclick();
}
function Header(id, names) {
  const th_s = names
    .filter(is_display_key)
    .map((v) => "<th>" + v + "</th>")
    .join("")
  return `<tr id ="${id}">${th_s}</tr>`;
}
function TableRow(row_item, row_number, inline_keys = false, inline_key_prefix = '') {
  let number_of_display_columns = Object.keys(row_item).filter(
    is_display_key
  ).length;
  if (inline_keys) number_of_display_columns *= 2
  const subTables = []
  let td_s = Object.keys(row_item)
    .filter(is_display_key)
    .map(key => {
      const value = row_item[key];
      let td = (inline_keys) ? `<td><strong>${inline_key_prefix
        } ${key}:</strong></td>` : "";
      if (value instanceof Object) {
        // just making sure key is a single word
        // key.replace(' ', '-') 
        // since key is a field in the object this row represent
        const id_of_tr_this_btn_will_expand = key.replace(' ', '-') + "-" + row_number + "-" + subTables.length
        subTables.push(id_of_tr_this_btn_will_expand);
        td += `<td>
        <button
          id="${id_of_tr_this_btn_will_expand}-btn" 
          style="background: none;color: inherit;border: none;padding: 0;font: inherit;cursor: pointer;outline: inherit;"
          onclick="toggleDropDown('${id_of_tr_this_btn_will_expand}')">
            🔽
          </button>
        </td>`
      } else if (key == `is_correct`) {
        td += `<td>${value ? "✔" : "❌"}</td>`
      } else {
        td += `<td>${value}</td>`;
      }

      return td;
    })

  const td_text = td_s.join('')
  // trashbtn.addEventListener("click", deleteRow);

  // edit_btn.addEventListener("click", editRow(row, tr.id));
  const trId = window.currentTab + "-" + row_item['id']
  const tr = `<tr id="${trId}">${td_text}
    <td>
      <i class="fa fa-trash" aria-hidden="true" onclick="deleteRow(event)" id="${trId}-left"></i>
    </td>
    <td>
      <i class="fa fa-pencil" aria-hidden="true" onclick="editRow('${window.currentTab}',${row_number}, '${trId}')(event)" id="${trId}-right"></i>
    </td>
    </tr>`
  const subTablesWrappedInTr_s = subTables
    .map((identifier) => {
      const key = identifier.split("-")[0];
      const res = subTable_tr(
        row_item[key].length && row_item[key].length > 1 ? row_item[key] : [row_item[key]],
        identifier,
        number_of_display_columns
      );
      return res;
    })
    .join('\n')
  return `${tr}\n${subTablesWrappedInTr_s}`
}
function subTable_tr(objs, trId, parent_number_of_keys) {
  if (parent_number_of_keys === 0) return;
  if (objs.length === 0) return;

  if (objs.length === 1) {
    const prefixed_header_trs = TableRow(objs[0], 0, true, trId.split('-')[0])

    return `<tr id="${trId}" style="display:none">
              <td colspan=${parent_number_of_keys}>
                <table style="width:100%">
                  <tbody>
                      ${prefixed_header_trs}
                  </tbody>
                </table>
              </td>
            </tr>`
  }
  const tr_s = objs
    .map((obj, index) => TableRow(obj, index))
  return `<tr id="${trId}" style="display:none">
              <td colspan=${parent_number_of_keys}>
                <table style="width:100%">
                  ${Header(trId.split('-')[1], Object.keys(objs[0]))}
                  ${tr_s.join('')}
                </table>
              </td>
          </tr>`;
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
            const prows = TableRow(element, index);
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
  window.currentTab = "";
  switchTo("Dashboard");
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
      if (!(lst instanceof Array) || lst.length === 0) return;
      success(lst);
    })
    .catch(failure);
}

function switchTo(Tab) {
  try {
    if (window.currentTab === Tab) return;
    window.currentTab = Tab;
    document.getElementById("title").innerText =
      document.getElementById(Tab).innerText;
    const container = document.getElementById("TTTarget");
    container.innerHTML = "";
    if (Tab == "Dashboard") {
      console.log("DASH");
      if (!window.statistics) loadStats();
      console.log('window.statistics : ');
      console.log(window.statistics);
      viewStats();
      return;
    }

    let parentChartDiv = document.getElementById("chartContainer");
    if (parentChartDiv)
      parentChartDiv.innerHTML = "";

    getFromHQ(
      {},
      (lst) => {
        container.innerHTML += MainTable(window.currentTab, Object.keys(lst[0]))
        const tbl = document.getElementById('MainTable')
        for (let index = 0; index < lst.length; index++) {
          const element = lst[index];
          if (AllFetchedRows[window.currentTab])
            AllFetchedRows[window.currentTab].push(element);
          else AllFetchedRows[window.currentTab] = [element];
          const row = TableRow(element, index);
          tbl.innerHTML += row
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

  } catch (error) {
    console.error(error)
  }
}
function editRow(tableName, row_number, id) {
  return (evt) => {
    var arr = [].slice.call(document.getElementById(id).children);
    let tic = arr.pop();
    let x = arr.pop();
    console.log('arr : ');
    console.log(arr);
    arr = arr.filter(is_not_unicode_sth)
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
      let data = AllFetchedRows[tableName][row_number];
      let sql_id = id.split("-").pop();
      let header = Object.keys(data).filter(is_display_key)
      data["id"] = sql_id;
      header.forEach((key, i) => {
        data[key] = arr[i].innerText;
      });


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
  if (evt.target.className.includes("fa-trash")) {
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
  } else {
    const id = evt.target.parentElement.parentElement.id
    var arr = [].slice.call(document.getElementById(id).children);
    let tic = arr.pop();
    let x = arr.pop();
    arr = arr.filter(is_not_unicode_sth)
    arr.forEach(tag => {
      tag.contentEditable = false
    })
    let data = AllFetchedRows[id.split('-')[0]];
    for (let i = 0; i < data.length; i++) {
      const row = data[i]
      if (row.id == id.split("-")[1]) {
        data = row
        break
      }
    }
    let header = Object.keys(data).filter(is_display_key)
    header.forEach((key, i) => {
      arr[i].innerText = data[key];
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
    console.log('json : ');
    console.log(json);
    // console.log("AFTER: " + JSON.stringify(json));
    window.statistics = cleanStats(json);
    console.log('window.statistics : ');
    console.log(window.statistics);
    if (window.currentTab == "Dashboard") {
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
          newKey = oldKey
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

  console.log('window.statistics : ');
  console.log(window.statistics);

  for (const key in window.statistics) {
    if (Object.hasOwnProperty.call(window.statistics, key)) {
      viewedData.push({ y: window.statistics[key], name: key });
    }
  }

  console.log('viewedData : ');
  console.log(viewedData);

  var chart = new CanvasJS.Chart("chartContainer", {
    theme: "dark2",
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

window.onload = main;
