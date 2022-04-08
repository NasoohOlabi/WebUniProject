<?php
require_once 'basemodel.php';
require_once './application/libs/util/Option.php';
require_once 'core/schema.php';
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
        $sql = "SELECT role.name as role, permission.name as permission FROM `Role_Has_Permission` JOIN `role` JOIN permission WHERE role_id=role.id AND permission_id=permission.id;";
        $this->db->prepare($sql)->execute();
    }
    function getRolePermissions($role)
    {
        if (is_numeric($role)) {
            $sql = "SELECT role.name as role, permission.name as permission FROM `Role_Has_Permission` JOIN `role` JOIN permission WHERE role_id=:role AND permission_id=permission.id;";
            $this->db->prepare($sql)->execute([":role" => $role]);
        } else if (is_string($role)) {
            $sql = "SELECT role.name as role, permission.name as permission FROM `Role_Has_Permission` JOIN `role` JOIN permission WHERE role.name=:role AND role_id=role.id AND permission_id=permission.id;";
            $this->db->prepare($sql)->execute([":role" => $role]);
        }
    }
    function permissionExists($permission)
    {
        if (is_numeric($permission)) {
            $result = $this->count('Permission', [Permission::id => $permission]);
        } else if (is_string($permission)) {
            $result = $this->count('Permission', [Permission::name => $permission]);
        } else {
            throw new Exception("Invalid Argument for permissionExists");
        }
        if ($result > 1) throw new Exception("SQL Error both id and name should be unique");
        return $result == 1;
    }
    function permissionId($permission)
    {
        if (is_numeric($permission)) {
            $result = $this->select([], 'Permission', [Permission::id => $permission]);
        } else if (is_string($permission)) {
            $result = $this->select([], 'Permission', [Permission::name => $permission]);
        } else {
            throw new Exception("Invalid Argument for permissionId");
        }
        if (count($result) == 1) {
            return $result->id;
        } elseif (count($result) > 1) {
            throw new Exception("Internal SQL error: Permission's name and id are unique");
        } else {
            throw new Exception("Permission : $permission Doesn't Exists");
        }
    }
    function roleExists($role)
    {
        if (is_numeric($role)) {
            $result = $this->count('Role', [Role::id => $role]);
        } else if (is_string($role)) {
            $result = $this->count('Role', [Role::name => $role]);
        } else {
            throw new Exception("Invalid Argument for roleExists");
        }
        if ($result > 1) throw new Exception("SQL Error both id and name should be unique");
        return $result == 1;
    }
    function roleId($role)
    {
        if (is_numeric($role)) {
            $result = $this->select([], 'role', [Role::id => $role]);
        } else if (is_string($role)) {
            $result = $this->select([], 'role', [Role::name => $role]);
        } else {
            throw new Exception("Invalid Argument for roleId");
        }
        if (count($result) == 1) {
            return $result->id;
        } elseif (count($result) > 1) {
            throw new Exception("Internal SQL error: Permission's name and id are unique");
        } else {
            throw new Exception("Role : $role Doesn't Exists");
        }
    }
    function addPermissionToRole($permission, $role)
    {
        $link = new Role_Has_Permission();
        $link->permission_id = $this->permissionId($permission);
        $link->role_id = $this->roleId($role);
        return $this->insert($link);
    }
    function hasPermission($role, $permission)
    {
        $cnt = $this->count(
            'Role_Has_Permission',
            [
                [Role_Has_Permission::role_id => $this->roleId($role)],
                [Role_Has_Permission::permission_id => $this->permissionId($permission)]
            ]
        );
        if ($cnt > 1)
            throw new Exception("SQL error: Permission's name and id should be unique");
        return ($cnt == 1);
    }
}
