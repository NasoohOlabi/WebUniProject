<?php

function properties_exists($stdClass, array $properties, string $prefix)
{
    // unset($properties[6]);
    foreach ($properties as $property) {
        if (!property_exists($stdClass, $prefix . $property))
            return false;
    }
    return true;
}

abstract class Table
{
    abstract public function string_identifying_columns(string $prefix);
    public array $CRUD_Terms;
}
class Exam extends Table
{
    const id = "exam.id";
    const number_of_questions = "exam.number_of_questions";
    const duration = 'exam.duration';
    const subject_id = 'exam.subject_id';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public int $number_of_questions;
    public int $duration;
    public int $subject_id;
    public ?Subject $subject;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1, 2];
    }
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
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Subject extends Table
{
    const id = 'subject.id';
    const name = 'subject.name';
    const description = 'subject.description';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $name;
    public string $description;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
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
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Topic extends Table
{
    const id = 'topic.id';
    const name = 'topic.name';
    const description = 'topic.description';
    const subject_id = 'topic.subject_id';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $name;
    public string $description;
    public int $subject_id;
    public ?Subject $subject;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
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
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Question extends Table
{
    const id = 'question.id';
    const text = 'question.text';
    const number_of_choices = 'question.number_of_choices';
    const topic_id = 'question.topic_id';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $text;
    public int $number_of_choices;
    public int $topic_id;
    public ?Topic $topic;
    public ?array $choices;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
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
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Choice extends Table
{
    const id = 'choice.id';
    const text = 'choice.text';
    const is_correct = 'choice.is_correct';
    const question_id = 'choice.question_id';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $text;
    public int $is_correct;
    public int $question_id;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
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
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Permission extends Table
{
    const id = 'permission.id';
    const name = 'permission.name';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $name;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Permission::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}


class Role extends Table
{
    const id = 'role.id';
    const name = 'role.name';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $name;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Role::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class Role_has_Permission extends Table
{
    const id = 'role_has_permission.id';
    const role_id = 'role_has_permission.role_id';
    const permission_id = 'role_has_permission.permission_id';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public int $role_id;
    public int $permission_id;
    public ?Role $role;
    public ?Permission $permission;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix)
    {
        return [1, 2];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Role_has_Permission::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $this->role = new Role($stdClass);
            $this->permission = new Permission($stdClass);
        }
    }
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}

class User extends Table
{
    const id = 'user.id';
    const username = 'user.username';
    const password = 'user.password';
    const first_name = 'user.first_name';
    const last_name = 'user.first_name';
    const middle_name = 'user.middle_name';
    const profile_picture = 'user.profile_picture';
    const role_id = 'user.role_id';
    // this is what we'll inter act with the rest is just jargon
    public int $id;
    public string $username;
    public string $password;
    public string $first_name;
    public string $last_name;
    public ?string $middle_name;
    public $profile_picture;
    public int $role_id;

    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }

    public function string_identifying_columns(string $prefix)
    {
        return [1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = User::SQL_Columns();
            unset($cols[6]);
            unset($cols[5]);
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }
    static function SQL_Columns(string $prefix = "")
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
}
