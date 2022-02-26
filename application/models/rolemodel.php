<?php
require_once 'basemodel.php';
require_once './application/libs/util/Option.php';
class RoleModel extends BaseModel
{
    function __construct($db)
    {
        parent::__construct($db, "role");
    }

    function createRole($name)
    {
        parent::insert(array("name" => $name));
    }
    function getAllRolesWithPermissions()
    {
        $sql = "SELECT role.name as role, permission.name as permission FROM `role_has_permission` JOIN `role` JOIN permission WHERE role_id=role.id AND permission_id=permission.id;";
        $this->db->prepare($sql)->execute();
    }
    function getRolePermissions($role)
    {
        if (is_numeric($role)) {
            $sql = "SELECT role.name as role, permission.name as permission FROM `role_has_permission` JOIN `role` JOIN permission WHERE role_id=:role AND permission_id=permission.id;";
            $this->db->prepare($sql)->execute([":role" => $role]);
        } else if (is_string($role)) {
            $sql = "SELECT role.name as role, permission.name as permission FROM `role_has_permission` JOIN `role` JOIN permission WHERE role.name=:role AND role_id=role.id AND permission_id=permission.id;";
            $this->db->prepare($sql)->execute([":role" => $role]);
        }
    }
    function permissionExists($permission)
    {
        if (is_numeric($permission)) {
            $sql = "SELECT * FROM `permission` WHERE id=:permission;";
        } else if (is_string($permission)) {
            $sql = "SELECT * FROM `permission` WHERE name=:permission;";
        } else {
            return new Either\Err("Invalid Argument for permissionExists");
        }
        $query = $this->db->prepare($sql);
        $query->execute([":permission" => $permission]);
        $line = $query->fetchAll();
        if (count($line)) {
            return new Either\Result($line[0]->id);
        } else {
            return new Either\Err("Permission $permission doesn't exists");
        }
    }
    function roleExists($role)
    {
        if (is_numeric($role)) {
            $sql = "SELECT * FROM `role` WHERE id=:role;";
        } else if (is_string($role)) {
            $sql = "SELECT * FROM `role` WHERE name=:role;";
        } else {
            return new Either\Err("Role $role doesn't exists");
        }
        $query = $this->db->prepare($sql);
        $query->execute([":role" => $role]);
        $line = $query->fetchAll();
        if (count($line)) {
            return new Either\Result($line[0]->id);
        } else {
            return new Either\Err("Role $role doesn't exists");
        }
    }
    function addPermissionToRole($permission, $role)
    {
        $permission_check = $this->permissionExists($permission);
        $role_check = $this->roleExists($role);
        if ($permission_check instanceof Either\Result && $role_check instanceof Either\Result) {
            $sql = "INSERT INTO `role_has_permission`(`role_id`, `permission_id`) VALUES (:role,:permission)";
            simpleLog("Running $sql");
            $succeeded = $this->db->prepare($sql)->execute([":role" => $role_check->result, ":permission" => $permission_check->result]);
            return ($succeeded) ? $permission_check->combine($role_check) : new Either\Err("$sql was not succeeded");
        } else {
            return $permission_check->combine($role_check);
        }
    }
    function hasPermission($role, $permission)
    {
        $permission_check = $this->permissionExists($permission);
        $role_check = $this->roleExists($role);
        if ($permission_check instanceof Either\Result && $role_check instanceof Either\Result) {
            $sql = "SELECT * FROM `role_has_permission` WHERE role_id=$role_check->result AND permission_id=$permission_check->result";
            simpleLog("Running $sql");
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":role" => $role_check->result, ":permission" => $permission_check->result]);
            $rows = $stmt->fetchAll();
            if (count($rows) == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            simpleLog($permission_check->combine($role_check)->left);
            return false;
        }
    }
}
