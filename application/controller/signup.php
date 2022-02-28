<?php

require_once 'application/views/_templates/header.php';

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class SignUp extends Controller
{
    public function index()
    {
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

        $profileImg = (isset($_POST['ProfileImg']) && $_POST['ProfileImg'] != '') ? $_POST['ProfileImg'] : null;

        $data = [$first_name, $last_name, $email, $phone, $password, $account_type, $profileImg];

        echo $profileImg;
        echo 'SIGNUP HERE';
        print_r($data);

        $u = new User();

        $u->first_name = $first_name;
        $u->last_name = $last_name;
        $u->username = explode('@', $email)[0];
        $u->password = md5($password);
        $u->role_id = 1;
        $u->profile_picture = $profileImg;

        var_dump($u);

        if (!$new_user->userIsFound($email)) {
            $new_user->experimental_insert($u);
        }
        echo '</pre>';

        //TODO: check if user already exists... then add to db if not

        // if ($new_user->userIsFound()) {
        //     echo "User already exists";
        // } else {
        //     echo "New User";
        // }


    }
}
