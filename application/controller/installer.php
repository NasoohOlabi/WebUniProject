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
		}
	}
}
