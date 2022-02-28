<?php
function echoQuestion($question)
{
    require_once "__question.php";
}
function echoQuestions($questions)
{
    echo '<pre>';
    var_dump($questions);
    echo '</pre>';
    foreach ($questions as $question) {
        echoQuestion($question);
    }
}
