<?php

	class InstallationManagement {
	
		private static function isConfigured() {
			if(empty(DB_HOST) || empty(DB_USER))
				return false;
			return true;
		}
		
		private static function isReachable() {
			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if($mysqli->connect_errno)
				return false;
			
			$tables = array_reduce(
				$mysqli->query("SHOW TABLES")->fetch_all(), 
				"InstallationManagement::reduce_rows", 
				array()
			);
			
			if(!in_array(TABLE_USER, $tables))
				return false;
			if(!in_array(TABLE_LDAP_CONFIG, $tables))
				return false;
			if(!in_array(TABLE_USER_SERVICE_BOOKED, $tables))
				return false;
			
			$users = array_reduce(
				$mysqli->query("SELECT * FROM ".TABLE_USER)->fetch_all(),
				"InstallationManagement::reduce_rows",
				array()
			);
			
			if(empty($users))
				return false;
				
			return true;
		}	
		
		static function isInstalled() {
			if(!(InstallationManagement::isConfigured()) || 
				!(InstallationManagement::isReachable())) {
				return false;
			}
			return true;
		}
		
		static function check() {
			if(!(InstallationManagement::isConfigured()) || 
				!(InstallationManagement::isReachable())) {
				print "<meta http-equiv='refresh' content='0; URL=".BASE_URL."/install.html'>";
				die;
			}
		}
		
		private static function reduce_rows($carry, $item) { 
			return array_merge($carry, $item); 
		}			
	
	}

?>