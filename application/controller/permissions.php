<?php
require_once './application/libs/util/log.php';
require_once './application/models/core/schema.php';


class permissions extends Controller
{
	public function revoke()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);
		$permission_id = $_POST['permission_id'] ?? null;
		$role_id = $_POST['role_id'] ?? null;

		if ($permission_id == null || $role_id == null) {
			$this->response(400, "Bad Request");
			return;
		}

		$permission_model = $this->loadModel('PermissionModel');
		$permission_model->revoke($permission_id, $role_id);
		$this->response(200, 'deleted');
	}
}
