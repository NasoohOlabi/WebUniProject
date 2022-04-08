<?php
require_once 'application/views/_templates/header.php';
require_once 'application/models/core/schema.php';
require_once 'application/views/_templates/schema_table.php';

class Exams extends Controller
{
    public function index()
    {

        //TODO: remove =null

        // $model = $this->loadModel('ExamModel');
        // $Exam = $model->getAll();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['inExam'] = true; //TODO: REMOVE

        $exam_model = $this->loadModel('ExamModel');


        if (!$exam_model->inExam()) {
            http_response_code(403);
            return;
        }


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

        $student = new stdClass();
        $student->id = 1;
        $student->enroll_date = "2022-04-12";
        $student->user_id = "5";
        $student = new Student($student);

        $exam_center = new stdClass();
        $exam_center->id = 1;
        $exam_center->name = "Hiast Center";
        $exam_center->description = 'A center in the hiast...';
        $exam_center->user_id = 31;
        $exam_center = new Exam_Center($exam_center);


        $exam_model = $this->loadModel('ExamModel');

        $student_exam = $exam_model->InsertOneOfAKind_student_exam($exam, $student, $exam_center);

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

        /* ... */

        $_SESSION['inExam'] = true;
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
        //$data = $_GET['data'];
        var_dump($_GET);

        if (!isset($_GET['data']))
            return;

        $ids = explode('-', $_GET['data']);

        $em = $this->loadModel('ExamModel');

        if (!$em->getById($ids[0]) || !$em->getById($ids[1], 'Exam_Center')) {
            http_response_code(404);
            return;
        }

        echo "found";
    }
}
