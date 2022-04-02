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
}
