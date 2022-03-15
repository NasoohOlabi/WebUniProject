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
        require_once 'application/views/home/signup.php';
        pageHit('Users.signup');
    }

    public function register()
    {

        $new_user = $this->loadModel('UserModel');

        // <pre> tag is great for debugging! :)
        echo '<pre>';

        $first_name = htmlentities($_POST['first_name']);
        $last_name =  htmlentities($_POST['last_name']);
        $email = htmlentities($_POST['email']);
        $phone = htmlentities($_POST['phone']);
        $password = htmlentities($_POST['password']);
        $account_type = htmlentities($_POST['AccountType']);
        $username = explode('@', $email)[0];
        $role_id = null;
        $profileImg = null;

        if (isset($_FILES['ProfileImg']) && $_FILES['ProfileImg']['name'] != '') {
            $fname = $username . '.' . pathinfo($_FILES['ProfileImg']['name'], PATHINFO_EXTENSION);
            $target_dir = "./DB/ProfilePics/";
            $target_file = $target_dir . $fname;
            if (!move_uploaded_file($_FILES["ProfileImg"]["tmp_name"], $target_file)) {
                simpleLog("Error storing image");
                echo "Error storing image";
                return;
            };
            $profileImg = $fname;
        }


        switch ($account_type) {
            case 'admin':
                $role_id = 1;
                break;
            case 'teacher':
                $role_id = 2;
                break;
            default:
                $role_id = 3;
                break;
        }

        $u = new User();

        $u->first_name = $first_name;
        $u->last_name = $last_name;
        $u->username = $username;
        $u->password = md5($password);
        $u->role_id = 1;
        $u->profile_picture = $profileImg;


        $arr = [];

        $arr['first_name'] = $first_name;
        $arr['last_name'] = $last_name;
        $arr['username'] = $username;
        $arr['password'] = md5($password);
        $arr['role_id'] = 0;
        $arr['profile_picture'] = '1';
        $arr['middle_name'] = '';


        simpleLog("Register function running");

        echo '</pre>';

        if ($new_user->userIsFound($email))
            echo "User already exists";
        else
            if ($new_user->insertUser($first_name, $last_name, $username, $password, $role_id, $profileImg)) {
            echo "User Added";
        }

        $_POST = array();
        $_FILES = array();
        pageHit('Users.index');
    }

    public function validate()
    {
        session_start();
        $users_model = $this->loadModel('UserModel');

        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);

        $username = explode('@', $email)[0];
        if ($users_model->validateUser($email, $password)) {
            $_SESSION['user'] = $users_model->getFullDetails($username, $password);
            header("Location:" . URL);
        } else {
            header("Location:" . URL . 'home?login_failed=true');
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

    function profile()
    {
        session_start();
        if (!isset($_SESSION['user']) || !$_SESSION['loggedIn']) {
            http_response_code(403);
            return;
        }

        $bm = $this->loadModel('BaseModel');

        pageHeadTag("User Profie");
        require 'application/views/_templates/user_navbar.php';
        require 'application/views/profile/profile.php';



        echo '</div>';
    }
}
