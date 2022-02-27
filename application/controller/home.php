<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';


/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class Home extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';

        simpleLog("index called");



        $p = $this->loadModel('QuestionModel');
        echo "<pre>";
        // // print("before");
        // var_dump($p->questionDetails(1));
        // $p->insert(array("name" => "admin"));
        // print("after");
        // var_dump($p->getAll());
        // var_dump([[1, 2] => [2, 3]]);
        // var_dump("3" * 5);
        $v = "22";
        $v = str_repeat($v, 3);
        var_dump($v);

        echo "</pre>";


        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        require 'application/views/home/index.php';
        require 'application/views/_templates/login_popup.php';
        require 'application/views/_templates/footer.php';
    }
    public function signup()
    {
        simpleLog("signup called");
        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("Signup");
        require_once 'application/views/home/signup.php';
    }
}
