<?php

	$url = "http://xacml.snet.tu-berlin.de:9090/rest/policyhandler";
	$policy = file_get_contents("policies/GeoXACMLv3_aggregated_policyset.xml");
	print put_request($url, $policy);

	function put_request($url, $policy) {
		$ch = curl_init($url);
		 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $policy);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}


?>