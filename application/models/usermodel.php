<?php
require_once 'basemodel.php';
require_once 'rolemodel.php';
class UserModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "user");
        simpleLog("USERMODEL");
    }

    function userIsFound($email)
    {
        $username = explode('@', $email)[0];
        return ($this->count('User', [], [User::username => $username]) > 0);
    }

    function insertUser($first_name, $last_name, $username, $password, $role_id, $profile_picture)
    {

        //TODO: insert profile picture too
        $password = md5($password);
        $sql = "INSERT INTO `user` (`id`, `username`, `password`, `first_name`, `last_name`, `middle_name`, `profile_picture`, `role_id`) VALUES (NULL, '$username', '$password', '$first_name', '$last_name', '', NULL, $role_id);";

        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = $query->fetchAll();
        // TODO: talk about whether assigning sessions is the model's job
        if ($arr) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
        }

        return $arr;
    }

    function validateUser($email, $password)
    {
        $password = md5($password);
        $username = explode('@', $email)[0];

        $sql = "SELECT * FROM `user` WHERE `username` = '$username' AND `password` = '$password';";
        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = $query->fetchAll();
        if (count($arr) == 1) {
            // TODO: talk about whether assigning sessions is the model's job
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    }
}
