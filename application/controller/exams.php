<?php
require_once 'application/views/_templates/header.php';
require_once 'application/views/exams/index.php';
require_once './application/libs/util/log.php';

class Exams extends Controller
{
    public function index()
    {

        $model = $this->loadModel('ExamModel');
        $exams = $model->getAllExams();


        // load views. within the views we can echo out $songs and $amount_of_songs easily
        // require_once 'application/views/_templates/question.php';
        // if ($questions instanceof Either\Result)
        //     echoQuestions($questions->result);
        pageHeadTag("Exams");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';

        if ($exams instanceof Either\Result)
            examsIndex($exams->result);

        require 'application/views/_templates/login_popup.php';
        require 'application/views/_templates/footer.php';

        pageHit("Exams.index");
    }
}
