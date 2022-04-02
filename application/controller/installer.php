<?php


class installer extends Controller
{
	public function install(string $op)
	{
		$installer = $this->loadModel('installermodel');
		if ($op == 'schema') {
			$installer->installSchema();
		} elseif ($op == 'demo') {
			$installer->installDemo();
		} elseif ($op == 'all') {
			$installer->installSchema();
			$installer->installDemo();
		} else {
			// return 400 error
			header('HTTP/1.1 400 Bad Request');
			return;
		}
		// return 200 success
		header('HTTP/1.1 200 OK');
		echo 'OK';
	}
}
