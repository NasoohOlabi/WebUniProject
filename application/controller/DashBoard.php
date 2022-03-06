<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require './application/models/core/schema.php';
require_once 'application/views/_templates/schema_table.php';
require_once 'application/views/_templates/form.php';

function is_ROOT__ADMIN()
{
    session_start();
    if (!(isset($_SESSION['user']) && $_SESSION['user']->role->name == 'ROOT::ADMIN')) {
        header('Location:' . URL);
        simpleLog("he is trying to hack us " . json_encode($_SESSION['user']), 'users/');
        return;
    }
    simpleLog("access Granted to " . json_encode($_SESSION['user']), 'users/');
}
class DashBoard extends Controller
{
    public function index()
    {
        is_ROOT__ADMIN();

        simpleLog("dashboard called");
        $bm = $this->loadModel('BaseModel');

        require './application/views/Dashboard/index.php';
        pageHit("dashboard.index");
    }

    public function add()
    {
        is_ROOT__ADMIN();

        $bm = $this->loadModel('BaseModel');

        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        echo '<div id="main-content" class="inlineBlock">';
        foreach ([
            'Question', 'Role', 'Exam', 'Subject', 'Topic', 'Question',
            'Choice', 'Permission', 'Role_has_Permission', 'User'
        ] as $val) {
            $q = new $val();
            FormForThis($q, $bm);
        }
        echo '</div></div>';

        require 'application/views/_templates/footer.php';
        pageHit("dashboard.Add");
    }
    public function view()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';
        is_ROOT__ADMIN();

        $bm = $this->loadModel('BaseModel');
        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        echo '<div id="main-content" class="inlineBlock">';

        foreach ([
            'Question', 'Role', 'Exam', 'Subject', 'Topic', 'Question',
            'Choice', 'Permission', 'Role_has_Permission', 'User'
        ] as $val) {
            $entries  = $bm->select([], $val);
            schema_table($entries);
        }

        echo '</div></div>';

        require 'application/views/_templates/footer.php';

        pageHit("dashboard.view");
    }
}
