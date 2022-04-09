<script src="<?= URL ?>public/js/exams_fetch.js"></script>
<script>
    function generateExam() {
        selects = document.getElementsByTagName("select")
        window.location = `<?= URL ?>exams/generateExam?data=${selects[0].selectedIndex}-${selects[1].selectedIndex}`
    }

    if (<?php echo (isset($_GET['op_success']) && $_GET['op_success']) ? ("true") : "false" ?>) {
        success();
    }

    if (<?php echo (isset($_GET['op_success']) && !$_GET['op_success']) ? ("true") : "false" ?>) {
        failure();
    }
</script>
<div class="user-content">
    <h2>Generate Random Questions:</h2>

    <select name="exam" id="exams-list" class="form-select">
        <option id="exam-default" value="" disabled selected></option>
    </select>

    <select name="exam-center" id="exam-centers-list" class="form-select" style="margin-top : 0.5em;">
        <option id="exam-center-default" value="" disabled selected></option>
    </select>

    <div class="form-block">
        <button type="submit" onclick="generateExam()">Generate</button>
    </div>
</div>