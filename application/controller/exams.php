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

        $_SESSION['inExam'] = true; //TODO: REMOVE

        $exam_model = $this->loadModel('ExamModel');


        if (!$exam_model->inExam()) {
            http_response_code(403);
            return;
        }

        if ($question_index + 1 > sizeof($_SESSION['ExamQuestions']))
            $this->endExam();

        $curQuestion = $_SESSION['ExamQuestions'][$question_index];

        var_dump($curQuestion = $_SESSION['ExamQuestions']);

        $curQuestionInfo = $exam_model->select([], 'question', [Question::id => $curQuestion->question_id])[0];

        var_dump($curQuestionInfo);

        $question_text = $curQuestionInfo->text;
        $question_mark = $curQuestionInfo->text;


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

        ob_start();

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

        $em->loadExam(intval($ids[0]), intval($ids[1]));

        while (ob_get_status()) {
            ob_end_clean();
        }

        header('Location: ' . URL . 'exams/index/0');

        //$this->index(0);


        /* ... */
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



        /* ... */

        unset($_SESSION['inExam']);
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
}
