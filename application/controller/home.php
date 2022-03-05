<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require_once 'application/models/core/schema.php';


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
        session_start();
        simpleLog("index called");



        $p = $this->loadModel('QuestionModel');

        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");

        if (isset($_SESSION['loggedIn'])) {
            require 'application/views/_templates/user_navbar.php';
            require 'application/views/home/user_index.php';
        } else {
            require 'application/views/_templates/navbar.php';
            //require 'application/views/_templates/aside.php';
            require 'application/views/home/exp_index.php';
            require 'application/views/_templates/login_popup.php';
            if (isset($_GET['login_failed'])) {
                echo '<script type="text/javascript">',
                'pop();',
                'toggleError();',
                '</script>';
            }
        }

        //require 'application/views/_templates/footer.php';
    }
}
