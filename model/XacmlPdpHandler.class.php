<?php

	class XacmlPdpHandler {
		public static $url = PDP_URL;
	
    /**
     * Perform XACML request and receive the response.
     * @param <XML> $request - XACML conform request
     * @return <String> $response - the raw response of the PDP
     */
		public static function xacmlRequest($request) {
			$ch = curl_init(XacmlPdpHandler::$url."/pdp");
			 
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Accept: application/xacml+xml",
				"Content-Type: application/xacml+xml"
			));				
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}	
		
    /**
     * Retrieve a list of all policies which have been uploaded to the PDP.
     * @return <String> response from the PDP listing all policies
     */
		public static function getClientsPolicies($clientId) {
			$ch = curl_init(XacmlPdpHandler::$url."/policy/".$clientId);
			 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Accept: application/json",
				"Authorization : Basic YnJva2VyOmJyb2tlcg=="
			));			
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}
		
    /**
     * Get the policy of a specific service.
     * @param <String> $serviceId - The TRESOR-wide known identifier of the service.
     * @return <String> $response - The policy of the respective service.
		 * OR
     * @return <String> $response - A HTTP status (200 | 401 | 403 | 404).
     */
		public static function getServicePolicy($clientId, $serviceId) {
			$ch = curl_init(XacmlPdpHandler::$url."/policy/".$clientId."/".$serviceId);
			 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Accecpt: application/xacml+xml",
				"Authorization: Basic YnJva2VyOmJyb2tlcg=="	
			));						
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}			
		
		public static function getServicePolicyId($clientId, $serviceId) {
			$response = XacmlPdpHandler::getServicePolicy($clientId, $serviceId);
			if($response["info"]["http_code"] == 200) {
				$xml = simplexml_load_string($response["content"]);
				$policyId = $xml->attributes()->PolicyId;
				if(preg_match("/^Broker_*/", $policyId)) {
					$policyId = explode("_", $policyId);
					$policyId = $policyId[1];
					if($policyId == POLICY_ALLOW_ALL) $type = 0;
					if($policyId == POLICY_ALLOW_ALL_GROUP) $type = 1;
					if($policyId == POLICY_DENY_ALL) $type = 2;
				}
				else $type = 3;
				return array("policyId" => $policyId, "type" => $type);
			}
			// TODO: Handling if PDP is not reachable
			return array("policyId" => null, "type" => 0);
		}
		
		public static function isDefaultPolicy($clientId, $serviceId) {
			$response = XacmlPdpHandler::getServicePolicy($clientId, $serviceId);
			if($response["info"]["http_code"] == 200) {
				$xml = simplexml_load_string($response["content"]);
				$policyId = $xml->attributes()->PolicyId;
				if(preg_match("/^Broker_*/", $policyId))
					return true;
			}
			return false;
		}
		
    /**
     * Upload a policy to the PDP for a given service.
		 * IMPORTANT: 
		 * Use this function if a service already exists. 
		 * This functions replaces an existing policy for the given service.
     * @param <String> $serviceId - The TRESOR-wide known identifier of the service. 
     * @param <String> $policy - The policy of the respective service.
     * @return <String> $response - A HTTP status (204 | 401 | 403 | 404 | 400 | 500).
     */
		public static function putServicePolicy($clientId, $serviceId, $policy) {
			$ch = curl_init(XacmlPdpHandler::$url."/policy/".$clientId."/".$serviceId);
			 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $policy);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type : application/xacml+xml",
				"Authorization : Basic YnJva2VyOmJyb2tlcg=="
			));				
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}		

    /**
     * Delete a policy from the PDP for a given service.
     * @param <String> $serviceId - The TRESOR-wide known identifier of the service.
     * @return <String> $response - A HTTP status (204 | 401 | 403 | 404 | 500).
     */
		public static function deleteServicePolicy($clientId, $serviceId) {
			$ch = curl_init(XacmlPdpHandler::$url."/policy/".$clientId."/".$serviceId);
			 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Authorization : Basic YnJva2VyOmJyb2tlcg=="
			));				
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}
		
	}

?>