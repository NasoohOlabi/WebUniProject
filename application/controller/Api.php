<?php
require_once 'application/views/_templates/header.php';
require_once './application/libs/util/log.php';
require_once './application/models/core/schema.php';

function is_display_key($key)
{
	return !endsWith($key, 'id') && $key !== 'identifying_fields' && $key !== 'profile_picture' &&
		$key !== "dependents";
}
if (!function_exists('is_ROOT__ADMIN')) {
	function is_ROOT__ADMIN()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		// if (!(isset($_SESSION['user']) && $_SESSION['user']->role->name == 'ROOT::ADMIN')) {
		//     header('Location:' . URL);
		//     simpleLog("he is trying to hack us " . json_encode($_SESSION['user']), 'users/');
		//     return;
		// }
		// simpleLog("access Granted to " . json_encode($_SESSION['user']), 'users/');
	}
}
class Api extends Controller
{
	public function index()
	{
		// admin session check!
		echo '<pre>';
		echo "APIs are great, make sure to use to be the admin to use this api for now";
		echo '</pre>';
		pageHit("Api.index");
	}
	public function create($className = null)
	{
		Is_ROOT__ADMIN();
		$_POST = json_decode(file_get_contents("php://input"), true);
		try {
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

			// The constructor checks if the required field are satisfied
			// and also obviously checks is $className is sth we have
			$v = new $className((object) $_POST);


			simpleLog(json_encode($v), 'Api/create/');
			$Model = $this->loadModel('BaseModel');
			if ($Model->experimental_insert($v)) {
				echo json_encode($v);
			} else {
				echo "Operation Failed : create $className failed ";
			}
			// header('Location:' . URL . 'DashBoard/');
		} catch (\Throwable $e) {

			simpleLog('Caught exception: ' . $e->getMessage());
			echo 'Operation Failed : ' . 'Caught exception: ' . str_replace('::', ' ', str_replace('$', ' ', $e->getMessage()));
		}
		pageHit("Api.create");
	}
	public function read(string $schemaClass = null, string $id = null)
	{
		Is_ROOT__ADMIN();
		$_POST = json_decode(file_get_contents("php://input"), true);
		if ($schemaClass == null) {
			// invalid request
			echo 'Operation Failed : ';

			simpleLog('$_POST ' . json_encode($_POST) . ' failed', 'Api/read/');
			return;
		}

		if ($id !== null)
			$id = intval($id);

		$more = false;
		if (isset($_POST['op']) && $_POST['op'] === 'get after' && isset($_POST['id'])) {
			$id = $_POST['id'];
			simpleLog("more is requested specifically after " . $_POST['id']);
			$more = true;
		}

		$limit = (strtolower($schemaClass) == 'user') ? 8 : 30;


		if (isset($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] < 100 && $_POST['limit'] > 0)
			$limit = $_POST['limit'];

		$Model = $this->loadModel('BaseModel');
		$fetch_fk_values = function (Table $answer) use ($Model, $schemaClass, &$fetch_fk_values) {
			try {
				foreach ($answer as $prop => $val) {
					if (endsWith($prop, '_id')) {
						$subSchemaClass = substr($prop, 0, -3); // remove _id from the end

						// you have fk and so answer should be one since id is unique
						$answer->{$subSchemaClass} =
							$Model->select([], $subSchemaClass,  [$subSchemaClass::id => $val])[0];
						$answer->{$subSchemaClass} = $fetch_fk_values($answer->{$subSchemaClass});
					}
				}
				return $answer;
			} catch (AccessDeniedException $th) {
				simpleLog("Access Denied Exception $th");
				simpleLog("You have access to $schemaClass but: " . $th->getMessage());
				return $answer;
			}
		};
		try {
			$answers = ($more)
				? $answers = $Model->select([], $schemaClass, (($id !== null) ? [$schemaClass::id => $id, 'op' => '>'] : []), $limit)
				: $answers = $Model->select([], $schemaClass, (($id !== null) ? [$schemaClass::id => $id] : []), $limit);

			$answers = array_map($fetch_fk_values, $answers);
			$answers = array_map(function ($row) use ($Model, $schemaClass) {
				if (property_exists($row, 'password'))
					unset($row->password);

				if (count($row->dependents) === 0) return $row;

				foreach ($row->dependents as $dep) {
					if (is_array($dep) && count($dep) == 1) {
						// One2Many getting the many...
						foreach ($dep as $key => $value) {
							try {
								$row->{strtolower($key) . 's'} = $Model->select([], $key,  [$key::access($value) => $row->id]);
							} catch (AccessDeniedException $th) {
								simpleLog("Access Denied Exception $th");
								simpleLog("You have access to $schemaClass but: " . $th->getMessage());
								// return $row;
								continue;
							}
						}
					} else {
						if (str_contains(strtolower($dep), '_has_')) {
							// Many2Many but we can treat is as One2Many in this case

							// those are SchemaClass names
							// not sql names
							$one_name = get_class($row);
							$many_name = ($dep === 'Exam_Center_Has_Exam' && $one_name == 'Exam')
								? 'Exam_Center'
								: str_replace('_Has_', '', str_replace($one_name, '', $dep));

							$middle_table = $dep;
							$middle_table_one_col =
								$dep::access(strtolower($one_name) . '_id');
							$middle_table_many_col =
								$dep::access(strtolower($many_name) . '_id');

							simpleLog('->>jump over many2many table :: $dep<<<----' . "dep: $dep one_name: $one_name many_name: $many_name middle_table: $middle_table middle_table_one_col: $middle_table_one_col middle_table_many_col: $middle_table_many_col row->id: " . json_encode($row->id));

							try {
								$row->{strtolower($many_name) . 's'} =
									$Model->join(
										[
											$many_name,
											$middle_table
										],
										// safe conditions
										[
											[$many_name::id =>
											$middle_table_many_col],
											[$middle_table_one_col => $row->id]
										]
									);
							} catch (AccessDeniedException $th) {
								simpleLog("Access Denied Exception $th");
								simpleLog("You have access to $schemaClass but: " . $th->getMessage());
								continue;
								// return $row;
							}
						} else {
							$fk_col = $dep::access(strtolower(get_class($row)) . '_id');
							simpleLog(get_class($row) . ' dep fetch ' . $dep);
							try {
								$row->{strtolower($dep) . 's'} = $Model->select([], $dep,  [$fk_col => $row->id]);
							} catch (AccessDeniedException $th) {
								simpleLog("Access Denied Exception $th");
								simpleLog("You have access to $schemaClass but: " . $th->getMessage());
								continue;
								// return $row;
							}
							simpleLog(get_class($row) . " dep " . json_encode($row->{strtolower($dep) . 's'}));
						}
					}
				}
				return $row;
			}, $answers);

			simpleLog('$_POST ' . json_encode($_POST) . ' served', 'Api/read/');
			if (count($answers) >= 1)
				echo json_encode($answers);
			elseif ($more)
				echo "that's all we have";
			else
				echo "id $id Not Found";
		} catch (\Throwable $e) {

			simpleLog('$_POST ' .
				json_encode($_POST) . ' failed ' . 'Caught exception: ' . $e->getMessage());
			echo 'Operation Failed : ' . ' $_POST ' .
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
		Is_ROOT__ADMIN();
		$_POST = json_decode(file_get_contents("php://input"), true);
		try {
			// Clean inputs
			try {
				$wanted_columns = $schemaClass::SQL_Columns();
			} catch (\Throwable $e) {
				throw new Exception("invalid url $schemaClass");
			}

			simpleLog('api update>>>preprocessed>>>>POST>>>>>' . json_encode((object)$_POST));
			$_POST = array_filter($_POST, function ($key) use ($wanted_columns) {
				return in_array(strtolower($key), $wanted_columns);
			}, ARRAY_FILTER_USE_KEY);
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
				'api update>>>>cleaned>>>POST>>>>>' . json_encode((object)$_POST)
			);

			if (!isset($_POST['id'])) {
				$_POST['id'] = $_SESSION['user']->id;
			}

			simpleLog(
				'api update>>>>cleaned>>>POST>>>>>id added>>>>>' . json_encode((object)$_POST)
			);

			$Model = $this->loadModel('BaseModel');
			if ($Model->experimental_update($schemaClass, $_POST['id'], (object) $_POST)) {
				$users_model = $this->loadModel('UserModel');
				$_SESSION['user'] = $users_model->getFullDetails($_SESSION['user']->id);
				echo 'updated';
			} else {
				echo 'update unsuccessful';
			}
		} catch (\Throwable $e) {

			simpleLog('Caught exception: ' . $e->getMessage());
			echo 'Operation Failed : ';
		}
		pageHit("Api.update");
	}
	public function delete(string $schemaClass, int $id)
	{
		Is_ROOT__ADMIN();
		$_POST = json_decode(file_get_contents("php://input"), true);
		try {
			if (isset($_POST['ids'])) {
				$Model
					= $this->loadModel('BaseModel');
				$Model->wipeByIds($schemaClass, $_POST['ids']);
				echo 'deleted';
			} else {
				if ($id) {
					$Model
						= $this->loadModel('BaseModel');
					$Model->deleteById($id);
					echo 'deleted';
				} else {
					// Clean inputs
					$_POST = array_map('htmlentities', $_POST);
					$_POST = array_map('trim', $_POST);
					// Consider empty strings as null
					$_POST = array_map(function ($v) {
						return (is_string($v) && strlen($v) == 0) ? NULL : $v;
					}, $_POST);
					// Get the name of what are we updating
					$className = $_POST['schemaClass'];
					// and also obviously checks is $className is sth we have
					$v = new $className((object) $_POST);

					simpleLog(json_encode($v), 'Api/delete/');
					$Model = $this->loadModel('BaseModel');
					//TODO: check if what he submitted has the same fields
					$Model->deleteById($v->id);
					header('Location:' . URL . 'DashBoard/');
				}
			}
		} catch (\Throwable $e) {

			simpleLog('Caught exception: ' . $e->getMessage());
			echo 'Operation Failed : ';
		}
		pageHit("Api.delete");
	}

	function getStats()
	{
		Is_ROOT__ADMIN();

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
