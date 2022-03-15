<?php

function properties_exists($stdClass, array $properties, string $prefix)
{
    // simpleLog("properties_exists: checking " . json_encode($stdClass) . " to contain the following " . json_encode($properties));
    if ($stdClass == null) return false;
    foreach ($properties as $property) {
        if (!property_exists($stdClass, $prefix . $property)) {
            // simpleLog("this one failed $prefix" . "$property");
            if ($prefix == '')
                throw new Exception("Constructing : " . json_encode($stdClass) . " to contain the following " . json_encode($properties) . " this one failed $prefix" . "$property");
            return false;
        }
    }
    return true;
}

abstract class Table
{
    public int $id;
    public array $identifying_fields;
    public array $dependents = [];
    abstract public function get_CRUD_Terms();
    abstract static function SQL_Columns(string $prefix = "");
    abstract static function access(string $name);
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
    public array $dependents = ['Exam_Center_Has_Exam', 'Exam_Has_Question'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Form', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['id', 'number_of_questions', 'duration'];
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null)
            $cols = Exam::SQL_Columns();
        if (properties_exists($stdClass, Exam::SQL_Columns(), $prefix)) {
            foreach ($cols as $col) {
                $this->$col = $stdClass->{$prefix . $col};
            }
            $tmp = new Subject($stdClass, "subject_");
            if (isset($tmp->id))
                $this->subject = $tmp;
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $dependents = ['Exam', 'Topic'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['name'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $dependents = ['Question'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['name'];
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Topic::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
            $tmp = new Subject($stdClass, "subject_");
            if (isset($tmp->id)) {
                $this->subject = $tmp;
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $dependents = ['Choice', 'Exam_Has_Question'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['text'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $dependents = ['Student_Took_Exam'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Add', 'read' => 'Take', 'update' => 'Edit', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['text'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
    }
}
class Permission extends Table
{
    const id = 'permission.id';
    const name = 'permission.name';
    // this is what we'll inter act with the rest is just jargon
    public string $name;
    public array $dependents = ['Role_has_Permission'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['name'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
    }
}
class Role extends Table
{
    const id = 'role.id';
    const name = 'role.name';
    // this is what we'll inter act with the rest is just jargon
    public string $name;
    public array $dependents = ['User', 'Role_Has_Permission'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['name'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
    }
}
class Role_Has_Permission extends Table
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
    public array $identifying_fields =  ['role_id', 'permission_id'];
    function __construct($stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = Role_Has_Permission::SQL_Columns();
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public ?string $profile_picture;
    public int $role_id;
    public ?Role $role;
    public ?array $permissions;
    public array $dependents = [['Student' => 'id']];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Grant', 'read' => 'Take', 'update' => 'Transfer', 'delete' => 'Revoke'];
    }

    public array $identifying_fields =  ['username'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
    }
}
class Student extends Table
{
    const id = 'student.id';
    const enroll_date = 'student.enroll_date';
    // this is what we'll inter act with the rest is just jargon
    public string $enroll_date;
    public array $dependents = ['Student_Took_Exam', ['User' => 'id']];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Enroll', 'read' => 'Take', 'update' => 'edit enrollment', 'delete' => 'Unenroll'];
    }

    public array $identifying_fields =  ['id', 'enroll_date'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $dependents = ['Student_Took_Exam', 'Exam_Center_Has_Exam'];

    public function get_CRUD_Terms()
    {
        return ['create' => 'Enlist', 'read' => 'Take', 'update' => 'change', 'delete' => 'Decommission'];
    }

    public array $identifying_fields =  ['name'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $identifying_fields =  ['id', 'date'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $identifying_fields =  ['id', 'date'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
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
    public array $identifying_fields =  ['question_id', 'exam_id'];
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
    static function access(string $name)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();
        return $constants[$name];
    }
}
