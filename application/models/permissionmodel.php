<?php
require_once 'basemodel.php';
require_once 'rolemodel.php';
class PermissionModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "permission");
    }

    function createPermission($name, $ActorRole)
    {
        $p = new $this->loadModel("RoleModel");
        if ($p->hasPermission($ActorRole, "write_permission"))
            parent::insert(array("name" => $name));
    }
}
