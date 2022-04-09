<?php
require_once 'basemodel.php';
require_once 'subjectmodel.php';

class ExamModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "exam");
        simpleLog("Exam Model Loaded");
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
    function questionsRandomSampleTopic(int $number_of_questions, int $topic_id)
    {
        $sql = "SELECT id FROM `question` WHERE question.topic_id = $topic_id ORDER BY RAND() LIMIT $number_of_questions;";
        $query = $this->db->prepare($sql);
        // $query->execute([$topic_id, $number_of_questions]);
        $query->execute();
        $questions = array_map(function ($question) {
            return $question->id;
        }, $query->fetchAll());
        return $questions;
    }

    function InsertOneOfAKind_student_exam(Exam $exam, Exam_Center $exam_center)
    {
        $number_of_questions =  $exam->number_of_questions;
        $subject_id = $exam->subject_id;

        $sql = "SELECT id FROM `topic` WHERE subject_id = :subject_id";
        $query = $this->db->prepare($sql);
        $query->execute([":subject_id" => $subject_id]);
        $topic_ids = array_map(function ($row) {
            return $row->id;
        }, $query->fetchAll());
        $number_of_questions_per_topic = $number_of_questions / count($topic_ids);


        /**
         * topic_questions is an array of arrays indexed by topic_ids
         * and so it maps a topic_id to an array of question_ids
         * that where picked at random corresponding to that topic_d
         */
        $topic_questions = [];
        foreach ($topic_ids as $topic_id) {
            $topic_questions[$topic_id] = $this->questionsRandomSampleTopic($number_of_questions_per_topic + 1, $topic_id);
        }


        /**
         * since questionsRandomSampleTopic gave us one more question than we need for a certain topic
         * we'll go through different combinations of question by removing one question from the array
         * and so if the sample size is 5, we'll have 5 different combinations of samples of size 4
         * assuming $number_of_questions_per_topic is 4
         * this function will take and array of question_id and will return
         * [13,12,45]
         * and so the returned array will 
         * [
         *      hash of sample of all the questions in the input array except for 13 meaning "12,45",
         *      hash of sample of all the questions in the input array except for 12 meaning "13,45",
         *      hash of sample of all the questions in the input array except for 45 meaning "13,12",
         * ]
         */
        $questions_ids_string_md5 = function (array $question_ids, int $without_index = null) {
            if ($without_index === null)
                return implode(",", $question_ids);
            else {
                $initial_array = array_values($question_ids);
                unset($initial_array[$without_index]);
                $final_array = array_values($initial_array);
                return implode(",", $final_array);
            }
        };


        // using the previously defined function to get the md5 hash of the sample of questions
        $topic_to_hashes_to_exams_without_index = [];
        foreach ($topic_questions as $topic_id => $question_ids) {
            foreach ($question_ids as $index => $question) {
                $topic_to_hashes_to_exams_without_index[$topic_id][$index] = $questions_ids_string_md5($question_ids, $index);
            }
        }


        /**
         * recursively try combinations of exams based on the combinations topic sampled questions
         * ex:
         *                                                topics [    2,    4, 5, 6]
         *   question_ids without question_id in the index         "12,45"  1  1  1  
         *   question_ids without question_id in the index         "13,45"  2  2  2  
         *   question_ids without question_id in the index         "13,12"  3  3  3  
         * 
         * we'll go through exams like this
         * 
         * E := exam with topic 2 sample without q at index 1 
         *            and topic 4 sample without q at index 1 
         *            and topic 5 sample without q at index 1 
         *            and topic 6 sample without q at index 1
         * 
         * if E unique we'll stop looking for more
         * otherwise we'll try to find more using the same way recursively
         * 
         * 
         */
        $r = function (int $topic_ids_index = 0, array $acc = []) use (&$r, $topic_ids, $topic_to_hashes_to_exams_without_index) {
            if ($topic_ids_index >= count($topic_ids)) {
                // check if the md5 of the implode of acc exist in sql table student_exam in column qs_hash
                $sql = "SELECT COUNT(*) as num FROM `student_exam` WHERE qs_hash = :qs_hash";
                $query = $this->db->prepare($sql);
                $potentially_unique_qs = implode("&", $acc);
                $potentially_unique_qs_hash = md5($potentially_unique_qs);
                $query->execute([":qs_hash" => $potentially_unique_qs_hash]);
                $number_of_exams = $query->fetchAll()[0]->num;
                if ($number_of_exams == 0) {
                    $answer = [];
                    foreach (array_keys($acc) as $topic_id_not_taken_q_ind) {
                        $broken_down = explode("::", $topic_id_not_taken_q_ind);
                        $answer[$broken_down[0]] = $broken_down[1];
                    }
                    return ['unique hash' => $potentially_unique_qs_hash, 'result' => $answer];
                } else {
                    return null;
                }
            } else {
                $topic_id = $topic_ids[$topic_ids_index];
                foreach ($topic_to_hashes_to_exams_without_index[$topic_id] as $index => $hash) {
                    $acc["$topic_id::$index"] = $hash;
                    $try = $r($topic_ids_index + 1, $acc);
                    if ($try != null) return $try;
                    unset($acc["$topic_id::$index"]);
                }
            }
        };


        // try to find a unique exam
        // using the previously defined function 
        $what_to_throw_for_topic_id = $r();

        if ($what_to_throw_for_topic_id === null)
            throw new Exception("couldn't generate a unique exam");

        // we'll take the result of the function 
        // and using it we'll clean the topic_questions array
        foreach ($what_to_throw_for_topic_id['result'] as $topic_id => $throw_away_question_id_ind) {
            $question_ids = $topic_questions[$topic_id];

            unset($question_ids[$throw_away_question_id_ind]);

            $topic_questions[$topic_id] = array_values($question_ids);
        }

        // insert the unique student_exam using the unique hash
        $insert_time = date("Y-m-d");
        $sql = "INSERT INTO `student_exam` (`date`,`exam_id`,`student_id`,`exam_center_id`,`qs_hash`) VALUES (?, ?, ?, ?, ?)";
        $query = $this->db->prepare($sql);
        $query->execute([$insert_time, $exam->id, null, $exam_center->id, $what_to_throw_for_topic_id['unique hash']]);
        $student_exam_id = $this->db->lastInsertId();

        // link questions to the student exam using the topic_questions array
        foreach ($topic_questions as $topic_id => $question_ids) {
            $sql = "INSERT INTO `student_exam_has_question` (`student_exam_id`, `question_id`) VALUES ";
            $values = [];
            foreach ($question_ids as $question_id) {
                $values[] = "($student_exam_id, $question_id)";
            }
            $sql .= implode(",", $values);
            $query = $this->db->prepare($sql);
            $query->execute();
        }

        $student_exam = new Student_Exam();
        $student_exam->id = $student_exam_id;
        $student_exam->exam = $exam;
        $student_exam->student = null;
        $student_exam->exam_center = $exam_center;
        $student_exam->student_id = 0;
        $student_exam->exam_id = $exam->id;
        $student_exam->exam_center_id = $exam_center->id;
        $student_exam->qs_hash = $what_to_throw_for_topic_id['unique hash'];
        $student_exam->date = $insert_time;

        // return the student_exam
        return $student_exam;
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

    public function inExam()
    {
        return (isset($_SESSION['inExam']) && ($_SESSION['inExam']));
    }

    public function generateExam($exam_id, $exam_center_id)
    {

        $exam = $this->select([], 'exam', ["exam.id" => $exam_id])[0];
        $exam_center = $this->select([], 'exam_center', ["exam_center.id" => $exam_center_id])[0];
        $student_exam = $this->InsertOneOfAKind_student_exam($exam, $exam_center);
    }

    public function loadExam($exam_id, $exam_center_id)
    {

        //TODO CHANGE ID

        $valid_student_exams = $this->select([], 'student_exam', [[Student_Exam::exam_id => $exam_id], [Student_Exam::student_id => 102]]);

        var_dump($valid_student_exams);

        $student_exam = $valid_student_exams[array_rand($valid_student_exams)];
        if (!$student_exam) {
            http_response_code(404);
            return;
        }

        $student_id = $this->select([], 'student', [Student::user_id => $_SESSION['user']->id])[0]->id;

        var_dump($student_id);


        $curDate = date("Y-m-d");

        $this->update("student_exam", $student_exam->id, (object) ["date" => $curDate, "student_id" => $student_id, "exam_center_id" => $exam_center_id]);

        $_SESSION['inExam'] = true;
        $_SESSION['Exam'] = $student_exam;

        $questions = $this->select([], 'student_exam_has_question', [Student_Exam_Has_Question::student_exam_id => $student_exam->id]);
        shuffle($questions);
        $_SESSION['ExamQuestions'] = $questions;
    }
}
