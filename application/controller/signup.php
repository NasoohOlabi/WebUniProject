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




        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $account_type = $_POST['AccountType'];
        $profileImg = $_POST['ProfileImg'];

        $data = [$first_name, $last_name, $email, $phone, $password, $account_type, $profileImg];

        echo 'SIGNUP HERE';
        print_r($data);

        //TODO: check if user already exists... then add to db if not

        // if ($new_user->userIsFound()) {
        //     echo "User already exists";
        // } else {
        //     echo "New User";
        // }


    }
}
