<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require './application/models/core/schema.php';
require_once 'application/views/_templates/schema_table.php';


function simple_auth()
{

    session_start();
    if (!(isset($_SESSION['user']) && $_SESSION['user']->role->name == 'ROOT::ADMIN')) {
        header('Location:' . URL);
        simpleLog("he is trying to hack us " . json_encode($_SESSION['user']));
        return;
    }
    simpleLog("access Granted to " . json_encode($_SESSION['user']));
}
/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class DashBoard extends Controller
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function index()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';
        simple_auth();

        simpleLog("dashboard called");
        $bm = $this->loadModel('BaseModel');

        require './application/views/Dashboard/index.php';
    }
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function add()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';
        simple_auth();

        simpleLog("dashboard add called");



        $bm = $this->loadModel('BaseModel');

        require_once 'application/views/_templates/form.php';

        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        // require ''
        echo '<div id="main-content" class="inlineBlock">';
        foreach ([
            'Question', 'Role', 'Exam', 'Subject', 'Topic', 'Question',
            'Choice', 'Permission', 'Role_has_Permission', 'User'
        ] as $val) {
            $q = new $val();
            getThisFromForm($q, $bm);
        }
        echo '</div></div>';

        // require 'application/views/home/index.php';
        // require 'application/views/_templates/login_popup.php';
        require 'application/views/_templates/footer.php';
    }
    public function view()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';


        simple_auth();


        simpleLog("dashboard add called");



        $bm = $this->loadModel('BaseModel');

        require_once 'application/views/_templates/form.php';

        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        // require ''
        echo '<div id="main-content" class="inlineBlock">';
        foreach ([
            'Question', 'Role', 'Exam', 'Subject', 'Topic', 'Question',
            'Choice', 'Permission', 'Role_has_Permission', 'User'
        ] as $val) {
            $entries  = $bm->select([], $val);
            schema_table($entries);
        }
        echo '</div></div>';

        // require 'application/views/home/index.php';
        // require 'application/views/_templates/login_popup.php';
        require 'application/views/_templates/footer.php';
    }
}
