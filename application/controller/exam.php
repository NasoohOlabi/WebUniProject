<?php
require_once 'application/views/_templates/header.php';
require_once 'application/libs/util/log.php';
require_once 'application/models/core/schema.php';



require_once 'application/views/_templates/schema_table.php';

class Exams extends Controller
{
    public function index()
    {

        //TODO: remove =null

        $model = $this->loadModel('ExamModel');
        $Exam = $model->getAll();



        // if ((session_status() === PHP_SESSION_NONE) || (!isset($_SESSION['inExam']) || (!$_SESSION['inExam']))) { //TODO ACTIVATE AFTER DONE
        //     http_response_code(403);
        // }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // var_dump($_SESSION);

        //WEIRD BUG:
        // echo "HELLO WORLD";


        // $exam_model = $this->loadModel('ExamModel');

        // echo "here";

        // var_dump($exam_model);

        // echo "hi";


        // if (!$exam_model->inExam()) {
        //     http_response_code(403);
        // }


        pageHeadTag("Exam");
        require 'application/views/_templates/user_navbar.php';
        require 'application/views/exams/index.php';
        pageHit("Exam.index");
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
        }

        /* ... */

        unset($_SESSION['inExam']);
    }
}
