var AllFetchedRows = [];

function tableCreateIn_for_(container, header) {
  let tbl = document.createElement("table");
  tbl.className = "table";
  let tr = tbl.insertRow();
  tr.innerHTML = header
    .filter((x) => x.substring(x.length - 2) !== "id")
    .map((v) => "<th>" + v + "</th>")
    .join("");
  container.appendChild(tbl);
  return tbl;
}
function expand2(id_to_toggle) {
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
function pureHeader(names) {
  let tr = document.createElement("tr");
  tr.innerHTML = names
    .filter((x) => x.substring(x.length - 2) !== "id")
    .map((v) => "<th>" + v + "</th>")
    .join("");
  return tr;
}
function pureRows(row, id) {
  const answer = [];
  let tr = document.createElement("tr");
  answer.push(tr);
  // let rows = arr[Tab]
  // if (!rows.map)
  // rows = [rows]
  // let row = rows[ind]
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
        tmp.onclick = expand2(key + "-" + id + "-" + subrows.length);
        subrows.push(key + "-" + id + "-" + subrows.length);
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

  contained_table.appendChild(pureHeader(Object.keys(objs[0])));

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

      fetch(URL + `Api/read/${window.currentTab}/`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
        .then((res) => {
          return res.json();
        })
        .then((lst) => {
          if (lst.length === 0) return;
          for (let index = 0; index < lst.length; index++) {
            const element = lst[index];
            if (AllFetchedRows[window.currentTab])
              AllFetchedRows[window.currentTab].push(element);
            else AllFetchedRows[window.currentTab] = [element];
            const prows = pureRows(element, index);
            prows.forEach((row) => tbl.appendChild(row));
          }
          fetching_flag = false;
        })
        .catch((e) => {
          // just keep the fetching_flag off
          // console.log(e)
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
function switchTo(Tab) {
  if (window.currentTab === Tab) return;
  window.currentTab = Tab;
  document.getElementById("title").innerText =
    document.getElementById(Tab).innerText;
  const container = document.getElementById("TTTarget");
  container.innerHTML = "";
  fetch(URL + `Api/read/${Tab}/`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((res) => {
      // console.log(res)
      return res.json();
    })
    .then((lst) => {
      // console.log(lst)
      if (lst.length === 0) return;
      const tbl = tableCreateIn_for_(container, Object.keys(lst[0]));
      for (let index = 0; index < lst.length; index++) {
        const element = lst[index];
        if (AllFetchedRows[window.currentTab])
          AllFetchedRows[window.currentTab].push(element);
        else AllFetchedRows[window.currentTab] = [element];
        const prows = pureRows(element, index);
        prows.forEach((row) => tbl.appendChild(row));
      }
    })
    .catch((e) => {
      //TODO: tell user some how that that's all
      // console.log(e)
    });
}
window.onload = main;
