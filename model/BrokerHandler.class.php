<?php

	class BrokerHandler {
		public static $broker_headers = array();

		public static $url = BROKER_URL;

		public static function getBookings($clientId) {
			$ch = curl_init(BrokerHandler::$url."/clients/".$clientId."/bookings");
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, self::$broker_headers);
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}
		
		public static function parseBookings($content) {
			$xml = new DOMDocument();
			$xml->loadXML($content);
			$doc = $xml->documentElement;
			$list = $doc->getElementsByTagName("booking");
			$bookings = array();
			for($i=0; $i < $list->length; $i++) {
				$nodeList = $list->item($i)->childNodes;
				for($j=0; $j < $nodeList->length; $j++)
					if(!preg_match("/^#./", $nodeList->item($j)->nodeName))
						$bookings[$i][$nodeList->item($j)->nodeName] = $nodeList->item($j)->nodeValue;
			}
			return $bookings;
		}
		
		public static function getService($serviceUrl) {
			$ch = curl_init($serviceUrl);
			
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, self::$broker_headers);
			 
			$response = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			return array("info" => $info, "content" => $response);
		}
		
		public static function parseService($content) {
			$xml = new DOMDocument();
			$xml->loadXML($content);
			$doc = $xml->documentElement;
			$service = array();
			$service["service_uuid"] = $doc->getAttribute("service_uuid");
			$service["version_uuid"] = $doc->getAttribute("version_uuid");
			for($i=0; $i < $doc->childNodes->length; $i++) {
				if(!preg_match("/^#./", $doc->childNodes->item($i)->nodeName)) {
					if(!($doc->childNodes->item($i)->nodeName == "immediate_booking"))
						if(!($doc->childNodes->item($i)->nodeName == "status"))
							$service[$doc->childNodes->item($i)->nodeName] = $doc->childNodes->item($i)->nodeValue;
						else
							$service[$doc->childNodes->item($i)->nodeName] = $doc->childNodes->item($i)->getAttribute("identifier");
					else {
						$list = $doc->childNodes->item($i)->childNodes;
						for($j=0; $j < $list->length; $j++)
							if(!preg_match("/^#./", $list->item($j)->nodeName))
								$service[$list->item($j)->nodeName] = $list->item($j)->nodeValue;
					}
				}
			}
			return $service;
		}
	
	}

	BrokerHandler::$broker_headers[] = "Accept: application/xml";

	if (defined("BROKER_USERPWD")) {
		BrokerHandler::$broker_headers[] = "Authorization : Basic ".base64_encode(BROKER_USERPWD);
	}
?>