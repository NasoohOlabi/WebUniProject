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

    function userIsFound($id)
    {
    }

    function getUserId($email)
    {

        $username = explode('@', $email)[0];
    }
}
