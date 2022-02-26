<?php
require_once 'basemodel.php';
require_once 'subjectmodel.php';
require_once 'core/schema.php';

class ExamModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "exam");
    }

    function subject($id)
    {
        $sql = "SELECT name FROM `subject` WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([":id" => $id]);
        $subjects = $query->fetchAll();
        return (count($subjects)) ?
            new Either\Result($subjects[0]) :
            new Either\Err("id $id doesn't exist in table subject");
    }
    function questionsRandomSample()
    {
        $sql = "SELECT * FROM question AS t1 JOIN (SELECT id FROM question ORDER BY RAND() LIMIT 10) as t2 ON t1.id=t2.id";
        // TODO: fill the rest

    }
    /**
     * join exams with their corresponding subject
     * @example object(Option)#7 (2) {
     *  ["left"]=>
     *  NULL
     *  ["result"]=>
     *  array(1) {
     *    [0]=>
     *    object(stdClass)#6 (6) {
     *      ["id"]=>
     *      string(1) "1"
     *      ["number_of_questions"]=>
     *      string(1) "1"
     *      ["duration"]=>
     *      string(1) "5"
     *      ["subject_id"]=>
     *      string(1) "1"
     *      ["subject_name"]=>
     *      string(16) "Computer Science"
     *      ["subject_description"]=>
     *      string(43) "It's the study of Computers and other stuff"
     *    }
     *  }
     *}
     * @return Exam
     */
    function getAllExams()
    {
        $sql = "SELECT exam.id as id, number_of_questions,duration, subject_id, subject.name as subject_name,subject.description as subject_description FROM `exam` JOIN `subject` ON exam.subject_id=subject.id;";
        $query = $this->db->prepare($sql);
        $query->execute();
        $exams = $query->fetchAll();
        return (count($exams)) ?
            new Either\Result(array_map(function ($args) {
                return new Exam($args);
            }, $exams)) :
            new Either\Err("No Exams available");
    }
}
