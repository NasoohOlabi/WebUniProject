<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require_once './application/models/core/schema.php';
require_once 'application/views/_templates/schema_table.php';
require_once 'application/views/_templates/form.php';

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Users extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {
        // TODO: remove this page or add auth to it
        pageHeadTag('Users List');
        $user = $this->loadModel('UserModel');
        $users  = $user->select([], 'User');
        schema_table($users);

        pageHit('Users.index');
    }

    public function signup()
    {
        session_start();
        pageHeadTag("Signup");
        $database = $this->loadModel('UserModel');
        require_once 'application/views/home/signup.php';
        pageHit('Users.signup');
    }

    public function register()
    {
        session_start();

        $new_user = $this->loadModel('UserModel');

        $_POST = array_map('htmlentities', $_POST);
        $_POST = array_map('trim', $_POST);
        $_POST = array_map(function ($v) {
            return (is_string($v) && strlen($v) == 0) ? NULL : $v;
        }, $_POST);

        if (isset($_FILES['ProfileImg']) && $_FILES['ProfileImg']['name'] != '') {
            $fname = $_POST['username'] . '.' . pathinfo($_FILES['ProfileImg']['name'], PATHINFO_EXTENSION);
            $_POST['profile_picture'] = $fname;
            $target_dir = "./DB/ProfilePics/";
            $target_file = $target_dir . $fname;
            if (!move_uploaded_file($_FILES["ProfileImg"]["tmp_name"], $target_file)) {
                simpleLog("Error storing image");
                echo "Error storing image";
                return;
            };
            $profileImg = $fname;
        }

        $_POST['id'] = -1;
        if (isset($_POST['password']))
            $_POST['password'] = md5($_POST['password']);

        try {
            $u = new User((object) $_POST);
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = "Missing Fields!";
            header("Location:" . URL . 'users/signup/User');
            return;
        }

        simpleLog("Register function running");


        if ($new_user->userIsFound($u->username)) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = "User already exists";
            header("Location:" . URL . 'users/signup/User');
        } else {
            if ($new_user->experimental_insert($u)) {
                $_SESSION['success'] = true;
                $_SESSION['msg'] = "User Added";
                header("Location:" . URL . 'users/signup/User');
            }
        }

        $_POST = array();
        $_FILES = array();
        pageHit('Users.index');
    }

    public function validate()
    {
        session_start();
        $users_model = $this->loadModel('UserModel');
        $_POST = json_decode(file_get_contents("php://input"), true);

        $username = htmlentities($_POST['username']);
        $password = htmlentities($_POST['password']);

        simpleLog("username = $username and password = $password");

        if ($users_model->validateUser($username, $password)) {
            $_SESSION['user'] = $users_model->getFullDetails($username, $password);
            header("Location:" . URL);
        } else {
            echo "Operation Failed : incorrect username or password";
        }

        pageHit('Users.validate');
    }

    function logout()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            simpleLog("User " . $_SESSION['user']->username . "logged out.");
        }
        session_unset();
        header("Location:" . URL);

        pageHit('Users.logout');
    }

    function exist(string $username)
    {
        $users_model = $this->loadModel('UserModel');
        $user_found = $users_model->userIsFound($username);
        $response = ($user_found) ? 'Username Exists' : 'Username Doesn\'t exist';
        echo trim($response);
    }

    function profile()
    {
        session_start();
        if (!isset($_SESSION['user']) || !$_SESSION['loggedIn']) {
            http_response_code(403);
            return;
        }

        $bm = $this->loadModel('BaseModel');

        pageHeadTag("User Profie", ['noform_util' => true]);
        require 'application/views/_templates/user_navbar.php';
        require 'application/views/profile/profile.php';



        echo '</div>';
    }
}
