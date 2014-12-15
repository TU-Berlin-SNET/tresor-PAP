<?php

	class XacmlPolicyParser {
	
		public function XacmlPolicyParser() {
		}	
		
		public function generateAllowAllFromUsergroup($groups) {
			$xml = new DOMDocument();
			$xml->formatOutput = true;
			$xml->preserveWhiteSpace = false;			
			$xml->load("../".POLICY_PATH.POLICY_ALLOW_ALL_GROUP.".xml");
			$doc = $xml->documentElement;
			
			$list = $doc->getElementsByTagName("AttributeValue");
			$parent = $list->item(0)->parentNode;
			foreach($groups as $value) {
				$clone = $list->item(0)->cloneNode();
				$clone->nodeValue = $value;
				$parent->appendChild($clone);
			}
			$parent->removeChild($list->item(0));
			return $xml->saveXML();
		}
		
		static public function retrieveGroupsFromPolicy($clientId, $serviceId) {
			$policy = XacmlPdpHandler::getServicePolicy($clientId, $serviceId);
			$xml = new DOMDocument();
			$xml->loadXML($policy["content"]);
			$doc = $xml->documentElement;
			$list = $doc->getElementsByTagName("AttributeValue");
			$groups = array();
			for($i=0; $i < $list->length; $i++) {
				$groups[] = $list->item($i)->nodeValue;
			}
			return $groups;
		}
		
	}

?>