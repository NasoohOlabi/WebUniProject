<?php

require_once 'application/views/_templates/header.php';

function is_display_key($key)
{
	return !endsWith($key, 'id')
		&& $key !== 'identifying_fields'
		&& $key !== 'profile_picture'
		&& $key !== "dependents"
		&& $key !== "active";
}
class Api extends Controller
{
	public function index()
	{
		require 'application/views/api/index.php';
		pageHit("Api.index");
	}
	public function create($className = null)
	{
		session_start();

		// allow for native form requests but also for ajax requests
		if (count($_POST) === 0)
			$_POST = json_decode(file_get_contents("php://input"), true);

		// Clean inputs
		$_POST = array_map('htmlentities', $_POST);
		$_POST = array_map('trim', $_POST);

		// Consider empty strings as null
		$_POST = array_map(function ($v) {
			return (is_string($v) && strlen($v) == 0) ? NULL : $v;
		}, $_POST);

		// Passwords get special treatment and get hashed
		if (isset($_POST['password']))
			$_POST['password'] = md5($_POST['password']);

		// Pseudo id since we don't have one
		// Note that the constructor needs the field id
		// While the insert Operation drops id and doesn't take in consideration
		$_POST['id'] = -1;

		try {
			// The constructor checks if the required field are satisfied
			// and also obviously checks is $className is sth we have
			$v = new $className((object) $_POST);
		} catch (\Throwable $e) {
			simpleLog('Caught exception: ' . $e->getMessage(), 'api/create');
			echo 'Operation Failed : ' . 'Missing or improper fields Caught exception: ' . str_replace('::', ' ', str_replace('$', ' ', $e->getMessage()));
			return;
		}


		simpleLog(json_encode($v), 'api/create/');
		$Model = $this->loadModel('BaseModel');
		try {
			if ($Model->insert($v)) {
				echo json_encode($v);
			} else {
				echo "Operation Failed : insert $className failed ";
			}
		} catch (AccessDeniedException $e) {
			simpleLog('Caught exception: ' . $e->getMessage(), 'api/create');
			echo 'Operation Failed : ' . 'Permission Denied: ' . str_replace('::', ' ', str_replace('$', ' ', $e->getMessage()));
			return;
		} catch (\Throwable $e) {
			simpleLog('Caught exception: ' . $e->getMessage(), 'api/create');
			echo 'Operation Failed : ' . str_replace('::', ' ', str_replace('$', ' ', $e->getMessage()));
			return;
		}
		// header('Location:' . URL . 'DashBoard/');
		pageHit("Api.create");
	}
	public function read(?string $schemaClass = null, ?string $field_or_id = null, ?string $needle = null)
	{
		session_start();
		if (count($_POST) === 0)
			$_POST = json_decode(file_get_contents("php://input"), true);

		if ($schemaClass == null) {
			// invalid request
			// echo "Operation Failed : $schemaClass is unknown to the system";
			echo "Operation Failed : read what?";

			simpleLog(' schemaClass == null $_POST ' . json_encode($_POST) . ' failed', 'api/read/');

			return;
		}

		$Model = $this->loadModel('BaseModel');

		simpleLog("schemaClass $schemaClass && field_or_id $field_or_id && is_string($field_or_id) " . is_string($field_or_id) . " && needle $needle", 'api/read/');

		// targeted calls
		if (isset($schemaClass) && isset($field_or_id) && is_numeric($field_or_id)) {
			$id = intval($field_or_id);
			echo json_encode($Model->select([], $schemaClass, [$schemaClass::id => $id])[0]);
			pageHit("Api.read");
			return;
		} elseif (isset($schemaClass) && isset($field_or_id) && is_string($field_or_id) && isset($needle)) {
			$field = $field_or_id;
			if (in_array($field, $schemaClass::SQL_Columns())) {
				if (str_contains(strtolower($schemaClass), '_has_') && $field !== 'id' && endsWith($field, '_id')) {

					$middle_table_one_col = $schemaClass::access($field);

					$one_name = substr($field, 0, strlen($field) - 3);

					$many_name =  str_replace('_has_', '', str_replace($one_name, '', $schemaClass));

					$middle_table = $schemaClass;

					$middle_table_many_col =
						$schemaClass::access(strtolower($many_name) . '_id');
					try {
						echo json_encode($Model->join(
							[
								$many_name,
								$middle_table
							],
							// safe conditions
							[
								[$many_name::id =>
								$middle_table_many_col],
								[$middle_table_one_col => $needle]
							]
						));
					} catch (AccessDeniedException $th) {
						simpleLog("AccessDeniedException $th", 'api/read/');
						simpleLog("You have access to $schemaClass but: " . $th->getMessage(), 'api/');
						echo ('Operation Failed : Access Denied: ' . $th->getMessage());
					}
				} else {
					echo json_encode($Model->select([], $schemaClass, [$schemaClass::access($field) => $needle]));
				}
			} else
				echo "Operation Failed : $field is not a valid field";
			pageHit("Api.read");
			return;
		}

		// scroll event calls asking for more
		$more = false;
		if (isset($_POST['op']) && $_POST['op'] === 'get after' && isset($_POST['id'])) {
			$id = $_POST['id'];
			simpleLog("more is requested specifically after " . $_POST['id'], 'api/read/');
			$more = true;
		}

		// lower default limit for user since it involves profile image ... for fun
		$limit = (strtolower($schemaClass) == 'user') ? 10 : 30;


		// override default limit
		if (isset($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] < 500 && $_POST['limit'] > 0)
			$limit = $_POST['limit'];

		$fetch_fk_values = function (Table $answer) use ($Model, $schemaClass, &$fetch_fk_values) {
			try {
				foreach ($answer as $prop => $val) {
					if (endsWith($prop, '_id')) {
						$subSchemaClass = substr($prop, 0, -3); // remove _id from the end

						if ($val === null) {
							return null;
						}

						// you have fk and so answer should be one since id is unique
						$answer->{$subSchemaClass} =
							$Model->select([], $subSchemaClass,  [$subSchemaClass::id => $val])[0];
						$answer->{$subSchemaClass} = $fetch_fk_values($answer->{$subSchemaClass});
					}
				}
				return $answer;
			} catch (AccessDeniedException $th) {
				simpleLog("Access Denied Exception $th", 'api/read/');
				simpleLog("You have access to $schemaClass but: " . $th->getMessage(), 'api/read/');
				return $answer;
			}
		};

		try {
			$answers = ($more)
				? $answers = $Model->select([], $schemaClass, (($id !== null) ? [$schemaClass::id => $id, 'op' => '>'] : []), $limit)
				: $answers = $Model->select([], $schemaClass, [], $limit);

			// filter non null elements in the array
			$answers = array_values(array_filter(array_map($fetch_fk_values, $answers), function ($answer) {
				return $answer !== null;
			}));
			$answers = array_map(function ($row) use ($Model, $schemaClass) {
				if (property_exists($row, 'password'))
					unset($row->password);
				return $row;
			}, $answers);

			simpleLog('$_POST ' . json_encode($_POST) .
				' served', 'api/read/');
			if (count($answers) >= 1)
				echo json_encode($answers);
			else
				echo "that's all we have";
		} catch (\Throwable $e) {

			simpleLog('$_POST ' .
				json_encode($_POST) . ' failed ' . 'Caught exception: ' . $e->getMessage(), 'api/read/');
			echo 'Operation Failed: ' . ' $_POST ' .
				json_encode($_POST) . ' failed ' . 'Caught exception: ' . $e->getMessage();
		}
		pageHit("Api.read");
	}
	public function like($var = null)
	{
		# read sth like $var as in sql like()
	}
	public function update(string $schemaClass)
	{
		session_start();

		if (count($_POST) === 0)
			$_POST = json_decode(file_get_contents("php://input"), true);

		// simpleLog('Api::update raw POST ' . json_encode((object)$_POST), 'api/update');

		try {
			$wanted_columns = $schemaClass::SQL_Columns();
		} catch (\Throwable $e) {
			throw new Exception("invalid url " . URL . "api/update/$schemaClass");
		}

		try {
			if (!isset($_POST['id'])) {
				$_POST['id'] = $_SESSION['user']->id;
			}
			if (isset($_POST['profile_picture']) && str_starts_with($_POST['profile_picture'], 'data:image') && strlen($_POST['profile_picture']) > 100) {
				$data = $_POST['profile_picture'];
				$username = isset($_POST['username']) ? $_POST['username'] : $_SESSION['user']->username;
				if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {


					$data = substr($data, strpos($data, ',') + 1);
					$type = strtolower($type[1]); // jpg, png, gif
					$target = "./DB/ProfilePics/$username.$type";


					if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
						throw new \Exception('invalid image type');
					}

					simpleLog("Saving image to: $target", 'api');

					$whandle = fopen($target, 'w');
					stream_filter_append($whandle, 'convert.base64-decode', STREAM_FILTER_WRITE);
					fwrite($whandle, $data);
					fclose($whandle);
				} else {
					simpleLog(
						"Picture decode failed",
						'api'
					);
					throw new \Exception('did not match data URI with image data');
				}

				$_POST['profile_picture'] = $username . "." . $type;
			}


			simpleLog('Api::update image preprocessed POST ' . json_encode((object)$_POST), 'api/update');

			$_POST = array_filter($_POST, function ($key) use ($wanted_columns) {
				return in_array(strtolower($key), $wanted_columns);
			}, ARRAY_FILTER_USE_KEY);

			// Clean inputs
			$_POST = array_map('htmlentities', $_POST);
			$_POST = array_map('trim', $_POST);

			// Consider empty strings as null
			$_POST = array_map(function ($v) {
				return (is_string($v) && strlen($v) == 0) ? NULL : $v;
			}, $_POST);

			// Passwords get special treatment and get hashed
			if (isset($_POST['password']))
				$_POST['password'] = md5($_POST['password']);

			simpleLog(
				'Api::update POST processed' . json_encode((object)$_POST),
				'api/update'
			);

			if (count($_POST) === 2 && isset($_POST['id']) && isset($_POST['role_id'])) {
				if (!sessionUserHasPermissions(['reassign_role'])) {
					simpleLog("You don't have permission to change roles");
					throw new AccessDeniedException("You don't have permission to change roles");
				}
			} else {
				unset($_POST['role_id']);
			}

			$Model = $this->loadModel('BaseModel');

			if ($Model->update($schemaClass, $_POST['id'], (object) $_POST)) {
				$users_model = $this->loadModel('UserModel');
				$_SESSION['user'] = $users_model->getFullDetails($_SESSION['user']->id);
				echo 'updated';
			} else {
				echo 'Operation Failed : update unsuccessful';
			}
		} catch (\Throwable $e) {
			simpleLog('Caught exception: ' . $e->getMessage(), 'api/update');
			echo 'Operation Failed : ' . $e->getMessage();
		}
		pageHit("Api.update");
	}
	public function delete(?string $schemaClass = null)
	{
		session_start();

		if (count($_POST) === 0)
			$_POST = json_decode(file_get_contents("php://input"), true);

		try {
		if (isset($_POST['ids'])) {
			if (isset($_SESSION['user']) && $schemaClass === 'user' && in_array($_SESSION['user']->id, $_POST['ids'])) {
				echo 'Operation Failed : You Can\'t delete yourself from here!';
			} else {
				try {
				$Model
					= $this->loadModel('BaseModel');
				if ($Model->wipeByIds($schemaClass, $_POST['ids']))	
				echo 'deleted';
				else
					echo 'Operation Failed: unsuccessful';
					} catch (AccessDeniedException $e) {
						simpleLog('Caught exception: ' . $e->getMessage(), 'api/delete');
						echo 'Operation Failed : ' . $e->getMessage();
					} catch (\Throwable $e) {
				simpleLog('Caught exception: ' . $e->getMessage(), 'api/delete');
					echo 'Operation Failed : ' . $e->getMessage();
				}
			}
		} else {
			echo 'Operation Failed : No IDs provided';
		}
		} catch (AccessDeniedException $e) {
			simpleLog('Caught exception: ' . $e->getMessage(), 'api/delete');
			echo 'Operation Failed : ' . $e->getMessage();
		} catch (\Throwable $e) {
			simpleLog('Caught exception: ' . $e->getMessage(), 'api/delete');
			echo 'Operation Failed : ' . $e->getMessage();
		}
		pageHit("Api.delete");
	}

	function getStats()
	{
		session_start();

		$data = hitStats();

		if (!$data) http_response_code(404);

		$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

		if ($contentType === "application/json") {
			$content = trim(file_get_contents("php://input"));

			$decoded = json_decode($content, true);

			foreach ($data as $key => $value) {
				$decoded[$key] = $value;
			}

			//$decoded['bar'] = "Hello World AGAIN!";    // Add some data to be returned.

			$reply = json_encode($decoded);
		}

		header("Content-Type: application/json; charset=UTF-8");
		echo $reply;
		// ----------------------------------------------------------------------------------------------
	}
}
