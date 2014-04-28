<?php

	$url = "http://xacml.snet.tu-berlin.de:9090/rest/policyhandler";
	print delete_request($url, $_GET["i"]);

	function delete_request($url, $i) {
		$ch = curl_init($url);
		 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $i);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

?>