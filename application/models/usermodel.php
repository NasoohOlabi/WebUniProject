<?php
require_once 'basemodel.php';
require_once 'rolemodel.php';
class UserModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "users");
        simpleLog("USERMODEL");
    }

    function userIsFound($email)
    {
        $username = explode('@', $email)[0];

        $sql = "SELECT * FROM " . $this->table . " WHERE username = " . $username;

        simpleLog('Running : "' . $sql . '"');

        $query = $this->db->prepare($sql);
        $query->execute();
        $arr = $query->fetchAll();

        if ($arr)
            return true;

        return false;
    }
}
