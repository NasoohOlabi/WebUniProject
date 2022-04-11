<?php
require_once 'application/views/_templates/header.php';
require_once 'application/views/_templates/schema_table.php';

class Exams extends Controller
{
    public function index($question_index)
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $exam_model = $this->loadModel('ExamModel');

        if (!$exam_model->inExam()) {
            http_response_code(403);
            return;
        }

        if ($question_index != 0 && (!isset($_SESSION['curQuestionIndex']) || $_SESSION['curQuestionIndex'] != $question_index - 1)) {
            http_response_code(404);
            return;
        }

        if ($question_index == sizeof($_SESSION['ExamQuestions'])) {
            $this->endExam();
            return;
        }

        if ($question_index > sizeof($_SESSION['ExamQuestions'])) {
            http_response_code(404);
            return;
        }

        $curQuestion = $_SESSION['ExamQuestions'][$question_index];
        $_SESSION['curQuestionIndex'] = $question_index;
        $curQuestionInfo = $exam_model->select([], 'question', [Question::id => $curQuestion->question_id])[0];
        $curQuestionInfo->choices = $exam_model->select([], 'choice', [Choice::question_id => $curQuestion->question_id]);
        $reviewMode = (isset($_SESSION['reviewExam']) && $_SESSION['reviewExam']) ? true : false;
        $studentChoice = $reviewMode ? intval(explode('-', $_SESSION['studentChoices'][$question_index])[1]) : null;
        $endTime = $_SESSION['endTime'];
        $endTime = str_replace("CES", "", $endTime);

        pageHeadTag("Exam");
        require 'application/views/_templates/user_navbar.php';
        require 'application/views/exams/index.php';
        pageHit("Exam.index");
    }

    /**
     * this test should only generate 27 new unique
     * exams base on the provided demo data
     */
    public function test()
    {
        $exam = new stdClass();
        $exam->id = 1;
        $exam->number_of_questions = 3;
        $exam->duration = 60;
        $exam->subject_id = 2;
        $exam = new Exam($exam);

        $exam_center = new stdClass();
        $exam_center->id = 1;
        $exam_center->name = "Hiast Center";
        $exam_center->description = 'A center in the hiast...';
        $exam_center->user_id = 31;
        $exam_center = new Exam_Center($exam_center);


        $exam_model = $this->loadModel('ExamModel');

        $student_exam = $exam_model->InsertOneOfAKind_student_exam($exam, $exam_center);

        print("<pre>");
        print('Generated Successfully! ');
        print("student_exam\n");
        var_dump($student_exam);
        print("</pre>");
    }

    public function startExam()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_GET['data']))
            return;

        $ids = explode('-', $_GET['data']);

        $em = $this->loadModel('ExamModel');

        if (!$em->getById($ids[0]) || !$em->getById($ids[1], 'Exam_Center')) {
            http_response_code(404);
            return;
        }

        simpleLog("Attempting to find an exam...");

        try {
            $em->loadExam(intval($ids[0]), intval($ids[1]));
        } catch (\Throwable $th) {
            header('Location: ' . URL . "index?no_exams=true");
            return;
        }

        header('Location: ' . URL . 'exams/index/0');
    }

    public function endExam()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $exam_model = $this->loadModel('ExamModel');

        if (!$exam_model->inExam()) {
            http_response_code(403);
            return;
        }

        if (isset($_SESSION['reviewExam']) && $_SESSION['reviewExam']) {
            header('Location: ' . URL . 'exams/unsetExam');
        } else {
            $student_exam = $_SESSION['Exam'];
            $exam_model->update("student_exam", $student_exam->id, (object) ["grade" => round($_SESSION['examGrade'], 2)]);
            header('Location: ' . URL . "index?exam_finished=true");
        }
    }

    public function generateExam()
    {
        if (!isset($_GET['data']))
            return;

        $ids = explode('-', $_GET['data']);

        $em = $this->loadModel('ExamModel');

        if (!$em->getById($ids[0]) || !$em->getById($ids[1], 'Exam_Center')) {
            http_response_code(404);
            return;
        }

        try {
            $em->generateExam(intval($ids[0]), intval($ids[1]));
            $success = true;
        } catch (\Throwable $th) {
            $success = false;
        }

        header('Location: ' . URL . "index?op_success=$success");
    }

    public function nextQuestion()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $exam_model = $this->loadModel('ExamModel');

        if (!$exam_model->inExam() || !isset($_SESSION['curQuestionIndex'])) {
            http_response_code(403);
            return;
        }

        $next_question_index = $_SESSION['curQuestionIndex'] + 1;

        if (!$_POST || (isset($_SESSION['reviewExam']) && $_SESSION['reviewExam'])) {
            header('Location: ' . URL . "exams/index/$next_question_index");
            array_push($_SESSION['studentChoices'], -1);
            simpleLog("Empty selection or review mode is enabled");
            return;
        }

        $answer_choice_id = explode('-', $_POST[array_key_first($_POST)])[1];

        $answer = new Student_Exam_Has_Choice();
        $answer->student_exam_id = $_SESSION['Exam']->id;
        $answer->choice_id = $answer_choice_id;

        simpleLog($exam_model->insert($answer));

        array_push($_SESSION['studentChoices'], $_POST[array_key_first($_POST)]);

        $is_correct =
            $exam_model->select([], 'choice', [Choice::id => $answer_choice_id])[0]->is_correct;

        if ($is_correct) {
            $_SESSION['examGrade'] += $_SESSION['MarksPerQuestion'];
        }

        $exam_model->update("student_exam", $_SESSION['Exam']->id, (object) ["grade" => round($_SESSION['examGrade'], 2)]);


        header('Location: ' . URL . "exams/index/$next_question_index");
    }

    public function unsetExam()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset($_SESSION['inExam']);
        unset($_SESSION['Exam']);
        unset($_SESSION['ExamQuestions']);
        unset($_SESSION['MarksPerQuestion']);
        unset($_SESSION['examGrade']);
        unset($_SESSION['studentChoices']);
        unset($_SESSION['curQuestionIndex']);
        unset($_SESSION['reviewExam']);

        header('Location: ' . URL . "index?exam_saved=true");
    }

    public function reviewExam()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $exam_model = $this->loadModel('ExamModel');

        if (!$exam_model->inExam() || !isset($_SESSION['curQuestionIndex'])) {
            http_response_code(403);
            return;
        }

        $_SESSION['reviewExam'] = true;
        header('Location: ' . URL . "exams/index/0");
    }
}
