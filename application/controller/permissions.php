<?php


class permissions extends Controller
{
	public function revoke()
	{
		$_POST = json_decode(file_get_contents("php://input"), true);
		$permission_id = $_POST['permission_id'] ?? null;
		$role_id = $_POST['role_id'] ?? null;

		if ($permission_id == null || $role_id == null) {
			header("HTTP/1.1 " . 400 . " Not Found");
			echo json_encode(array(
				"status" => 400, "message" => "Bad Request"));
			return;
		}

		$permission_model = $this->loadModel('PermissionModel');
		$permission_model->revoke($permission_id, $role_id);
		header("HTTP/1.1 " . 200 . " Redirect");
		echo json_encode(array("status" => 200, "message" => 'deleted'));

	}
}
