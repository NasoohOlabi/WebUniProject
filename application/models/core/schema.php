<?php

function properties_exists($stdClass, array $properties, string $prefix)
{
    if ($stdClass == null) return false;
    foreach ($properties as $property) {
        if (!property_exists($stdClass, $prefix . $property))
            // if (!isset($stdClass->{$prefix . $property}))
            return false;
    }
    return true;
}

abstract class Table
{
    public int $id;
    abstract public function string_identifying_columns(string $prefix = '');
    abstract public function get_CRUD_Terms();
    abstract static function SQL_Columns(string $prefix = "");
}
class Exam extends Table
{
    const id = "exam.id";
    const number_of_questions = "exam.number_of_questions";
    const duration = 'exam.duration';
    const subject_id = 'exam.subject_id';
    // this is what we'll inter act with the rest is just jargon
    public int $number_of_questions;
    public int $duration;
    public int $subject_id;
    public ?Subject $subject;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Form', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix = '')
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
    public string $name;
    public string $description;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix = '')
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
    public string $name;
    public string $description;
    public int $subject_id;
    public ?Subject $subject;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix = '')
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
    public string $text;
    public int $number_of_choices;
    public int $topic_id;
    public ?Topic $topic;
    public ?array $choices;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix = '')
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
                $tmp = new Topic($stdClass, "topic_");
                if (isset($tmp->id))
                    $this->topic = $tmp;
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
class Choice extends Table
{
    const id = 'choice.id';
    const text = 'choice.text';
    const is_correct = 'choice.is_correct';
    const question_id = 'choice.question_id';
    // this is what we'll inter act with the rest is just jargon
    public string $text;
    public int $is_correct;
    public int $question_id;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Add', 'read' => 'Take', 'update' => 'Edit', 'delete' => 'Remove'];
    }
    public function string_identifying_columns(string $prefix = '')
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
    public string $name;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public function string_identifying_columns(string $prefix = '')
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
    public string $name;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public function string_identifying_columns(string $prefix = '')
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
    public int $role_id;
    public int $permission_id;
    public ?Role $role;
    public ?Permission $permission;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Give', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public function string_identifying_columns(string $prefix = '')
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
            $tmp = new Role($stdClass, 'role_');
            if (isset($tmp->id))
                $this->role = $tmp;
            $tmp = new Permission($stdClass, 'permission_');
            if (isset($tmp->id))
                $this->permission = $tmp;
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
    public string $username;
    public string $password;
    public string $first_name;
    public string $last_name;
    public ?string $middle_name;
    public $profile_picture;
    public int $role_id;
    public ?Role $role;
    public ?array $permissions;

    public function get_CRUD_Terms()
    {
        return ['create' => 'Grant', 'read' => 'Take', 'update' => 'Transfer', 'delete' => 'Revoke'];
    }

    public function string_identifying_columns(string $prefix = '')
    {
        return [1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = User::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $tmp = new Role($stdClass, 'role_');
            if (isset($tmp->id))
                $this->role = $tmp;
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
class Student extends Table
{
    const id = 'student.id';
    const enroll_date = 'student.enroll_date';
    // this is what we'll inter act with the rest is just jargon
    public string $enroll_date;

    public function get_CRUD_Terms()
    {
        return ['create' => 'Enroll', 'read' => 'Take', 'update' => 'edit enrollment', 'delete' => 'Unenroll'];
    }

    public function string_identifying_columns(string $prefix = '')
    {
        return [0, 1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Student::SQL_Columns();
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
class Exam_Center extends Table
{
    const id = 'exam_center.id';
    const name = 'exam_center.name';
    const description = 'exam_center.description';
    // this is what we'll inter act with the rest is just jargon
    public string $name;
    public string $description;

    public function get_CRUD_Terms()
    {
        return ['create' => 'Enlist', 'read' => 'Take', 'update' => 'change', 'delete' => 'Decommission'];
    }

    public function string_identifying_columns(string $prefix = '')
    {
        return [1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Exam_Center::SQL_Columns();
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
class Exam_Center_Has_Exam extends Table
{
    const id = 'exam_center_has_exam.id';
    const date = 'exam_center_has_exam.date';
    const exam_id = 'exam_center_has_exam.exam_id';
    const exam_center_id = 'exam_center_has_exam.exam_center_id';
    // this is what we'll inter act with the rest is just jargon
    public string $date;
    public int $exam_id;
    public int $exam_center_id;
    public ?Exam $exam;
    public ?Exam_Center $exam_center;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Give', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public function string_identifying_columns(string $prefix = '')
    {
        return [0, 1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Exam_Center_Has_Exam::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $tmp = new Exam($stdClass, 'exam_');
            if (isset($tmp->id))
                $this->exam = $tmp;
            $tmp = new Exam_Center($stdClass, 'exam_center_');
            if (isset($tmp->id))
                $this->exam_center = $tmp;
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

class Student_Took_Exam extends Table
{
    const id = 'student_took_exam.id';
    const date = 'student_took_exam.date';
    const choice_id = 'student_took_exam.choice_id';
    const student_id = 'student_took_exam.student_id';
    const exam_center_id = 'student_took_exam.exam_center_id';
    // this is what we'll inter act with the rest is just jargon
    public string $date;
    public int $choice_id;
    public int $student_id;
    public int $exam_center_id;
    public ?Student $student;
    public ?Choice $choice;
    public ?Exam_Center $exam_center;
    public function get_CRUD_Terms()
    {
        return ['create' => '', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public function string_identifying_columns(string $prefix = '')
    {
        return [0, 1];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Student_Took_Exam::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $tmp = new Choice($stdClass, 'choice_');
            if (isset($tmp->id))
                $this->choice = $tmp;
            $tmp = new Student($stdClass, 'student_');
            if (isset($tmp->id))
                $this->student = $tmp;
            $tmp = new Exam_Center($stdClass, 'exam_center_');
            if (isset($tmp->id))
                $this->exam_center = $tmp;
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

class Exam_Has_Question extends Table
{
    const id = 'exam_has_question.id';
    const question_id = 'exam_has_question.question_id';
    const exam_id = 'exam_has_question.exam_id';
    // this is what we'll inter act with the rest is just jargon
    public int $question_id;
    public int $exam_id;
    public ?Exam $exam;
    public ?Question $question;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Give', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public function string_identifying_columns(string $prefix = '')
    {
        return [0, 1, 2];
    }
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Exam_Has_Question::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $tmp = new Exam($stdClass, 'exam_');
            if (isset($tmp->id))
                $this->exam = $tmp;
            $tmp = new Question($stdClass, 'question_');
            if (isset($tmp->id))
                $this->question = $tmp;
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
