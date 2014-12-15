<?php

	class XacmlPolicyGenerator {
	
		public function XacmlPolicyGenerator() {
		}
		
    /**
     * A function to generate a valid GeoXACMLv2 policy
     * @param <type> $file - The output file's name.
     * @param <type> $data - The user's input.
     * @param <type> $serviceId - The service's ID.
     * @param <type> $serviceDesc - The service's description text.
     * @param <type> $serviceUrl - The service's url.
     * @return <void>
     */
		static function generateV2Policy($file, $data, $serviceId, $serviceDesc, $serviceUrl, $enterpriseId) {
			$w = new XMLWriter();
			touch($file);
			$file = realpath($file);
			$w->openURI($file);
			$w->setIndent(true);
			$w->setIndentString("\t");

			$w->startDocument("1.0", "UTF-8");
				$w->startElement("Policy");
					$w->writeAttribute("xmlns", XACML_NSV2);
					$w->writeAttribute("xmlns:xsi", XACML_XSIV2);
					$w->writeAttribute("xsi:schemaLocation", XACML_XSDV2);
					$w->writeAttribute("PolicyId", $serviceId);					
					$w->writeAttribute("RuleCombiningAlgId", RULE_PERMIT_OVERRIDES);
						$w->writeElement("Description", $serviceDesc);						
						
						// Policy target
						XacmlPolicyGenerator::writePolicyV2Target($w, $serviceUrl, $enterpriseId);
						
						// Rules
						foreach($data["resource"] as $key => $value) {
							XacmlPolicyGenerator::writeRule($w, $data, $key, 'V2');
							// XacmlPolicyGenerator::generateRequests($data, $key, 'V2', $serviceId, $serviceUrl, $enterpriseId);
						}
							
						$w->startElement("Rule");
							$w->writeAttribute("RuleId", "else");
							$w->writeAttribute("Effect", "Deny");
						$w->endElement();
				$w->endElement(); // </Policy>
			$w->endDocument();
			$w->outputMemory(TRUE);
		}
		
    /**
     * A function to generate a valid GeoXACMLv3 policy
     * @param <type> $file - The output file's name.
     * @param <type> $data - The user's input.
     * @param <type> $serviceId - The service's ID.
     * @param <type> $serviceDesc - The service's description text.
     * @param <type> $serviceUrl - The service's url.
     * @return <void>
     */
		static function generateV3Policy($file, $data, $serviceId, $serviceDesc, $serviceUrl, $enterpriseId) {
			$w = new XMLWriter();
			touch($file);
			$file = realpath($file);
			$w->openURI($file);
			$w->setIndent(true);
			$w->setIndentString("\t");

			$w->startDocument("1.0", "UTF-8");
				$w->startElement("Policy");
					$w->writeAttribute("xmlns", XACML_NSV3);
					$w->writeAttribute("xmlns:xsi", XACML_XSIV3);
					$w->writeAttribute("xsi:schemaLocation", XACML_XSDV3);
					$w->writeAttribute("PolicyId", $serviceId);					
					$w->writeAttribute("RuleCombiningAlgId", RULE_PERMIT_OVERRIDES);
						$w->writeElement("Description", $serviceDesc);						
						
						// Policy target
						XacmlPolicyGenerator::writePolicyV3Target($w, $serviceUrl, $enterpriseId, $serviceId);
						
						// Rules
						foreach($data["resource"] as $key => $value) {
							XacmlPolicyGenerator::writeRule($w, $data, $key, 'V3');
							// XacmlPolicyGenerator::generateRequests($data, $key, 'V3', $serviceId, $serviceUrl, $enterpriseId);							
						}
						
						$w->startElement("Rule");
							$w->writeAttribute("RuleId", "else");
							$w->writeAttribute("Effect", "Deny");
						$w->endElement();
				$w->endElement(); // </Policy>
			$w->endDocument();
			$w->outputMemory(TRUE);
		}
		
    /**
     * A function to write the target of the Policy compliant to XACMLv2.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $serviceUrl - The service's URL.
     * @return <void>
     */
		private static function writePolicyV2Target($w, $serviceUrl, $enterpriseId) {
			$w->startElement("Target");
				$w->startElement("Resources");
					$w->startElement("Resource");
						$w->startElement("ResourceMatch");
							$w->writeAttribute("MatchId", REGEXP_STRING_MATCH);
								$w->startElement("AttributeValue");
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->text($serviceUrl);
								$w->endElement();
							$w->startElement("ResourceAttributeDesignator");
								$w->writeAttribute("AttributeId", RESOURCE_ID);
								$w->writeAttribute("DataType", TYPE_STRING);
							$w->endElement();
						$w->endElement();
					$w->endElement();
				$w->endElement();				
				$w->startElement("Subjects");
					$w->startElement("Subject");
						$w->startElement("SubjectMatch");
							$w->writeAttribute("MatchId", STRING_EQUAL);
								$w->startElement("AttributeValue");
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->text($enterpriseId);
								$w->endElement();
							$w->startElement("SubjectAttributeDesignator");
								$w->writeAttribute("AttributeId", "urn:oasis:names:tc:xacml:1.0:subject:subject-id-qualifier");
								$w->writeAttribute("DataType", TYPE_STRING);
							$w->endElement();
						$w->endElement();
					$w->endElement();
				$w->endElement();				
			$w->endElement();		
		}
		
    /**
     * A function to write the target of the Policy compliant to XACMLv3.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $serviceUrl - The service's URL.
     * @return <void>
     */
		private static function writePolicyV3Target($w, $serviceUrl, $enterpriseId, $serviceId) {
			$w->startElement("Target");
				/*
				$w->startElement("AnyOf");
					$w->startElement("AllOf");					
						$w->startElement("Match");
							$w->writeAttribute("MatchId", REGEXP_STRING_MATCH);
							$w->startElement("AttributeValue");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->text($serviceUrl);
							$w->endElement();
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", RESOURCE_ID);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:resource");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();
						$w->endElement();
						$w->startElement("Match");
							$w->writeAttribute("MatchId", STRING_EQUAL);
							$w->startElement("AttributeValue");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->text($serviceId);
							$w->endElement();
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", "service-id");
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:1.0:subject-category:access-subject");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();
						$w->endElement();						
						$w->startElement("Match");
							$w->writeAttribute("MatchId", STRING_EQUAL);
							$w->startElement("AttributeValue");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->text($enterpriseId);
							$w->endElement();
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", "domain-id");
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:1.0:subject-category:access-subject");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();
						$w->endElement();
					$w->endElement();
				$w->endElement();
				*/
			$w->endElement();
		}
		
    /**
     * A function to write a rule.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */
		private static function writeRule($w, $data, $key, $version) {
			if(($data["subject"][$key]->idsrc != IDENTITY_SOURCE_NONE) || $key == 0) {			
				$w->startElement("Rule");
					$w->writeAttribute("RuleId", $key);
					$w->writeAttribute("Effect", "Permit");
					
					// Rule target
					if($version == 'V2')
						XacmlPolicyGenerator::writeRuleV2Target($w, $data, $key);
					else if($version == 'V3')
						XacmlPolicyGenerator::writeRuleV3Target($w, $data, $key);		
						
						$w->startElement("Condition");
							$w->startElement("Apply");
								$w->writeAttribute("FunctionId", L_AND);
								
								// Form subjects and roles only into a disjunction if both are setted
								if(!empty($data["subject"][$key]->users) &&
									!empty($data["subject"][$key]->roles)) {
									$w->startElement("Apply");
										$w->writeAttribute("FunctionId", L_OR);
								}
								
								// If subjects have been defined.
								if(!empty($data["subject"][$key]->users)) {
									XacmlPolicyGenerator::writeSubjects($w, $data, $key, $version);
								}
								
								if($data["subject"][$key]->idsrc != IDENTITY_SOURCE_IDM &&
									$data["subject"][$key]->idsrc != IDENTITY_SOURCE_NONE) {
									
									$w->startElement("Apply");
										$w->writeAttribute("FunctionId", "urn:oasis:names:tc:xacml:1.0:function:integer-equal");
										$w->startElement("Apply");
											$w->writeAttribute("FunctionId", "urn:oasis:names:tc:xacml:1.0:function:string-bag-size");
											
											$w->startElement("AttributeDesignator");
												if($data["subject"][$key]->idsrc == IDENTITY_SOURCE_HPC)
													$w->writeAttribute("AttributeId", HPC_ATTRIBUTE);
												elseif($data["subject"][$key]->idsrc == IDENTITY_SOURCE_NPA)
													$w->writeAttribute("AttributeId", NPA_ATTRIBUTE);
												elseif($data["subject"][$key]->idsrc == IDENTITY_SOURCE_EGK)
													$w->writeAttribute("AttributeId", EGK_ATTRIBUTE);
												$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:1.0:subject-category:access-subject");							
												$w->writeAttribute("DataType", TYPE_STRING);
												$w->writeAttribute("MustBePresent", "true");
											$w->endElement();				
											
										$w->endElement();
										$w->startElement("AttributeValue");
											$w->writeAttribute("DataType", "http://www.w3.org/2001/XMLSchema#integer");
											$w->text("1");
										$w->endElement();
										
									$w->endElement();
								}
								
								
								// If roles are defined do this.
								if(!empty($data["subject"][$key]->roles)) {
									XacmlPolicyGenerator::writeRoles($w, $data, $key, $version);
								}

								if(!empty($data["subject"][$key]->users) &&
									!empty($data["subject"][$key]->roles)) {						
									$w->endElement();
								}
								
								// If time has been defined, do this.
								if(!empty($data["time"][$key])) {
									if(!empty($data["time"][$key]->time[0])) {
										XacmlPolicyGenerator::writeTime($w, $data, $key, $version);
									}
									
									// If date has been defined, do this.
									if(!empty($data["time"][$key]->date[0])) {
										XacmlPolicyGenerator::writeDate($w, $data, $key, $version);
									}
								}
								
								// If locations have been defined.
								if(!empty($data["location"][$key])) {
									XacmlPolicyGenerator::writeLocation($w, $data, $key, $version);
								}
								
							$w->endElement(); // </Apply>
						$w->endElement(); // </Condition>
				$w->endElement(); // </Rule>
			}
		
		}
		
    /**
     * A function to write the rule's target compliant to XACMLv2.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */
		private static function writeRuleV2Target($w, $data, $key) {
			$w->startElement("Target");
				$w->startElement("Resources");
					$w->startElement("Resource");
						$w->startElement("ResourceMatch");
							$w->writeAttribute("MatchId", REGEXP_STRING_MATCH);
								$w->startElement("AttributeValue");
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->text($data["resource"][$key]);
								$w->endElement();
							$w->startElement("ResourceAttributeDesignator");
								$w->writeAttribute("AttributeId", RESOURCE_ID);
								$w->writeAttribute("DataType", TYPE_STRING);
							$w->endElement();
						$w->endElement();
					$w->endElement();
				$w->endElement();
				$w->startElement("Actions");
					$w->startElement("Action");
						$w->startElement("ActionMatch");
							$w->writeAttribute("MatchId", STRING_EQUAL);
							$w->startElement("AttributeValue");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->text($data["methods"][$key]);
							$w->endElement();
							$w->startElement("ActionAttributeDesignator");
								$w->writeAttribute("AttributeId", ACTION_ID);
								$w->writeAttribute("DataType", TYPE_STRING);
							$w->endElement();								
						$w->endElement();
					$w->endElement();
				$w->endElement();
			$w->endElement();
		}
		
    /**
     * A function to write the rule's target compliant to XACMLv3.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */
		private static function writeRuleV3Target($w, $data, $key) {
			$w->startElement("Target");
				$w->startElement("AnyOf");
					$w->startElement("AllOf");
						$w->startElement("Match");
							$w->writeAttribute("MatchId", REGEXP_STRING_MATCH);
							$w->startElement("AttributeValue");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->text($data["resource"][$key]);
							$w->endElement();
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", RESOURCE_ID);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:resource");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();
						$w->endElement();
						$w->startElement("Match");
							$w->writeAttribute("MatchId", STRING_EQUAL);
							$w->startElement("AttributeValue");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->text($data["methods"][$key]);
							$w->endElement();
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", ACTION_ID);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:action");
								$w->writeAttribute("DataType", TYPE_STRING);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();
						$w->endElement();
					$w->endElement();
				$w->endElement();
			$w->endElement();
		}
		
    /**
     * A function to write the subjects who are permitted.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */	
		private static function writeSubjects($w, $data, $key, $version) {
			$w->startElement("Apply");
				$w->writeAttribute("FunctionId", ANY_OF);
				$w->startElement("Function");
					$w->writeAttribute("FunctionId", STRING_EQUAL);
				$w->endElement();
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", STRING_ONE_AND_ONLY);
					if($version == "V2") {
						$w->startElement("SubjectAttributeDesignator");
							$w->writeAttribute("AttributeId", SUBJECT_ID);
							$w->writeAttribute("DataType", TYPE_STRING);
						$w->endElement();
					}
					else if($version == "V3") {
						$w->startElement("AttributeDesignator");
							$w->writeAttribute("AttributeId", SUBJECT_ID);
							$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:1.0:subject-category:access-subject");							
							$w->writeAttribute("DataType", TYPE_STRING);
							$w->writeAttribute("MustBePresent", "true");
						$w->endElement();					
					}					
				$w->endElement();
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", STRING_BAG);
					
					// Iterate through subjects of this action.
					foreach($data["subject"][$key]->users as $subject) {
						$w->startElement("AttributeValue");
							$w->writeAttribute("DataType", TYPE_STRING);
							$w->text($subject);
						$w->endElement();
					}
				$w->endElement();
			$w->endElement();		
		}
		
    /**
     * A function to write the roles who are permitted.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */	
		private static function writeRoles($w, $data, $key, $version) {
			$w->startElement("Apply");
				$w->writeAttribute("FunctionId", ANY_OF);
				$w->startElement("Function");
					$w->writeAttribute("FunctionId", STRING_EQUAL);
				$w->endElement();
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", STRING_ONE_AND_ONLY);
					if($version == "V2") {
						$w->startElement("SubjectAttributeDesignator");
							$w->writeAttribute("AttributeId", SUBJECT_ROLE);
							$w->writeAttribute("DataType", TYPE_STRING);
						$w->endElement();
					}
					else if($version == "V3") {
						$w->startElement("AttributeDesignator");
							$w->writeAttribute("AttributeId", SUBJECT_ROLE);
							$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:1.0:subject-category:access-subject");
							$w->writeAttribute("DataType", TYPE_STRING);
							$w->writeAttribute("MustBePresent", "true");							
						$w->endElement();					
					}
				$w->endElement();
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", STRING_BAG);
					
					// Iterate through roles.
					foreach($data["subject"][$key]->roles as $role) {
						$w->startElement("AttributeValue");
							$w->writeAttribute("DataType", TYPE_STRING);
							$w->text($role);
						$w->endElement();
					}
				$w->endElement();
			$w->endElement();		
		}
		
    /**
     * A function to write the time frame in which an action is permitted.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */	
		private static function writeTime($w, $data, $key, $version) {
			$w->startElement("Apply");
			$w->writeAttribute("FunctionId", L_AND);
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", TIME_GREATER_THAN_OR_EQUAL);
					$w->startElement("Apply");
						$w->writeAttribute("FunctionId", TIME_ONE_AND_ONLY);
						if($version == "V2") {
							$w->startElement("EnvironmentAttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_TIME);
								$w->writeAttribute("DataType", TYPE_TIME);
							$w->endElement();
						}
						else if($version == "V3") {
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_TIME);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:environment");								
								$w->writeAttribute("DataType", TYPE_TIME);
								$w->writeAttribute("MustBePresent", "true");								
							$w->endElement();						
						}
					$w->endElement();
					$w->startElement("AttributeValue");
						$w->writeAttribute("DataType", TYPE_TIME);
						
						// The time-from value.
						$w->text($data["time"][$key]->time[0]);
					$w->endElement();
				$w->endElement();
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", TIME_LESS_THAN_OR_EQUAL);
					$w->startElement("Apply");
						$w->writeAttribute("FunctionId", TIME_ONE_AND_ONLY);
						if($version == "V2") {
							$w->startElement("EnvironmentAttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_TIME);
								$w->writeAttribute("DataType", TYPE_TIME);
							$w->endElement();
						}
						else if($version == "V3") {
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_TIME);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:environment");								
								$w->writeAttribute("DataType", TYPE_TIME);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();						
						}						
					$w->endElement();
					$w->startElement("AttributeValue");
						$w->writeAttribute("DataType", TYPE_TIME);
						
						// The time-to value.
						$w->text($data["time"][$key]->time[1]);
					$w->endElement();
				$w->endElement();
			$w->endElement();		
		}
		
    /**
     * A function to write a date range in which an action is permitted.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */
		private static function writeDate($w, $data, $key, $version) {
			$w->startElement("Apply");
			$w->writeAttribute("FunctionId", L_AND);
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", DATE_GREATER_THAN_OR_EQUAL);
					$w->startElement("Apply");
						$w->writeAttribute("FunctionId", DATE_ONE_AND_ONLY);
						if($version == "V2") {
							$w->startElement("EnvironmentAttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_DATE);
								$w->writeAttribute("DataType", TYPE_DATE);
							$w->endElement();
						}
						else if($version == "V3") {
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_DATE);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:environment");								
								$w->writeAttribute("DataType", TYPE_DATE);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();						
						}						
					$w->endElement();
					$w->startElement("AttributeValue");
						$w->writeAttribute("DataType", TYPE_DATE);
						
						// The date-from value.
						$w->text($data["time"][$key]->date[0]);
					$w->endElement();
				$w->endElement();
				$w->startElement("Apply");
					$w->writeAttribute("FunctionId", DATE_LESS_THAN_OR_EQUAL);
					$w->startElement("Apply");
						$w->writeAttribute("FunctionId", DATE_ONE_AND_ONLY);
						if($version == "V2") {
							$w->startElement("EnvironmentAttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_DATE);
								$w->writeAttribute("DataType", TYPE_DATE);
							$w->endElement();
						}
						else if($version == "V3") {
							$w->startElement("AttributeDesignator");
								$w->writeAttribute("AttributeId", CURRENT_DATE);
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:environment");								
								$w->writeAttribute("DataType", TYPE_DATE);
								$w->writeAttribute("MustBePresent", "true");
							$w->endElement();						
						}						
					$w->endElement();
					$w->startElement("AttributeValue");
						$w->writeAttribute("DataType", TYPE_DATE);
						
						// The date-to value.
						$w->text($data["time"][$key]->date[1]);
					$w->endElement();
				$w->endElement();
			$w->endElement();		
		}
		
    /**
     * A function to write a location within an action is permitted.
     * @param <type> $w - The XML Writer instance.
     * @param <type> $data - The user's input.
     * @param <type> $key - The ID of the action.
     * @return <void>
     */	
		private static function writeLocation($w, $data, $key, $version) {
			$w->startElement("Apply");
				$w->writeAttribute("FunctionId", L_OR);
				
				// Iterate through locations.
				foreach($data["location"][$key] as $location) {
					$w->startElement("Apply");
						$w->writeAttribute("FunctionId", GEOMETRY_INTERSECTS);
						$w->startElement("Apply");
							$w->writeAttribute("FunctionId", GEOMETRY_ONE_AND_ONLY);
							if($version == "V2") {
								$w->startElement("EnvironmentAttributeDesignator");
									$w->writeAttribute("AttributeId", "position");
									$w->writeAttribute("DataType", TYPE_GEOMETRY);
									$w->writeAttribute("MustBePresent", "true");
								$w->endElement();
							}
							else if($version == "V3") {
								$w->startElement("AttributeDesignator");
									$w->writeAttribute("AttributeId", "position");
									$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:environment");									
									$w->writeAttribute("DataType", TYPE_GEOMETRY);
									$w->writeAttribute("MustBePresent", "true");
								$w->endElement();							
							}
						$w->endElement();
						$w->startElement("AttributeValue");
							$w->writeAttribute("DataType", TYPE_GEOMETRY);
							$w->startElement("gml:Polygon");
								$w->writeAttribute("xmlns:gml", "http://www.opengis.net/gml");
								$w->writeAttribute("srsName", "EPSG:4326");
									$w->startElement("gml:outerBoundaryIs");
										$w->startElement("gml:LinearRing");
											$w->startElement("gml:coordinates");
												$w->text($location);
											$w->endElement();												
										$w->endElement();
									$w->endElement();
							$w->endElement();
						$w->endElement();
					$w->endElement();
				}
			$w->endElement();		
		}
		
		private static function generateRequests($data, $key, $version, $serviceId, $serviceUrl, $enterpriseId) {
			$attr = array();
			if(!empty($data["subject"][$key]->users))
				$attr[] = "user";
			if(!empty($data["subject"][$key]->roles))
				$attr[] = "role";
			if(!empty($data["location"][$key]))
				$attr[] = "location";
			$attr[] = "correct";
			
			foreach($attr as $k => $value) {
				$w = new XMLWriter();
				if($value == "correct")
					$file = "../requests/".$enterpriseId."_".$serviceId."_".$key."_".$value."_".$version."_permit.xml";
				else
					$file = "../requests/".$enterpriseId."_".$serviceId."_".$key."_wrong_".$value."_".$version."_deny.xml";
				touch($file);
				$file = realpath($file);
				$w->openURI($file);
				$w->setIndent(true);
				$w->setIndentString("\t");
				$w->startDocument("1.0", "UTF-8");
					
					if($version == "V2") {
						$w->startElement("Request");
							$w->writeAttribute("xmlns", "urn:oasis:names:tc:xacml:2.0:context:schema:os");
							$w->writeAttribute("xmlns:gml", "http://www.opengis.net/gml");
							$w->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
							$w->writeAttribute("xsi:schemaLocation", "urn:oasis:names:tc:xacml:2.0:context:schema:os http://docs.oasis-open.org/xacml/access_control-xacml-2.0-context-schema-os.xsd");
							
							$w->startElement("Subject");
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", SUBJECT_ID);
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->startElement("AttributeValue");
										if($value == "user")
											$w->text("WrongUser");
										else if(!isset($data["subject"][$key]->users[0]) || $value == "role")
											$w->text("");
										else
											$w->text($data["subject"][$key]->users[0]);
									$w->endElement();
								$w->endElement();
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", "urn:oasis:names:tc:xacml:1.0:subject:subject-id-qualifier");
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->startElement("AttributeValue");
										$w->text($enterpriseId);
									$w->endElement();
								$w->endElement();
							
								if(in_array("role", $attr)) {
									$w->startElement("Attribute");
										$w->writeAttribute("AttributeId", SUBJECT_ROLE);
										$w->writeAttribute("DataType", TYPE_STRING);
										$w->startElement("AttributeValue");
											if($value == "role" || $value == "user")
												$w->text("WrongRole");
											else
												$w->text($data["subject"][$key]->roles[0]);
										$w->endElement();
									$w->endElement();
								}
							$w->endElement();
							
							$w->startElement("Resource");
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", RESOURCE_ID);
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->startElement("AttributeValue");
										$w->text($data["resource"][$key]);
									$w->endElement();
								$w->endElement();
							$w->endElement();
							
							$w->startElement("Action");
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", ACTION_ID);
									$w->writeAttribute("DataType", TYPE_STRING);
									$w->startElement("AttributeValue");
										$w->text($data["methods"][$key]);
									$w->endElement();
								$w->endElement();
							$w->endElement();			
							
							if(in_array("location", $attr)) {							
								$w->startElement("Environment");
									$w->startElement("Attribute");
										$w->writeAttribute("AttributeId", "position");
										$w->writeAttribute("DataType", TYPE_GEOMETRY);
										$w->startElement("AttributeValue");
											$w->startElement("gml:Point");
												$w->writeAttribute("srsName", "EPSG:4326");
												$w->startElement("gml:coordinates");
													if($value == "location")
														$w->text("0,0");
													else {
														$loc = explode(" ", $data["location"][$key][0]);
														$w->text($loc[0]);
													}
												$w->endElement();
											$w->endElement();
										$w->endElement();
									$w->endElement();									
								$w->endElement();
							}
						
						$w->endElement();
					}
					else if($version == "V3") {
						$w->startElement("Request");
							$w->writeAttribute("xmlns", "urn:oasis:names:tc:xacml:3.0:core:schema:wd-17" );
							$w->writeAttribute("xmlns:gml", "http://www.opengis.net/gml");
							$w->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
							$w->writeAttribute("xsi:schemaLocation", "urn:oasis:names:tc:xacml:3.0:core:schema:wd-17 http://docs.oasis-open.org/xacml/3.0/xacml-core-v3-schema-wd-17.xsd");
							$w->writeAttribute("ReturnPolicyIdList", "false");
							$w->writeAttribute("CombinedDecision", "false");

							$w->startElement("Attributes");
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:1.0:subject-category:access-subject");
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", SUBJECT_ID);
									$w->writeAttribute("IncludeInResult", "false");
									$w->startElement("AttributeValue");
										$w->writeAttribute("DataType", TYPE_STRING);
										if($value == "user")
											$w->text("WrongUser");
										else if(!isset($data["subject"][$key]->users[0]) || $value == "role")
											$w->text("");											
										else
											$w->text($data["subject"][$key]->users[0]);
									$w->endElement();
								$w->endElement();
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", "urn:oasis:names:tc:xacml:1.0:subject:subject-id-qualifier");
									$w->writeAttribute("IncludeInResult", "false");									
									$w->startElement("AttributeValue");
										$w->writeAttribute("DataType", TYPE_STRING);
										$w->text($enterpriseId);
									$w->endElement();
								$w->endElement();
							
								if(in_array("role", $attr)) {
									$w->startElement("Attribute");
										$w->writeAttribute("AttributeId", SUBJECT_ROLE);
										$w->writeAttribute("IncludeInResult", "false");										
										$w->startElement("AttributeValue");
											$w->writeAttribute("DataType", TYPE_STRING);
											if($value == "role" || $value == "user")
												$w->text("WrongRole");
											else
												$w->text($data["subject"][$key]->roles[0]);
										$w->endElement();
									$w->endElement();
								}
							$w->endElement();
							
							$w->startElement("Attributes");
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:resource");
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", RESOURCE_ID);
									$w->writeAttribute("IncludeInResult", "false");									
									$w->startElement("AttributeValue");
										$w->writeAttribute("DataType", TYPE_STRING);
										$w->text($data["resource"][$key]);
									$w->endElement();
								$w->endElement();
							$w->endElement();
							
							$w->startElement("Attributes");
								$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:action");
								$w->startElement("Attribute");
									$w->writeAttribute("AttributeId", ACTION_ID);
									$w->writeAttribute("IncludeInResult", "false");									
									$w->startElement("AttributeValue");
										$w->writeAttribute("DataType", TYPE_STRING);
										$w->text($data["methods"][$key]);
									$w->endElement();
								$w->endElement();
							$w->endElement();
							
							if(in_array("location", $attr)) {							
								$w->startElement("Attributes");
									$w->writeAttribute("Category", "urn:oasis:names:tc:xacml:3.0:attribute-category:environment");
									$w->startElement("Attribute");
										$w->writeAttribute("AttributeId", "position");
										$w->writeAttribute("IncludeInResult", "false");										
										$w->startElement("AttributeValue");
											$w->writeAttribute("DataType", TYPE_GEOMETRY);
											$w->startElement("gml:Point");
												$w->writeAttribute("srsName", "EPSG:4326");
												$w->startElement("gml:coordinates");
													if($value == "location")
														$w->text("0,0");
													else {
														$loc = explode(" ", $data["location"][$key][0]);
														$w->text($loc[0]);
													}
												$w->endElement();
											$w->endElement();
										$w->endElement();
									$w->endElement();									
								$w->endElement();
							}
							
						$w->endElement();
					}
					
				$w->endDocument();
				$w->outputMemory(TRUE);
			}
		}
	
	}

?>