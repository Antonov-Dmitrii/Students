<?php
	class DB {
		private $db;
		private static $user = null;
		
		private function __construct() {
			$this->db = new mysqli("localhost", "vova1407_stud", "PEYg7P0B", "vova1407_stud");
			$this->db->query("SET NAMES 'utf8'");
		}
		
		public static function getObject() {
			if(self::$user === null) self::$user = new DB();
			return self::$user;
		}
		
		public function checkUser($login, $password) {
			$result_set = $this->db->query("SELECT `password` FROM `users` WHERE `login`='$login'");
			$user = $result_set->fetch_assoc();
			if(!$user) return false;
			return $user["password"] === $password;
		}
		
		public function isAuth() {
			session_start();
			$login = $_SESSION["login"];
			$password = $_SESSION["password"];
			return $this->checkUser($login, $password);
		}
		
		public function login($login, $password) {
			$password = md5($password);
			if($this->checkUser($login, $password)) {
				session_start();
				$_SESSION["login"] = $login;
				setcookie("login", $login);
				$_SESSION["password"] = $password;
				return true;
			}
		}
		
		public function getRole($login) {
			$result_set = $this->db->query("SELECT `rights` FROM `users` WHERE `login`='$login'");
			$role = $result_set->fetch_assoc();
			return $role["rights"];
		}

		public function checkLogin($login) {
			$result_set = $this->db->query("SELECT `password` FROM `users` WHERE `login`='$login'");
			return $result_set;
		}
		
		public function getUserByLogin($login){
			$result_set = $this->db->query("SELECT * FROM `users` WHERE `login`='$login'");
			return $result_set;
		}

		public function getUserByID($id){
			$result_set = $this->db->query("SELECT * FROM `users` WHERE `id`='$id'");
			return $result_set;
		}

		public function add($table, $name) {
			$this->db->query("INSERT INTO `$table` (`name`) VALUES ('$name')");
		}

		public function addGroup($name, $idBranch) {
			$this->db->query("INSERT INTO `groups` (`name`, `idBranch`) VALUES ('$name', '$idBranch')");
		}

		public function addStudent($idGroup, $name, $date, $docs, $note) {
			$this->db->query("INSERT INTO `students` (`idGroup`, `name`, `date`, `docs`, `note`) 
					 VALUES ('$idGroup', '$name', '".strtotime("$date")."', '$docs', '$note')");
		}

		public function addDateMark($idGroup, $idSubject, $date, $type) {
			$this->db->query("INSERT INTO `dates_in_journal` (`idGroup`, `idSubject`, `date`, `type`) 
					 VALUES ('$idGroup', '$idSubject', '".strtotime("$date")."', '$type')");
		}

		public function addMark($idStudent, $idSubject, $date, $mark) {
			$this->db->query("INSERT INTO `marks` (`idStudent`, `idSubject`, `date`, `mark`) 
					 VALUES ('$idStudent', '$idSubject', '$date', '$mark')");
		}

		public function addSemester($startDate, $endDate, $isActive) {
			$this->db->query("INSERT INTO `start_and_end_semesters_dates` (`startDate`, `endDate`, `isActive`) 
					 VALUES ('".strtotime("$startDate")."', '".strtotime("$endDate")."', '$isActive')");
		}
				
		public function edit($table, $id, $name) {
			$this->db->query("UPDATE `$table` SET `name`='$name' WHERE `id` = '$id'");
		}

		public function editStudent($id, $name, $date, $docs, $note) {
			$this->db->query("UPDATE `students` SET `name`='$name', `note`='$note', `date`='".strtotime("$date")."', `docs`='$docs' WHERE `id` = '$id'");
		}

		public function editSemester($id, $startDate, $endDate, $isActive) {
			$this->db->query("UPDATE `start_and_end_semesters_dates` SET `startDate`='".strtotime("$startDate")."', 
								`endDate`='".strtotime("$endDate")."', `isActive`='$isActive' WHERE `id` = '$id'");
		}

		public function editMark($id, $mark) {
			$this->db->query("UPDATE `marks` SET `mark`='$mark' WHERE `id` = '$id'");
		}

		public function getAllNotDeleted($table) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE isDeleted != 1");
			return $result_set;
		}

		public function getAllNotDeletedOrder($table, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE isDeleted != 1 ORDER by $order");
			return $result_set;
		}
		
		public function getRowWhere($table, $columnName, $where) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName`='$where'");
			return $result_set;
		}

		public function getRowWhereOrder($table, $columnName, $where, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName`='$where' ORDER by $order");
			return $result_set;
		}

		public function getRowWhereOrderNotDeleted($table, $columnName, $where, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName`='$where' AND isDeleted != 1 ORDER by $order");
			return $result_set;
		}

		public function getDatesForJournal($startDate, $endDate, $idGroup, $idSubject) {
			$result_set = $this->db->query("SELECT * FROM `dates_in_journal` WHERE (`idGroup`=$idGroup AND `idSubject`=$idSubject AND `date` >= $startDate AND `date` <= $endDate) ORDER by date");
			return $result_set;
		}

		public function getMark($idStudent, $idSubject, $date) {
			$result_set = $this->db->query("SELECT * FROM `marks` WHERE (`idStudent`=$idStudent AND `idSubject`=$idSubject AND `date` = $date)");
			return $result_set;
		}

		public function resetIsActive($id) {
			$this->db->query("UPDATE `start_and_end_semesters_dates` SET `isActive`='0' WHERE `id` = '$id'");
		}

		public function doIsDeleted($table, $id) {
			$this->db->query("UPDATE `$table` SET `isDeleted`='1' WHERE `id` = '$id'");
		}
		
		public function delete($table, $id) {
			$this->db->query("DELETE FROM `$table` WHERE `id` = '$id'");
		}

		
		public function __destruct() {
			if($this->db) $this->db->close();
		}
	}
?>