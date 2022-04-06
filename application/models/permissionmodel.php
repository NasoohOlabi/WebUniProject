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
    function updatePermissions($parent_id, $permission_ids)
    {


        $sql = "DELETE FROM `role_has_permission` WHERE role_has_permission.role_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$parent_id]);

        $bindings = [];
        foreach ($permission_ids as  $id) {
            $bindings[] = $parent_id;
            $bindings[] = $id;
        }

        // map permission_ids to string (?, ?)
        $sql = "INSERT INTO `role_has_permission` (`role_id`, `permission_id`) VALUES " . implode(",", array_fill(0, count($permission_ids), "(?, ?)"));
        $stmt = $this->db->prepare($sql);
        $stmt->execute($bindings);
        
        
    }
    function revoke(int $permission_id , int $role_id)
    {
        $sql = "DELETE FROM `role_has_permission` WHERE role_has_permission.role_id = ? AND role_has_permission.permission_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$role_id, $permission_id]);
    }
}
