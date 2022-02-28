<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require './application/models/core/schema.php';

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

        simpleLog("dashboard called");



        $p = $this->loadModel('QuestionModel');
        echo "<pre>";
        // // print("before");
        // var_dump($p->questionDetails(1));
        // [[1=>2],[[1=>2],[3=>4]]]
        // print_r([1 => 1, 1 => 2]);
        // var_dump($p->count('Role_has_Permission', [[1 => 2], [1 => 3], [3 => 2], [4 => 5]]));
        // $p->insert(array("name" => "admin"));
        // var_dump(md5("afjk;lsdjlafkjssssssssssssssssssssssssssssssssssk"));
        var_dump("hi");
        // require_once 'application/views/_templates/form.php';
        // $q = new Question();
        // print("after");
        // var_dump($p->getAll());
        // var_dump([[1, 2] => [2, 3]]);
        // var_dump("3" * 5);
        // getThisFromForm($q);
        // $v = "22";
        // $v = str_repeat($v, 3);
        // var_dump($v);

        echo "</pre>";


        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        require 'application/views/home/index.php';
        require 'application/views/_templates/login_popup.php';
        require 'application/views/_templates/footer.php';
    }
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/home/index (which is the default page btw)
     */
    public function add()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';

        simpleLog("dashboard add called");



        // $p = $this->loadModel('QuestionModel');
        echo "<pre>";
        // // print("before");
        // var_dump($p->questionDetails(1));
        // [[1=>2],[[1=>2],[3=>4]]]
        // print_r([1 => 1, 1 => 2]);
        // var_dump($p->count('Role_has_Permission', [[1 => 2], [1 => 3], [3 => 2], [4 => 5]]));
        // $p->insert(array("name" => "admin"));
        // var_dump(md5("afjk;lsdjlafkjssssssssssssssssssssssssssssssssssk"));
        // var_dump("hi");
        require_once 'application/views/_templates/form.php';
        // print("after");
        // var_dump($p->getAll());
        // var_dump([[1, 2] => [2, 3]]);
        // var_dump("3" * 5);
        // $v = "22";
        // $v = str_repeat($v, 3);
        // var_dump($v);
        $f = function (array $acc, array $v) {
            $acc['prepare'][] = $v['prepare'];
            $acc['execute'][] = $v['execute'];
            return $acc;
        };
        $answer = ['prepare' => [], 'execute' => []];
        var_dump($answer);
        var_dump($f($answer, ['prepare' => 'hi', 'execute' => 'bye']));
        var_dump($answer);

        echo "</pre>";


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
            getThisFromForm($q);
        }
        echo '</div></div>';

        // require 'application/views/home/index.php';
        // require 'application/views/_templates/login_popup.php';
        require 'application/views/_templates/footer.php';
    }
}
