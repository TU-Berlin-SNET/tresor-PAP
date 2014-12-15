<?php

	class SessionManagement {
		private $tresorHeader;
	
		public function SessionManagement() {
			$this->tresorHeader = $this->parseHeaderFields();
		}
		
		public function getTresorHeader() {
			return $this->tresorHeader;
		}
		
		public function getOrganization() {
			if(empty($this->tresorHeader))
				return null;
				
			return $this->tresorHeader[HEADER_ORG];
		}
		
		public function getOrganizationUUID() {
			if(empty($this->tresorHeader))
				return null;
				
			return $this->tresorHeader[HEADER_ORG_UUID];
		}		
		
		public function getId() {
			if(empty($this->tresorHeader))
				return null;		
				
			return $this->tresorHeader[HEADER_ID];
		}
		
    /**
     * This function returns the role of a user who accesses the PAP via the TRESOR-proxy.
		 * Note: In any case this function returns an array whether the user has only one role or multiple.
     * @return <Array> $roles
     */		
		public function getRole() {
			if(empty($this->tresorHeader))
				return null;		
				
			if(is_array($this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_ROLE]))
				return $this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_ROLE];
			else
				return array($this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_ROLE]);
		}
		
		public function getFullName() {
			if(isset($this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_FULL_NAME]))
				return $this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_GIVEN_NAME]." ".$this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_SURNAME];
			else
				return null;
		}
		
		public function hasRole($role) {
			if(empty($this->tresorHeader))
				return false;
				
			if(is_array($this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_ROLE]))
				if(in_array($role, $this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_ROLE]))
					return true;
			else
				if($this->tresorHeader[HEADER_ATTR][TRESOR_ATTR_ROLE] == $role)
					return true;
			return false;
		}
	
		static function validate() {
			if(!isset($_SESSION["enterprise_id"])) {
				session_destroy();
				print "<meta http-equiv='refresh' content='0; URL=".BASE_URL."/index.html'>";
				die;
			}
		}
		
		private function parseHeaderFields() {
			$headers = getallheaders();
			$arr = array();
			if(isset($headers[HEADER_ID]))
				$arr[HEADER_ID] = $headers[HEADER_ID];
			if(isset($headers[HEADER_ORG]))
				$arr[HEADER_ORG] = $headers[HEADER_ORG];
			if(isset($headers[HEADER_ORG_UUID]))	
				$arr[HEADER_ORG_UUID] = $headers[HEADER_ORG_UUID];
			if(isset($headers[HEADER_ATTR])) {
				$array = explode(",", $headers[HEADER_ATTR]);
				$attributes = array();
				foreach($array as &$value) {
					if(substr($value, 0, 1) == " ")
						$value = substr($value, 1, strlen($value));
					$tmp = explode(" ", $value);
					if(array_key_exists($tmp[0], $attributes)) {
						if(is_array($attributes[$tmp[0]]))
							array_push($attributes[$tmp[0]], $tmp[1]);
						else
							$attributes[$tmp[0]] = array($attributes[$tmp[0]], $tmp[1]);
					}
					else
						$attributes[$tmp[0]] = $tmp[1];
				}
				$arr[HEADER_ATTR] = $attributes;
			}
			return $arr;
		}
		
	}

?>