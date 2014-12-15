<?php
	
	define("XACML_NSV2", "urn:oasis:names:tc:xacml:2.0:policy:schema:os");
	define("XACML_XSIV2", "http://www.w3.org/2001/XMLSchema-instance");
	define("XACML_XSDV2", "urn:oasis:names:tc:xacml:2.0:policy:schema:os http://docs.oasis-open.org/xacml/2.0/access_control-xacml-2.0-policy-schema-os.xsd");
	
	define("XACML_NSV3", "urn:oasis:names:tc:xacml:3.0:core:schema:wd-17");
	define("XACML_XSIV3", "http://www.w3.org/2001/XMLSchema-instance");
	define("XACML_XSDV3", "urn:oasis:names:tc:xacml:3.0:core:schema:wd-17 http://docs.oasis-open.org/xacml/3.0/xacml-core-v3-schema-wd-17.xsd");

	define("RULE_PERMIT_OVERRIDES", "urn:oasis:names:tc:xacml:1.0:rule-combining-algorithm:permit-overrides");
	
	define("L_AND", "urn:oasis:names:tc:xacml:1.0:function:and");
	define("L_OR", "urn:oasis:names:tc:xacml:1.0:function:or");
	define("ANY_OF", "urn:oasis:names:tc:xacml:1.0:function:any-of");
	define("REGEXP_STRING_MATCH", "urn:oasis:names:tc:xacml:1.0:function:regexp-string-match");
	define("STRING_BAG", "urn:oasis:names:tc:xacml:1.0:function:string-bag");
	define("STRING_EQUAL", "urn:oasis:names:tc:xacml:1.0:function:string-equal");
	define("STRING_ONE_AND_ONLY", "urn:oasis:names:tc:xacml:1.0:function:string-one-and-only");
	
	define("TIME_ONE_AND_ONLY", "urn:oasis:names:tc:xacml:1.0:function:time-one-and-only");
	define("TIME_GREATER_THAN_OR_EQUAL", "urn:oasis:names:tc:xacml:1.0:function:time-greater-than-or-equal");
	define("TIME_LESS_THAN_OR_EQUAL", "urn:oasis:names:tc:xacml:1.0:function:time-less-than-or-equal");
	
	define("DATE_ONE_AND_ONLY", "urn:oasis:names:tc:xacml:1.0:function:date-one-and-only");
	define("DATE_GREATER_THAN_OR_EQUAL", "urn:oasis:names:tc:xacml:1.0:function:date-greater-than-or-equal");
	define("DATE_LESS_THAN_OR_EQUAL", "urn:oasis:names:tc:xacml:1.0:function:date-less-than-or-equal");
	
	define("GEOMETRY_INTERSECTS", "urn:ogc:def:function:geoxacml:1.0:geometry-intersects");
	define("GEOMETRY_ONE_AND_ONLY", "urn:ogc:def:function:geoxacml:1.0:geometry-one-and-only");
	
	define("TYPE_STRING", "http://www.w3.org/2001/XMLSchema#string");
	define("TYPE_TIME", "http://www.w3.org/2001/XMLSchema#time");
	define("TYPE_DATE", "http://www.w3.org/2001/XMLSchema#date");
	
	define("TYPE_GEOMETRY", "urn:ogc:def:dataType:geoxacml:1.0:geometry");
	
	define("RESOURCE_ID", "urn:oasis:names:tc:xacml:1.0:resource:resource-id");
	define("ACTION_ID", "urn:oasis:names:tc:xacml:1.0:action:action-id");
	
	define("SUBJECT_ID", "urn:oasis:names:tc:xacml:1.0:subject:subject-id");
	define("SUBJECT_ID_IDM", "org:snet:tresor:attribute:identity-source-idm");
	define("SUBJECT_ID_SKIDENTITY", "org:snet:tresor:attribute:identity-source-skidentity");
	
	define("SUBJECT_ROLE", "urn:oasis:names:tc:xacml:1.0:subject:subject:role");
	define("CURRENT_TIME", "urn:oasis:names:tc:xacml:1.0:environment:current-time");
	define("CURRENT_DATE", "urn:oasis:names:tc:xacml:1.0:environment:current-date");
	
?>