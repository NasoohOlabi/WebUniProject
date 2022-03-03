<?php
function echoQuestion($question)
{
    require_once "__question.php";
}
function echoQuestions($questions)
{
    foreach ($questions as $question) {
        echoQuestion($question);
    }
}
