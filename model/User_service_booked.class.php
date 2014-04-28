<?php

	class User_service_booked {
		private $enterprise_id;
		private $service_id;

		public function User_service_booked() {
		}

		public function init($enterprise_id, $service_id) {
			$this->setEnterprise_id($enterprise_id);
			$this->setService_id($service_id);
			$this->save();
		}

		public function getEnterprise_id() {
			return $this->enterprise_id;
		}

		public function setEnterprise_id($enterprise_id) {
			$this->enterprise_id = $enterprise_id;
		}

		public function getService_id() {
			return $this->service_id;
		}

		public function setService_id($service_id) {
			$this->service_id = $service_id;
		}

		static function load($enterprise_id, $service_id) {
			$c = new connection();
			$query = 
			"
				SELECT *
				FROM `user_service_booked`
				WHERE `enterprise_id` = '$enterprise_id' AND `service_id` = '$service_id'
			";
			$result = $c->query($query);
			return $c->fetch_object($result, "User_service_booked");
		}

		public function save() {
			$c = new connection();
			$query = 
			"
				INSERT INTO `user_service_booked`
				(
					`enterprise_id`, 
					`service_id`
				)
				VALUES
				(
					'$this->enterprise_id', 
					'$this->service_id'
				)
				ON DUPLICATE KEY UPDATE
					`enterprise_id` = '$this->enterprise_id', 
					`service_id` = '$this->service_id'
			";
			$c->query($query);
		}

		static function delete($enterprise_id, $servie_id) {
			$c = new connection();
			$query = 
			"
				DELETE FROM `user_service_booked`
				WHERE `enterprise_id` = '$enterprise_id' AND `service_id` = '$service_id'
			";
			$c->query($query);
		}

	}

?>