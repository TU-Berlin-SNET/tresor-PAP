<?php

	class Ldap_config {
		private $enterprise_id;
		private $ldap_host;
		private $ldap_port;
		private $ldap_rdn;
		private $ldap_password;
		private $ldap_search_string;

		public function Ldap_config() {
		}

		public function init($enterprise_id, $ldap_host, $ldap_port, $ldap_rdn, $ldap_password, $ldap_search_string)
		{
			$this->setEnterprise_id($enterprise_id);
			$this->setLdap_host($ldap_host);
			$this->setLdap_port($ldap_port);
			$this->setLdap_rdn($ldap_rdn);
			$this->setLdap_password($ldap_password);
			$this->setLdap_search_string($ldap_search_string);
			$this->save();
		}

		public function getEnterprise_id() {
			return $this->enterprise_id;
		}

		public function setEnterprise_id($enterprise_id) {
			$this->enterprise_id = $enterprise_id;
		}

		public function getLdap_host() {
			return $this->ldap_host;
		}

		public function setLdap_host($ldap_host) {
			$this->ldap_host = $ldap_host;
		}

		public function getLdap_port() {
			return $this->ldap_port;
		}

		public function setLdap_port($ldap_port) {
			$this->ldap_port = $ldap_port;
		}

		public function getLdap_rdn() {
			return $this->ldap_rdn;
		}

		public function setLdap_rdn($ldap_rdn) {
			$this->ldap_rdn = $ldap_rdn;
		}

		public function getLdap_password() {
			return $this->ldap_password;
		}

		public function setLdap_password($ldap_password) {
			$this->ldap_password = $ldap_password;
		}

		public function getLdap_search_string(){
			return $this->ldap_search_string;
		}

		public function setLdap_search_string($ldap_search_string) {
			$this->ldap_search_string = $ldap_search_string;
		}

		static function load($enterprise_id) {
			$c = new connection();
			$query = 
			"
				SELECT *
				FROM `ldap_config`
				WHERE `enterprise_id` = '$enterprise_id'
			";
			$result = $c->query($query);
			return $c->fetch_object($result, "Ldap_config");
		}

		public function save() {
			$c = new connection();
			$query = 
			"
				INSERT INTO `ldap_config`
				(
					`enterprise_id`, 
					`ldap_host`, 
					`ldap_port`, 
					`ldap_rdn`, 
					`ldap_password`, 
					`ldap_search_string`
				)
				VALUES
				(
					'$this->enterprise_id', 
					'$this->ldap_host', 
					'$this->ldap_port', 
					'$this->ldap_rdn', 
					'$this->ldap_password', 
					'$this->ldap_search_string'
				)
				ON DUPLICATE KEY UPDATE
					`enterprise_id` = '$this->enterprise_id', 
					`ldap_host` = '$this->ldap_host', 
					`ldap_port` = '$this->ldap_port', 
					`ldap_rdn` = '$this->ldap_rdn', 
					`ldap_password` = '$this->ldap_password', 
					`ldap_search_string` = '$this->ldap_search_string'
			";
			$c->query($query);
		}

		static function delete($enterprise_id) {
			$c = new connection();
			$query = 
			"
				DELETE FROM `ldap_config`
				WHERE `enterprise_id` = '$enterprise_id'
			";
			$c->query($query);
		}

	}

?>