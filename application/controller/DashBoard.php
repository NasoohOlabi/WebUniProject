<?php
require_once 'application/views/_templates/header.php';
require './application/models/core/schema.php';
require_once 'application/views/_templates/schema_table.php';
require_once 'application/views/_templates/form.php';


class DashBoard extends Controller
{

    public array $forms = ['question', 'role', 'exam', 'subject', 'topic', 'choice', 'permission', 'role_has_permission', 'user', 'exam_center', 'student', 'student_exam_has_question', 'student_exam_has_choice', 'student_exam'];


    public function index()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            if (!sessionUserHasRole('ROOT::ADMIN')) {
                $this->redirectToIndex_flash_message(
                    Language::t('You don\'t have permission to access this page!')
                );
                return;
            }
        } else {
            $this->redirectToIndex_flash_message(
                Language::t('You probably forgot to login!')
            );
            return;
        }

        simpleLog("dashboard called");
        $bm = $this->loadModel('BaseModel');

        $stats = hitStats();

        require './application/views/Dashboard/index.php';
        pageHit("dashboard.index");
    }

    public function add($form)
    {
        session_start();
        if (isset($_SESSION['user'])) {
            if (!sessionUserHasRole('ROOT::ADMIN')) {
                $this->redirectToIndex_flash_message(
                    Language::t('You don\'t have permission to access this page!')
                );
                return;
            }
        } else {
            $this->redirectToIndex_flash_message(
                Language::t('You probably forgot to login!')
            );
            return;
        }

        $bm = $this->loadModel('BaseModel');
        pageHeadTag("Add $form", ['Swal' => true]);

        require 'application/views/_templates/user_navbar.php';
        require './application/views/Dashboard/add.php';


        pageHit("dashboard.Add");
    }

    public function update($form)
    {
        session_start();
        if (isset($_SESSION['user'])) {
            if (!sessionUserHasRole('ROOT::ADMIN')) {
                $this->redirectToIndex_flash_message(
                    Language::t('You don\'t have permission to access this page!')
                );
                return;
            }
        } else {
            $this->redirectToIndex_flash_message(
                Language::t('You probably forgot to login!')
            );
            return;
        }



        $parent_id = isset($_GET['parent_id'])
            ? (int) $_GET['parent_id']
            : null;

        if (is_numeric($parent_id) && strtolower($form) === 'role_has_permission') {

            $_POST =
                json_decode(file_get_contents("php://input"), true);

            if (isset($_POST['permission_ids'])) {

                $bm = $this->loadModel('PermissionModel');

                $bm->updatePermissions($parent_id, $_POST['permission_ids']);

                $_SESSION['user']->permissions =
                    $bm->select([], 'Permission', [Permission::id => $parent_id], 1000, true);

                $_SESSION['flash_message'] = 'Permissions Updated';
                header('Location:' . URL . 'dashboard');
            }

            $bm = $this->loadModel('BaseModel');
            pageHeadTag("Add $form", ['Swal' => true]);

            require 'application/views/_templates/user_navbar.php';
            $cls = $bm->getById($parent_id, "Role");

            require 'application/views/Dashboard/tags.php';
        } else {


            $_POST = array_map('htmlentities', $_POST);


            $bm = $this->loadModel('BaseModel');

            pageHeadTag("index");
            require 'application/views/_templates/user_navbar.php';
            //require 'application/views/_templates/aside.php';
            echo '<div id="main-content" class="inlineBlock">';

            if (in_array($form, $this->forms)) {
                $q = new $form((object)$_POST);
                FormForThis($q, $bm);
            }

            echo '</div></div>';

            require 'application/views/_templates/footer.php';
        }
        pageHit("dashboard.update");
    }


    public function view()
    {
        // debug message to show where you are, just for the demo
        // echo 'Message from Controller: You are in the controller home, using the method index()';
        session_start();
        if (isset($_SESSION['user'])) {
            if (!sessionUserHasRole('ROOT::ADMIN')) {
                $this->redirectToIndex_flash_message(
                    Language::t('You don\'t have permission to access this page!')
                );
                return;
            }
        } else {
            $this->redirectToIndex_flash_message(
                Language::t('You probably forgot to login!')
            );
            return;
        }

        $bm = $this->loadModel('BaseModel');
        // load views. within the views we can echo out $songs and $amount_of_songs easily
        pageHeadTag("index");
        require 'application/views/_templates/navbar.php';
        require 'application/views/_templates/aside.php';
        echo '<div id="main-content" class="inlineBlock">';

        foreach ($this->forms as $val) {
            $entries  = $bm->select([], $val);
            schema_table($entries);
        }

        echo '</div></div>';

        require 'application/views/_templates/footer.php';

        pageHit("dashboard.view");
    }
}
