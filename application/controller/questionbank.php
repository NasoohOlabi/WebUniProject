<?php

/**
 * Class Home
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */
class QuestionBank extends Controller
{
    public function index()
    {
        return '.';
    }
    public function Add()
    {
        session_start();

        if (
            isset($_POST['text']) && isset($_POST['number_of_choices'])
            && isset($_POST['password'])
        ) {

            // Data validation
            if (strlen($_POST['name']) < 1 || strlen($_POST['password']) < 1) {
                $_SESSION['error'] = 'Missing data';
                header("Location: add.php");
                return;
            }

            if (strpos($_POST['email'], '@') === false) {
                $_SESSION['error'] = 'Bad data';
                header("Location: add.php");
                return;
            }

            // $sql = "INSERT INTO users (name, email, password)
            //   VALUES (:name, :email, :password)";
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute(array(
            //     ':name' => $_POST['name'],
            //     ':email' => $_POST['email'],
            //     ':password' => $_POST['password']
            // ));
            $_SESSION['success'] = 'Record Added';
            // header('Location: index.php');
            return;
        }
        $model = $this->loadModel('ExamModel');
        $topics = $model->select([], "Topic");

        var_dump($topics);

        // Flash pattern
        if (isset($_SESSION['error'])) {
            echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
            unset($_SESSION['error']);
        }
        require_once 'application/views/questionbank/add.php';
    }
}
