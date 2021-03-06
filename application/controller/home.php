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

        $p = $this->loadModel('QuestionModel');

        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index", ['noform' => true, 'Swal' => true]);

        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
            require 'application/views/_templates/user_navbar.php';
            $role = $_SESSION['user']->role->name;
            $username = $_SESSION['username'];

            switch ($role) {
                case 'TestAdmin':
                    $content =
                        'application/views/home/_content/__test_center_admin_content.php';
                    break;
                case 'Student':
                    $content =
                        'application/views/home/_content/__student_content.php';
                    break;
                default:
                    $content =
                        null;
                    break;
            }
            require 'application/views/home/user_index.php';
        } else {
            require 'application/views/_templates/navbar.php';
            require 'application/views/home/index.php';
            require 'application/views/_templates/login_popup.php';
            if (isset($_GET['login_failed'])) {
                echo '<script type="text/javascript">',
                'pop();',
                'toggleError();',
                '</script>';
            }
        }

        pageHit('home.index');
    }
}
