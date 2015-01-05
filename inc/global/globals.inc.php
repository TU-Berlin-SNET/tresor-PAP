<?php

  /**
   * Global paths
   */
	define("BASE_URL", "http://pap.service.cloud-tresor.de/");
	define("INCLUDE_PATH", "inc/lib/");
	define("SERVICES_PATH", "xml-templates/");
	define("STYLESHEET_PATH", "inc/style/");
	define("TEMPLATE_PATH", "view/");
	define("CONTROL_PATH", "control/");
	define("MODEL_PATH", "model/");
	define("POLICY_PATH", "policies/");
	define("DATA_PATH", "data/");
	define("LOCALE_PATH", "locale/");
	
  /**
   * Global strings
   */
	define("THESIS_TITLE", "Policy Administration Point");
	
	define("HINT_SUCCESS", "success");
	define("HINT_INFO", "info");
	define("HINT_WARNING", "warning");
	define("HINT_ERROR", "error");
	
	define("IDENTITY_SOURCE_NONE", "Keine");
	define("IDENTITY_SOURCE_IDM", "TRESOR IDM");
	define("IDENTITY_SOURCE_SKIDENTITY", "SkIdentity");
	define("IDENTITY_SOURCE_HPC", "Health Professional Card");
	define("IDENTITY_SOURCE_NPA", "Neuer Personalausweis");
	define("IDENTITY_SOURCE_EGK", "Electronic Health Card");
	
	define("HPC_ATTRIBUTE", "http://schemas.cloud-tresor.com/schema/2014/11/hpc-id");
	define("NPA_ATTRIBUTE", "http://schemas.cloud-tresor.com/request/2014/11/npa-id");
	define("EGK_ATTRIBUTE", "http://schemas.cloud-tresor.com/schema/2014/11/egk-id");
	
	define("HEADER_ID", "TRESOR-Identity");
	define("HEADER_ORG", "TRESOR-Organization");
	define("HEADER_ORG_UUID", "TRESOR-Organization-UUID");
	define("HEADER_ATTR", "TRESOR-Attribute");
	
	define("TRESOR_ATTR_WINDOWS_ACCOUNT_NAME", "http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname");
	define("TRESOR_ATTR_FULL_NAME", "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name");
	define("TRESOR_ATTR_GIVEN_NAME", "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname");
	define("TRESOR_ATTR_SURNAME", "http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname");
	define("TRESOR_ATTR_ROLE", "http://schemas.microsoft.com/ws/2008/06/identity/claims/role");
	define("TRESOR_ATTR_ORG", "http://schemas.tresor.com/claims/2014/04/organization");
	
	define("ROLE_TRESOR_ADMIN", "TRESOR_System_Admin");
	define("ROLE_TRESOR_MP_STORE_PROVIDER", "TRESOR_MP_Marktplatzbetreiber");
	define("ROLE_TRESOR_SERVICE_USER", "TRESOR_MP_Dienstnutzer");
	define("ROLE_TRESOR_SERVICE_PROVIDER", "TRESOR_MP_Dienstanbieter");
	define("ROLE_STORE_PROVIDER", "MP_Marktplatzbetreiber");
	define("ROLE_SERVICE_USER", "MP_Dienstnutzer");
	define("ROLE_SERVICE_PROVIDER", "MP_Dienstanbieter");
	
	define("POLICY_ALLOW_ALL", "AllowAll");
	define("POLICY_ALLOW_ALL_GROUP", "AllowAllFromUsergroup");
	define("POLICY_DENY_ALL", "DenyAll");	
	
	/**
	 * Global arrays
	 */
	$identityProvider = array(
		IDENTITY_SOURCE_NONE,
		IDENTITY_SOURCE_IDM,
		IDENTITY_SOURCE_HPC,
		IDENTITY_SOURCE_NPA,
		IDENTITY_SOURCE_EGK
	);
	
  /**
   * Logstash constants
   */	
	define("LOGSTASH_URL", "xacml.snet.tu-berlin.de:9400");
	define("LOGSTASH_LOGGER", "org.snet.tresor-pap");
	define("LOGSTASH_ERROR", "ERROR");
	define("LOGSTASH_WARN", "WARN");
	define("LOGSTASH_INFO", "INFO");
	define("LOGSTASH_DEBUG", "DEBUG");
	define("LOGSTASH_TRACE", "TRACE");
	define("LOGSTASH_TRESOR_COMPONENT", "PAP");
	
  /**
   * Global settings
   */
	ini_set('display_errors', 1);
	
	use Monolog\Logger;
	use Monolog\Formatter\LogstashFormatter;
	use Monolog\Formatter\JsonFormatter;
	use Monolog\Handler\SocketHandler;	
	
	function getLogger($loggerName) {
		$logger = new Logger($loggerName);
		$handler = new SocketHandler(LOGSTASH_URL);
		$handler->setPersistent(true);
		$logger->pushHandler($handler, Logger::DEBUG);
		$logger->pushProcessor(function ($record) {
			$record["extra"] = generateLogRecord($record["level_name"], $record["channel"]);
			return $record;
		});
		$formatter = new LogstashFormatter($logger->getName(), null, 	null, "", LogstashFormatter::V1);
		$handler->setFormatter($formatter);	
		return $logger;
	}
	
  /**
   * Create a log record for logstash
   * @param <type> $priority
   * @return <Array>
   */
	function generateLogRecord($priority, $logger) {
		if(isset($_SESSION["enterprise_id"]))
			$clientId = $_SESSION["enterprise_id"];
		else
			$clientId = "";
		if(isset($_SESSION["enterprise"]))
			$subjectId = $_SESSION["enterprise"];
		else
			$subjectId = "";
		return array(
			"client-id" => $clientId,
			"priority" => $priority,
			"logger" => $logger,
			"tresor-component" => LOGSTASH_TRESOR_COMPONENT,
			"subject-id" => $subjectId
		);	
	}
	
	function pfile_get_contents($path) {
		ob_start();
		include($path);
		return ob_get_clean();	
	}
	
?>
