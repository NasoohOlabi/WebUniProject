<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require_once './application/models/core/schema.php';
require_once 'application/views/_templates/user_table.php';

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

        $user = $this->loadModel('UserModel');
        $users  = $user->select([], 'User');
        user_table($users);
        // foreach ($users as $schemaClass) {
        // }
    }

    public function signup()
    {
        session_start();
        pageHeadTag("Signup");
        require_once 'application/views/home/signup.php';
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
        $profileImg = (isset($_POST['ProfileImg']) && $_POST['ProfileImg'] != '') ? $_POST['ProfileImg'] : null;


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

        $data = [$first_name, $last_name, $email, $phone, $password, $account_type, $profileImg];

        // echo $profileImg;
        // echo 'SIGNUP HERE';
        // print_r($data);

        $u = new User();

        $u->first_name = $first_name;
        $u->last_name = $last_name;
        $u->username = $username;
        $u->password = md5($password);
        $u->role_id = 1;
        $u->profile_picture = $profileImg;

        // var_dump($u);

        $arr = [];

        $arr['first_name'] = $first_name;
        $arr['last_name'] = $last_name;
        $arr['username'] = $username;
        $arr['password'] = md5($password);
        $arr['role_id'] = 0;
        $arr['profile_picture'] = '1';
        $arr['middle_name'] = '';

        //var_dump($new_user->insert($arr));
        //var_dump($new_user->userIsFound($email));


        echo '</pre>';

        if ($new_user->userIsFound($email))
            echo "User already exists";
        else
            if ($new_user->insertUser($first_name, $last_name, $username, $password, $role_id, $profileImg)) {
            echo "User Added";
        }
    }

    public function validate()
    {
        session_start();
        $new_user = $this->loadModel('UserModel');

        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);

        echo "got the user";

        if ($new_user->validateUser($email, $password)) {
            header("Location:" . URL);
        } else {
            echo "Login failed";
        }
    }
}
