<div class="question-main-content">
    <div class="question-container">
        <div class="question-content">
            <h1 class="question-header">Question #<?= $question_index + 1 ?> <span>(<?= round($_SESSION['MarksPerQuestion'], 2) ?> marks)</span></h1>
            <h3 class="question-text"><?= $curQuestionInfo->text ?></h3>
            <form action="<?= URL ?>exams/nextquestion" method="post" class=question-form>

                <?php
                foreach ($curQuestionInfo->choices as $key => $value) {

                    $question_id = $curQuestionInfo->id;
                    $choice_id = $value->id;
                    $choice_text = $value->text;
                    $selected = ($studentChoice && $studentChoice == $choice_id) ? true : false;
                    $is_correct = $reviewMode ? $value->is_correct : false;
                    require 'application/views/exams/_choice_template.php';
                }
                ?>
                <div class="form-block exam-nav-container">
                    <button type="submit" class="exam-nav-btn">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>