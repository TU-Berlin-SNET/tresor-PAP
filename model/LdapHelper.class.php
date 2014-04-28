<?php

	class LdapHelper {
		private $ds;
		private $r;
		private $search_string;
		
		public function LdapHelper($ldap_host, $ldap_port, $ldap_rdn, $ldap_password, $ldap_search_string) {
			$this->ds = ldap_connect($ldap_host, $ldap_port);
			$this->r = @ldap_bind($this->ds, $ldap_rdn, $ldap_password);
			$this->search_string = $ldap_search_string;
			ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($this->ds, LDAP_OPT_REFERRALS, 0);
		}
		
		private function getEntries($query) {
			$sr = ldap_search($this->ds, $this->search_string, $query);
			return ldap_get_entries($this->ds, $sr);
		}
		
		public function getPeople() {
			$entries = $this->getEntries("uid=*");
			$people = array();
			foreach($entries as $key => $value) {
				if(!is_numeric($key)) continue;
				$people[] = $value["uid"][0];
			}
			return $people;
		}
		
		public function getPeopleByUid($uid) {
			$entries = $this->getEntries("uid=".$uid."*");
			$people = array();
			foreach($entries as $key => $value) {
				if(!is_numeric($key)) continue;
				$people[] = $value["uid"][0];
			}
			return $people;
		}			
		
		public function getRoles() {
			$entries = $this->getEntries("ou=*");
			$ou = array();
			foreach($entries as $key => $value) {
				if(!is_numeric($key)) continue;
				$ou[] = $value["ou"][0];
			}
			return array_unique($ou);
		}		
	}

?>