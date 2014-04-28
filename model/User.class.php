<?php

	class User {
		private $enterprise_id;
		private $enterprise;
		private $password;

		public function User() {
		}

		public function init($enterprise, $password) {
			$this->setEnterprise_id($this->getNextEnterprise_id());
			$this->setEnterprise($enterprise);
			$this->setPassword($password);
			$this->save();
		}

		public function getEnterprise_id() {
			return $this->enterprise_id;
		}

		public function setEnterprise_id($enterprise_id) {
			$this->enterprise_id = $enterprise_id;
		}

		public function getEnterprise() {
			return $this->enterprise;
		}

		public function setEnterprise($enterprise) {
			$this->enterprise = $enterprise;
		}

		public function getPassword() {
			return $this->password;
		}

		public function setPassword($password) {
			$this->password = $password;
		}

		private function getNextEnterprise_id() {
			$c = new connection();
			$query = "SHOW TABLE STATUS LIKE 'user'";
			$result = $c->query($query);
			$tableStatus = $c->fetch_row($result);
			return $tableStatus["Auto_increment"];
		}

		static function load($enterprise_id) {
			$c = new connection();
			$query = 
			"
				SELECT *
				FROM `user`
				WHERE `enterprise_id` = '$enterprise_id'
			";
			$result = $c->query($query);
			return $c->fetch_object($result, "User");
		}		
		
		static function loadByName($enterprise) {
			$c = new connection();
			$query = 
			"
				SELECT *
				FROM `user`
				WHERE `enterprise` = '$enterprise'
			";
			$result = $c->query($query);
			return $c->fetch_object($result, "User");
		}

		public function save() {
			$c = new connection();
			$query = 
			"
				INSERT INTO `user`
				(
					`enterprise_id`, 
					`enterprise`, 
					`password`
				)
				VALUES
				(
					'$this->enterprise_id', 
					'$this->enterprise', 
					'$this->password'
				)
				ON DUPLICATE KEY UPDATE
					`enterprise_id` = '$this->enterprise_id', 
					`enterprise` = '$this->enterprise', 
					`password` = '$this->password'
			";
			$c->query($query);
		}

		static function delete($enterprise_id) {
			$c = new connection();
			$query = 
			"
				DELETE FROM `user`
				WHERE `enterprise_id` = '$enterprise_id'
			";
			$c->query($query);
		}

		static function getUserList($from, $range) {
			$c = new connection();
			$query = 
			"
				SELECT *
				FROM `user`
				ORDER BY enterprise_id DESC
				LIMIT $from, $range
			";
			$result = $c->query($query);
			return $c->fetch_objects($result, "User");
		}

	}

?>