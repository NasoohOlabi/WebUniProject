<?php
function _minus_(array $big, array $small)
{

    // simpleLog("big: " . json_encode($big) . " small: " . json_encode($small));
    foreach ($big as $key => $value) {
        if (in_array($value, $small)) {
            unset($big[$key]);
        }
    }

    // simpleLog("big: " . json_encode(array_values($big)) . " small: " . json_encode($small));
    return array_values($big);
}
function properties_exists($stdClass, array $properties, string $prefix)
{

    // simpleLog("properties_exists: checking " . json_encode($stdClass) . " to contain the following " . json_encode(array_map(function ($elem) use ($prefix) {
    //     return $prefix . $elem;
    // }, $properties)),'basemodel/schema');
    if ($stdClass == null) return false;
    foreach ($properties as $property) {
        if (!property_exists($stdClass, $prefix . $property)) {

            simpleLog("this one failed $prefix" . "$property", 'basemodel/schema');
            if ($prefix == '')
                throw new Exception("Constructing : " . json_encode($stdClass) . " to contain the following " . json_encode($properties) . " this one failed $prefix" . "$property");
            return false;
        }
    }
    return true;
}

define('Question', 'Question');
define('Role', 'Role');
define('Exam', 'Exam');
define('Subject', 'Subject');
define('Topic', 'Topic');
define('Choice', 'Choice');
define('Permission', 'Permission');
define('Role_has_Permission', 'Role_has_Permission');
define('User', 'User');
define('Exam_Center', 'Exam_Center');
define('Student', 'Student');
define('Student_Exam_has_Question', 'Student_Exam_has_Question');
define('Student_Exam_has_Choice', 'Student_Exam_has_Choice');
define('Student_Exam', 'Student_Exam');

abstract class Table
{
    public int $id;
    public array $identifying_fields;
    public array $dependents = [];
    abstract public function get_CRUD_Terms(); // Not visible in the result of json_encode()

    function __construct(stdClass $stdClass = null, $prefix = "")
    {
        if ($stdClass != null) {
            $cols = (static::class)::SQL_Columns();
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }

    protected function relationalSetter(?stdClass $stdClass, string $name)
    {
        if ($stdClass === null) return;
        $tmp = new $name($stdClass, strtolower($name) . "_");
        if (isset($tmp->id))
            $this->{strtolower($name)} = $tmp;
    }

    static function SQL_Dotted_Columns()
    {
        $oClass = new ReflectionClass(static::class);
        $constants = array_values($oClass->getConstants());
        return  $constants;
    }
    static function SQL_Columns(string $prefix = "", $not_nulls = false)
    {
        $oClass = new ReflectionClass(static::class);
        $constants = array_keys($oClass->getConstants());
        return ($prefix == "") ? $constants : array_map(function ($args) use ($prefix) {
            return $prefix . $args;
        }, $constants);
    }
    static function access(string $name)
    {
        $oClass = new ReflectionClass(static::class);
        $constants = $oClass->getConstants();
        return $constants[$name];
    }
}
class Exam extends Table
{
    const id = "exam.id";
    const number_of_questions = "exam.number_of_questions";
    const duration = 'exam.duration';
    const subject_id = 'exam.subject_id';
    // this is what we'll interact with the rest is just jargon
    public int $number_of_questions;
    public int $duration;
    public int $subject_id;
    public ?Subject $subject;
    public array $dependents = ['Student_Exam'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Form', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['id', 'number_of_questions', 'duration'];
    function __construct(stdClass $stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Subject");
    }
}
class Subject extends Table
{
    const id = 'subject.id';
    const name = 'subject.name';
    const description = 'subject.description';
    // this is what we'll interact with the rest is just jargon
    public string $name;
    public string $description;
    public array $dependents = ['Exam', 'Topic'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['name'];
}
class Topic extends Table
{
    const id = 'topic.id';
    const name = 'topic.name';
    const description = 'topic.description';
    const subject_id = 'topic.subject_id';
    // this is what we'll interact with the rest is just jargon
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
    function __construct(stdClass $stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Subject");
    }
}
class Question extends Table
{
    const id = 'question.id';
    const text = 'question.text';
    const topic_id = 'question.topic_id';
    const active = 'question.active';
    // this is what we'll interact with the rest is just jargon
    public string $text;
    public int $active;
    public ?string $topic_id;
    public ?Topic $topic;
    public ?array $choices;
    public array $dependents = ['Choice', 'Student_Exam_Has_Question'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Write', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['text'];
    function __construct($stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Subject");
    }
}
class Choice extends Table
{
    const id = 'choice.id';
    const text = 'choice.text';
    const is_correct = 'choice.is_correct';
    const question_id = 'choice.question_id';
    // this is what we'll interact with the rest is just jargon
    public string $text;
    public int $is_correct;
    public int $question_id;
    public array $dependents = ['Student_Exam_Has_Choice'];
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
                    if ($col == "is_correct")
                        $this->$col = ($stdClass->{$prefix . $col} == 1) ? 1 : 0;
                    else
                        $this->$col = $stdClass->{$prefix . $col};
                }
            }
        }
    }
}
class Permission extends Table
{
    const id = 'permission.id';
    const name = 'permission.name';
    // this is what we'll interact with the rest is just jargon
    public string $name;
    public array $dependents = ['Role_Has_Permission'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Delete'];
    }
    public array $identifying_fields =  ['name'];
}
class Role extends Table
{
    const id = 'role.id';
    const name = 'role.name';
    // this is what we'll interact with the rest is just jargon
    public string $name;
    public array $dependents = ['User', 'Role_Has_Permission'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Create', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['name'];
}
class Role_Has_Permission extends Table
{
    const id = 'role_has_permission.id';
    const role_id = 'role_has_permission.role_id';
    const permission_id = 'role_has_permission.permission_id';
    // this is what we'll interact with the rest is just jargon
    public int $role_id;
    public int $permission_id;
    public ?Role $role;
    public ?Permission $permission;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Give', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['role_id', 'permission_id'];
    public array $dependents =  [];
    function __construct(stdClass $stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Role");
        $this->relationalSetter($stdClass, "Permission");
    }
}
class User extends Table
{
    const id = 'user.id';
    const username = 'user.username';
    const password = 'user.password';
    const first_name = 'user.first_name';
    const last_name = 'user.last_name';
    const middle_name = 'user.middle_name';
    const profile_picture = 'user.profile_picture';
    const role_id = 'user.role_id';
    // this is what we'll interact with the rest is just jargon
    public string $username;
    public string $password;
    public string $first_name;
    public string $last_name;
    public ?string $middle_name;
    public ?string $profile_picture;
    public int $role_id;
    public ?Role $role;
    public ?array $permissions;
    public array $dependents = ['Student', 'Exam_Center'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Join', 'read' => 'Take', 'update' => 'Transfer', 'delete' => 'Revoke'];
    }
    public array $identifying_fields =  ['username'];
    function __construct(stdClass $stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Subject");
        if ($stdClass != null) {
            $cols = _minus_(User::SQL_Columns(), ['middle_name', 'profile_picture']);
            if (properties_exists($stdClass, $cols, $prefix)) {
                foreach ($cols as $col) {
                    $this->$col = $stdClass->{$prefix . $col};
                }

                $this->profile_picture = (isset($stdClass->{$prefix . 'profile_picture'})) ? $stdClass->{$prefix . 'profile_picture'} : null;
                $this->middle_name = (isset($stdClass->{$prefix . 'middle_name'})) ? $stdClass->{$prefix . 'middle_name'} : null;

                $this->relationalSetter($stdClass, "Role");
            }
        }
    }
}
class Student extends Table
{
    const id = 'student.id';
    const enroll_date = 'student.enroll_date';
    const user_id = 'student.user_id';
    // this is what we'll interact with the rest is just jargon
    public string $enroll_date;
    public int $user_id;
    public array $dependents = ['Student_Exam'];
    public function get_CRUD_Terms()
    {
        return ['create' => 'Enroll', 'read' => 'Take', 'update' => 'edit enrollment', 'delete' => 'Unenroll'];
    }

    public array $identifying_fields =  ['id', 'enroll_date'];
}
class Exam_Center extends Table
{
    const id = 'exam_center.id';
    const name = 'exam_center.name';
    const description = 'exam_center.description';
    const user_id = 'exam_center.user_id';
    // this is what we'll interact with the rest is just jargon
    public string $name;
    public string $description;
    public int $user_id;
    public User $user;
    public array $dependents = ['Student_Exam'];

    public function get_CRUD_Terms()
    {
        return ['create' => 'Enlist', 'read' => 'Take', 'update' => 'change', 'delete' => 'Decommission'];
    }

    public array $identifying_fields =  ['name'];
    function __construct($stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "User");
    }
}
class Student_Exam extends Table
{
    const id = 'student_exam.id';
    const date = 'student_exam.date';
    const exam_id = 'student_exam.exam_id';
    const student_id = 'student_exam.student_id';
    const exam_center_id = 'student_exam.exam_center_id';
    const qs_hash = 'student_exam.qs_hash';
    const grade = 'student_exam.grade';
    // this is what we'll interact with the rest is just jargon
    public string $date;
    public int $exam_id;
    public int $exam_center_id;
    public ?string $student_id;
    public string $qs_hash;
    public ?float $grade;
    public ?Exam $exam;
    public ?Exam_Center $exam_center;
    public ?Student $student;

    public array $dependents = ['Student_Exam_Has_Choice', 'Student_Exam_Has_Question'];

    public function get_CRUD_Terms()
    {
        return ['create' => 'Give', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['id', 'date'];
    function __construct($stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Exam");
        $this->relationalSetter($stdClass, "Exam_Center");
        $this->relationalSetter($stdClass, "Student");
    }
}

class Student_Exam_Has_Choice extends Table
{
    const id = 'student_exam_has_choice.id';
    const choice_id = 'student_exam_has_choice.choice_id';
    const student_exam_id = 'student_exam_has_choice.student_exam_id';
    // this is what we'll interact with the rest is just jargon
    public int $choice_id;
    public int $student_exam_id;
    public ?Student_Exam $student_exam;
    public ?Choice $choice;
    public function get_CRUD_Terms()
    {
        return ['create' => '', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['id', 'date'];
    function __construct($stdClass = null, $prefix = "")
    {
        parent::__construct($stdClass, $prefix);
        $this->relationalSetter($stdClass, "Choice");
        $this->relationalSetter($stdClass, "Student_Exam");
    }
}
class Student_Exam_Has_Question extends Table
{
    const id = 'student_exam_has_question.id';
    const question_id = 'student_exam_has_question.question_id';
    const student_exam_id = 'student_exam_has_question.student_exam_id';
    // this is what we'll interact with the rest is just jargon
    public int $id;
    public int $question_id;
    public int $student_exam_id;
    public ?Question $question;
    public ?Student_Exam $student_exam;
    public function get_CRUD_Terms()
    {
        return ['create' => 'Give', 'read' => 'Take', 'update' => 'Change', 'delete' => 'Remove'];
    }
    public array $identifying_fields =  ['question_id', 'student_exam_id'];
}
