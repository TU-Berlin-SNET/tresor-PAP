<?php

	class SessionManagement {
	
		static function sessionCheck() {
			if(!isset($_SESSION["enterprise_id"]) ||
				(time() - $_SESSION["time"] > 30000)) {
				session_destroy();
				print "<meta http-equiv='refresh' content='0; URL=http://localhost/BA/index.html'>";
				die;
			}
			else
				$_SESSION["time"] = time();
		}
		
	}

?>