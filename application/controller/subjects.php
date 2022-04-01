<?php
require_once './application/libs/util/log.php';

class Subjects extends Controller
{
    public function generate($name)
    {
        $subject_model = $this->loadModel('SubjectModel');
        $subject_model->random_generate($name);
    }
}
