<?php
require_once 'basemodel.php';

class InstallerModel extends BaseModel
{
	function __construct($db)
	{
		parent::__construct($db, "permission");
	}

	function installSchema()
	{
		$sql = file_get_contents('.\application\_install\schema.sql');
		$queries = explode(';', $sql);
		foreach ($queries as $query) {
			$this->db->query($query);
		}
	}
	function installDemo()
	{
		$sql = file_get_contents('.\application\_install\DemoData.sql');
		$queries = explode(';', $sql);
		foreach ($queries as $query) {
			$this->db->query($query);
		}
	}
}
