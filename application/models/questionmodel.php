<?php
require_once 'basemodel.php';
require_once 'core/schema.php';
class QuestionModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "question");
    }


    /**
     * 
     *
     * @param [int] $questions_ids
     * @return Option
     */
    function getTheseQuestions($arr)
    {
        $allNumbers = $arr == array_filter($arr, 'is_numeric');
        if (!$allNumbers)
            return new Either\Err("Bad input for getTheseQuestions $arr");


        $condition = implode(" OR ", array_fill(0, count($arr), "id = ?"));
        $questionsSQL = "SELECT * FROM `question` WHERE $condition";

        simpleLog("Running $questionsSQL");
        $query = $this->db->prepare($questionsSQL);
        $query->execute($arr);
        // $query->debugDumpParams();

        $questions = $query->fetchAll();

        $answersSQL = str_replace("id", "question_id ", str_replace("question", "choice", $questionsSQL));
        $query = $this->db->prepare($answersSQL);
        simpleLog("Running $answersSQL");
        $query->execute($arr);

        $answers = $query->fetchAll();

        foreach ($questions as $q) {
            foreach ($answers as $ans) {
                if ($ans->question_id == $q->id) {
                    if (property_exists($q, 'answers')) {
                        $q->answers[] = $ans;
                    } else {
                        $q->answers = [$ans];
                    }
                }
            }
        }

        return new Either\Result($questions);
    }

    function questionDetails($id)
    {
        // $query = $this->join(
        //     ["Question", "Topic", "Subject"],
        //     [
        //         [Question::topic_id => Topic::id],
        //         [Topic::subject_id => Subject::id],
        //         [Question::id => $id]
        //     ],
        //     "Question"
        // );
        // if ($query instanceof Either\Err) return $query;
        // $question = $query->result[0];
        // // var_dump($question);
        // $query = $this->select([], "Choice", [Choice::question_id => $question->id]);
        // $question->choices = $query;

        // return $question;
    }
}
