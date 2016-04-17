<?php
require_once "DB/pdoDbManager.php";
require_once "DB/DAO/UsersDAO.php";

class AuthenticationModel {
	private $UsersDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->UsersDAO = new UsersDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
	}
	
	public function authenticateUser($username, $password) {
		if(!empty ($username) && !empty ($password))
			if($this->UsersDAO->authenticate($username, $password))
				return true;
		return false;
	}
	public function __destruct() {
		$this->UsersDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>