<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require_once './application/models/core/schema.php';


/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Api extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {

        simpleLog("Api/index called");

        // admin session check!
        echo '<pre>';
        echo "APIs are great, make sure to use to be the admin to use this api for now";
        echo '</pre>';
    }
    public function create($var = null)
    {
        try {
            // Clean inputs
            $_POST = array_map('htmlentities', $_POST);
            $_POST = array_map('trim', $_POST);
            // Consider empty strings as null
            $_POST = array_map(function ($v) {
                return (is_string($v) && strlen($v) == 0) ? NULL : $v;
            }, $_POST);
            // Get the name of what are we creating
            $className = $_POST['schemaClass'];
            // Passwords get special treatment and get hashed
            if (isset($_POST['password']))
                $_POST['password'] = md5($_POST['password']);
            // Pseudo id since we don't have one
            // Note that the constructor needs the field id
            // While the insert Operation drops id and doesn't take in consideration
            $_POST['id'] = -1;
            // The constructor checks if the required field are satisfied
            // and also obviously checks is $className is sth we have
            $v = new $className((object) $_POST);
            simpleLog(json_encode($v));
            $Model = $this->loadModel('BaseModel');
            $Model->experimental_insert($v);
        } catch (\Throwable $e) {
            simpleLog('Caught exception: ' . $e->getMessage());
            http_response_code(400);
            echo 'Operation Failed';
        }
    }
    public function read(string $schemaClass = null, string $id = null)
    {

        $_POST = json_decode(file_get_contents("php://input"), true);
        simpleLog('_POST' . json_encode($_POST));
        if ($schemaClass == null) {
            // invalid request
            http_response_code(400);
            echo 'Operation Failed';
            return;
        }
        $more = false;
        if ($id !== null)
            $id = intval($id);
        if (isset($_POST['op']) && $_POST['op'] === 'get after' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $more = true;
        }
        function endsWith(string $haystack, string  $needle)
        {
            $length = strlen($needle);
            return $length > 0 ? substr($haystack, -$length) === $needle : true;
        }
        $Model = $this->loadModel('BaseModel');
        $get_dets_r = function (Table $answer) use ($Model, &$get_dets_r) {
            foreach ($answer as $prop => $val) {
                if (endsWith($prop, '_id')) {
                    $subSchemaClass = substr($prop, 0, -3);
                    $answer->{$subSchemaClass} =
                        $Model->select([], $subSchemaClass, [], [$subSchemaClass::id => $val])[0];
                    $answer->{$subSchemaClass} = $get_dets_r($answer->{$subSchemaClass});
                }
            }
            return $answer;
        };
        try {
            if ($more)
                $answers = $Model->select([], $schemaClass, [], (($id !== null) ? [$schemaClass::id => $id, 'op' => '<'] : []));
            else
                $answers = $Model->select([], $schemaClass, [], (($id !== null) ? [$schemaClass::id => $id] : []));
            $answers = array_map($get_dets_r, $answers);
            $answers = array_map(function ($row) use ($Model) {
                if (property_exists($row, 'password'))
                    unset($row->password);
                if (get_class($row) === 'Question')
                    $row->choices = $Model->select([], 'Choice', [], [Choice::question_id => $row->id]);

                return $row;
            }, $answers);
            if (count($answers) >= 1)
                echo json_encode($answers);
            else
                echo "id $id Not Found";
        } catch (\Throwable $e) {
            simpleLog('Caught exception: ' . $e->getMessage());
            http_response_code(400);
            echo 'Operation Failed';
        }
    }
    public function update($var = null)
    {
        # code...
    }
    public function delete($var = null)
    {
        # code...
    }
}
