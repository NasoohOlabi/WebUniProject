<?php

function properties_exists($stdClass, array $properties, string $prefix)
{
    foreach ($properties as $property) {
        if (!property_exists($stdClass, $prefix . $property))
            return false;
    }
    return true;
}


class Exam
{
    const id = "exam.id";
    const number_of_questions = "exam.number_of_questions";
    const duration = 'exam.duration';
    const subject_id = 'exam.subject_id';
    // this is what we'll inter act with the res is just jargon
    public int $id;
    public int $number_of_questions;
    public int $duration;
    public int $subject_id;
    public Subject $subject;
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null)
            $cols = Exam::SQL_Columns();
        if (properties_exists($stdClass, Exam::SQL_Columns(), $prefix)) {
            foreach ($cols as $col) {
                $this->$col = $stdClass->{$prefix . $col};
            }
            $this->subject = new Subject($stdClass, "subject_");
        }
    }
    static function SQL_Columns($prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Subject
{
    const id = 'subject.id';
    const name = 'subject.name';
    const description = 'subject.description';

    // this is what we'll inter act with the res is just jargon
    public int $id;
    public string $name;
    public string $description;
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Subject::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }
    static function SQL_Columns($prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Topic
{
    const id = 'topic.id';
    const name = 'topic.name';
    const description = 'topic.description';
    const subject_id = 'topic.subject_id';
    // this is what we'll inter act with the res is just jargon
    public int $id;
    public string $name;
    public string $description;
    public int $subject_id;
    public Subject $subject;
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Topic::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $this->subject = new Subject($stdClass, "subject_");
        }
    }
    static function SQL_Columns($prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Question
{
    const id = 'question.id';
    const text = 'question.text';
    const number_of_choices = 'question.number_of_choices';
    const topic_id = 'question.topic_id';
    // this is what we'll inter act with the res is just jargon
    public int $id;
    public string $text;
    public int $number_of_choices;
    public int $topic_id;
    public Topic $topic;
    public array $choices;
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Question::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $this->topic = new Topic($stdClass, "topic_");
        }
    }
    static function SQL_Columns($prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Choice
{
    const id = 'choice.id';
    const text = 'choice.text';
    const is_correct = 'choice.is_correct';
    const question_id = 'choice.question_id';
    // this is what we'll inter act with the res is just jargon
    public int $id;
    public string $text;
    public int $is_correct;
    public int $question_id;
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Choice::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }
    static function SQL_Columns($prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}
