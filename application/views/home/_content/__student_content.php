<script src="<?= URL ?>public/js/exams_fetch.js"></script>
<script>
    function startExam() {
        selects = document.getElementsByTagName("select")
        window.location = `<?= URL ?>exams/startExam?data=${selects[0].selectedIndex}-${selects[1].selectedIndex}`
    }

    function viewResult() {
        grade = <?= isset($_SESSION['examGrade']) ? round($_SESSION['examGrade'], 2) . ";" : "null;" ?>
        msgText = "Your exam has finished. Score =  " + grade + "/100.\nWould You like to review your answers?"

        Swal.fire({
            title: "Exam Finished",
            text: msgText,
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Review Exam",
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = "<?= URL ?>exams/reviewExam";
            } else {
                window.location = "<?= URL ?>exams/unsetExam";
            }
        });
    }

    function alertExam() {
        Swal.fire({
            title: "Start Exam?",
            text: "You cant go back once you start your exam. Are you sure you want to continue?",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Start Exam",
        }).then((result) => {
            if (result.isConfirmed) {
                startExam();
            } else {
                return;
            }
        });
    }

    if (<?php echo (isset($_GET['exam_finished']) && isset($_SESSION['examGrade'])) ? ("true") : "false" ?>) {
        viewResult();
    }
    if (<?php echo (isset($_GET['no_exams']) && $_GET['no_exams']) ? ("true") : "false" ?>) {
        failure("No exams available");
    }
    if (<?php echo (isset($_GET['exam_saved']) && $_GET['exam_saved']) ? ("true") : "false" ?>) {
        success("Exam info saved");
    }
</script>
<div class="user-content">
    <h2>Take an exam:</h2>

    <select name="exam" id="exams-list" class="form-select">
        <option id="exam-default" value="" disabled selected></option>
    </select>

    <select name="exam-center" id="exam-centers-list" class="form-select" style="margin-top : 0.5em;">
        <option id="exam-center-default" value="" disabled selected></option>
    </select>

    <div class="form-block">
        <button type="submit" onclick="alertExam()">Take Exam</button>
    </div>
</div>