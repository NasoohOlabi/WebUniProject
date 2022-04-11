function success() {
  Swal.fire({
    position: "top-end",
    icon: "success",
    title: `Opreation Done Successfully`,
    showConfirmButton: false,
    timer: 1500,
  });
}

function failure(msg) {
  Swal.fire({
    position: "top-end",
    icon: "error",
    title: msg,
    showConfirmButton: false,
    timer: 1500,
  });
}

const payload = {
  limit: 1e9,
};

try {
  fetch(`./Api/read/exam`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  })
    .then((response) => response.text())
    .then((txt) => {
      // console.log(txt);
      try {
        let exams_list = JSON.parse(txt);
        // console.log("exams fetched");
        // console.log(exams_list[0]['subject']['name']);

        const select = document.getElementById("exams-list");

        var def_text = select.length
          ? "Select an exam"
          : "You don't have any exams to take";

        document.getElementById("exam-default").innerText = def_text;

        for (const exam of exams_list) {
          const name = exam["subject"]["name"];
          select.options[select.options.length] = new Option(name, name);
          select.options[select.options.length - 1].id = exam["id"];
        }
      } catch (error) {
        console.log("so close");
        console.log(error);
      }
    });
} catch (error) {
  console.log("ops!");
  console.log(error);
}

try {
  fetch(`./Api/read/exam_center`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  })
    .then((response) => response.text())
    .then((txt) => {
      // console.log(txt);
      try {
        let exam_centers_list = JSON.parse(txt);
        // console.log("exams centers fetched");
        // console.log(exam_centers_list[0]);

        const select = document.getElementById("exam-centers-list");

        var def_text = select.length
          ? "Select an exam center"
          : "There are no exam centers available";

        document.getElementById("exam-center-default").innerText = def_text;

        for (const exam_center of exam_centers_list) {
          const name = exam_center["name"];
          select.options[select.options.length] = new Option(name, name);
          select.options[select.options.length - 1].id = exam_center["id"];
        }
      } catch (error) {
        console.log("so close");
        console.log(error);
      }
    });
} catch (error) {
  console.log("ops!");
  console.log(error);
}

window.onload = initialize();

//window.setTimeout(initialize, 1000);

function initialize() {
  window.exams_list = document.getElementById("exams-list");
  window.exam_centers_list = document.getElementById("exam-centers-list");

  while (!exams_list || !exam_centers_list) {
    window.setTimeout(initialize, 500);
    return;
  }

  window.numOfExams = window.exams_list.children.length - 1;
  window.numOfCenters = window.exam_centers_list.children.length - 1;

  if (window.numOfExams && window.numOfCenters) {
    exams_list.addEventListener("change", validate);
    exam_centers_list.addEventListener("change", validate);
  }

  if (window.fire_success) success();

  validate();
}

function validate() {
  const Submitbtn = document.querySelector("[type='submit']");
  let selected_exam = exams_list.selectedIndex;
  let selected_center = exam_centers_list.selectedIndex;

  if (selected_exam && selected_center) {
    Submitbtn.disabled = false;
    Submitbtn.style.cursor = "pointer";
  } else {
    Submitbtn.disabled = true;
    Submitbtn.style.cursor = "not-allowed";
  }
}
