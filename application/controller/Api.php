<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require_once './application/models/core/schema.php';

function is_display_key($key)
{
    return !endsWith($key, 'id') && $key !== 'identifying_fields' && $key !== 'profile_picture' &&
        $key !== "dependents";
}
class Api extends Controller
{
    public function index()
    {
        // admin session check!
        echo '<pre>';
        echo "APIs are great, make sure to use to be the admin to use this api for now";
        echo '</pre>';
        pageHit("Api.index");
    }
    public function create($className = null)
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        try {
            // Clean inputs
            $_POST = array_map('htmlentities', $_POST);
            $_POST = array_map('trim', $_POST);
            // Consider empty strings as null
            $_POST = array_map(function ($v) {
                return (is_string($v) && strlen($v) == 0) ? NULL : $v;
            }, $_POST);
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
            simpleLog(json_encode($v), 'Api/create/');
            $Model = $this->loadModel('BaseModel');
            if ($Model->experimental_insert($v)) {
                echo json_encode($v);
            } else {
                echo "create $className failed";
            }
            // header('Location:' . URL . 'DashBoard/');
        } catch (\Throwable $e) {
            simpleLog('Caught exception: ' . $e->getMessage());
            http_response_code(400);
            echo 'Operation Failed : ' . 'Caught exception: ' . str_replace('::', ' ', str_replace('$', ' ', $e->getMessage()));
        }
        pageHit("Api.create");
    }
    public function read(string $schemaClass = null, string $id = null)
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        if ($schemaClass == null) {
            // invalid request
            http_response_code(400);
            echo 'Operation Failed';
            simpleLog('$_POST ' . json_encode($_POST) . ' failed', 'Api/read/');
            return;
        }
        $more = false;
        if ($id !== null)
            $id = intval($id);
        if (isset($_POST['op']) && $_POST['op'] === 'get after' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $more = true;
        }
        $limit = 30;
        if (isset($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] < 100 && $_POST['limit'] > 0)
            $limit = $_POST['limit'];

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
                $answers = $Model->select([], $schemaClass, [], (($id !== null) ? [$schemaClass::id => $id, 'op' => '<'] : []), $limit);
            else
                $answers = $Model->select([], $schemaClass, [], (($id !== null) ? [$schemaClass::id => $id] : []), $limit);
            $answers = array_map($get_dets_r, $answers);
            $answers = array_map(function ($row) use ($Model) {
                if (property_exists($row, 'password'))
                    unset($row->password);
                // TODO: generify this
                // if (get_class($row) === 'Question')
                //     $row->choices = $Model->select([], 'Choice', [], [Choice::question_id => $row->id]);
                if (count($row->dependents) === 0) return $row;


                foreach ($row->dependents as $dep) {
                    if (is_array($dep) && count($dep) == 1) {
                        foreach ($dep as $key => $value) {
                            $row->{strtolower($key) . 's'} = $Model->select([], $key, [], [$key::access($value) => $row->id]);
                        }
                    } else {
                        $row->{strtolower($dep) . 's'} = $Model->select([], $dep, [], [$dep::access(strtolower(get_class($row)) . '_id') => $row->id]);
                    }
                }
                return $row;
            }, $answers);
            simpleLog('$_POST ' . json_encode($_POST) . ' served', 'Api/read/');
            if (count($answers) >= 1)
                echo json_encode($answers);
            elseif ($more)
                echo "that's all we have";
            else
                echo "id $id Not Found";
        } catch (\Throwable $e) {
            simpleLog('$_POST ' .
                json_encode($_POST) . ' failed ' . 'Caught exception: ' . $e->getMessage(), ' Api/read/');
            http_response_code(400);
            echo 'Operation Failed ' . ' $_POST ' .
                json_encode($_POST) . ' failed ' . 'Caught exception: ' . $e->getMessage(), ' Api/read/';
        }
        pageHit("Api.read");
    }
    public function like($var = null)
    {
        # read sth like $var as in sql like()
    }
    public function update(string $schemaClass)
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        try {
            // Clean inputs
            try {
                $wanted_columns = $schemaClass::SQL_Columns();
            } catch (\Throwable $e) {
                throw new Exception("invalid url $schemaClass");
            }
            simpleLog('api update>>>preprocessed>>>>POST>>>>>' . json_encode((object)$_POST));
            $_POST = array_filter($_POST, function ($key) use ($wanted_columns) {
                return in_array(strtolower($key), $wanted_columns);
            }, ARRAY_FILTER_USE_KEY);
            $_POST = array_map('htmlentities', $_POST);
            $_POST = array_map('trim', $_POST);
            // Consider empty strings as null
            $_POST = array_map(function ($v) {
                return (is_string($v) && strlen($v) == 0) ? NULL : $v;
            }, $_POST);
            // Passwords get special treatment and get hashed
            if (isset($_POST['password']))
                $_POST['password'] = md5($_POST['password']);
            simpleLog(
                'api update>>>>cleaned>>>POST>>>>>' . json_encode((object)$_POST)
            );
            $Model = $this->loadModel('BaseModel');
            if ($Model->experimental_update($schemaClass, $_POST['id'], (object) $_POST)) {
                echo 'updated';
            } else {
                echo 'update unsuccessful';
            }
        } catch (\Throwable $e) {
            simpleLog('Caught exception: ' . $e->getMessage());
            http_response_code(400);
            echo 'Operation Failed';
        }
        pageHit("Api.update");
    }
    public function delete(string $schemaClass)
    {

        $_POST = json_decode(file_get_contents("php://input"), true);
        try {
            if (isset($_POST['ids'])) {
                $Model
                    = $this->loadModel('BaseModel');
                $Model->wipeByIds($schemaClass, $_POST['ids']);
                // foreach ($_POST['ids'] as  $id) {
                //     if (is_numeric($id))
                //         $Model->deleteById($id * 1);
                // }
            } else {
                // Clean inputs
                $_POST = array_map('htmlentities', $_POST);
                $_POST = array_map('trim', $_POST);
                // Consider empty strings as null
                $_POST = array_map(function ($v) {
                    return (is_string($v) && strlen($v) == 0) ? NULL : $v;
                }, $_POST);
                // Get the name of what are we updating
                $className = $_POST['schemaClass'];
                // and also obviously checks is $className is sth we have
                $v = new $className((object) $_POST);
                simpleLog(json_encode($v), 'Api/delete/');
                $Model = $this->loadModel('BaseModel');
                //TODO: check if what he submitted has the same fields
                $Model->deleteById($v->id);
                header('Location:' . URL . 'DashBoard/');
            }
        } catch (\Throwable $e) {
            simpleLog('Caught exception: ' . $e->getMessage());
            http_response_code(400);
            echo 'Operation Failed';
        }
        pageHit("Api.delete");
    }

    function getStats()
    {

        $data = hitStats();

        if (!$data) http_response_code(404);

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));

            $decoded = json_decode($content, true);

            foreach ($data as $key => $value) {
                $decoded[$key] = $value;
            }

            //$decoded['bar'] = "Hello World AGAIN!";    // Add some data to be returned.

            $reply = json_encode($decoded);
        }

        header("Content-Type: application/json; charset=UTF-8");
        echo $reply;
        // ----------------------------------------------------------------------------------------------
    }
}
