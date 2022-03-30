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

    function userIsFound($username)
    {
        return ($this->count('User', [], [User::username => $username], true) > 0);
    }

    function insertUser($first_name, $last_name, $username, $password, $role_id, $profile_picture)
    {

        $password = md5($password);
        if ($profile_picture == null)
            $profile_picture = "NULL";
        else
            $profile_picture = "'" . $profile_picture . "'";

        $sql = "INSERT INTO `user` (`id`, `username`, `password`, `first_name`, `last_name`, `middle_name`, `profile_picture`, `role_id`) VALUES (NULL, '$username', '$password', '$first_name', '$last_name', '', $profile_picture, $role_id);";

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
    function getFullDetails($arg1 = null, $arg2 = null)
    {
        $answer = null; // for scoping reasons ... I think
        if ($arg1 && $arg2 && is_string($arg1) && is_string($arg2)) {
            $username = $arg1;
            $password = $arg2;
            $answer =
                $this->join(
                    ['User', 'Role'],
                    [User::role_id => Role::id],
                    [[User::username => $username], [User::password => md5($password)]],
                    true
                )[0];
        } elseif ($arg1 && is_numeric($arg1)) {
            $id = $arg1;
            $answer =
                $this->join(
                    ['User', 'Role'],
                    [User::role_id => Role::id],
                    [User::id => $id],
                    true
                )[0];
        } else {
            return;
        }


        $tmp = $this->join(
            ['Permission', 'Role_Has_Permission'],
            [Permission::id => Role_Has_Permission::permission_id],
            [Role_Has_Permission::role_id => $answer->role_id],
            true
        );
        simpleLog("$username details looked up : " . json_encode($answer));
        $answer->permissions = $tmp;
        return $answer;
    }
}
