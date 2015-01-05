<style>
	html, body { margin: 0; padding: 0; }
	table { width: 100%; }
	thead td { font-weight: bold; background: #2d2d2d; color: white; }
	tr:nth-child(even) { background: #e7e7e7; }
	tr:nth-child(even) td { border-top: 1px solid grey; border-bottom: 1px solid grey; }
	tr:last-child td { border-bottom: 1px solid grey; }
	tr:hover { background: #B5D0EB }
	td { padding: 6px }
</style>
<?php

	include("../inc/global/globals.inc.php");
	include("../".MODEL_PATH."XacmlPdpHandler.class.php");
	
	print "
		<table cellpadding='0' cellspacing='0'>
			<thead>
				<tr>
					<td>Operation</td>
					<td>HTTP Status Code</td>
					<td width='70%'>Result</td>
				</tr>
			</thead>
			<tbody>
	";
	
	// Retrieve a decision from the PDP
	$response = XacmlPdpHandler::xacmlRequest(file_get_contents("../requests/example_request.xml"));
	print "
		<tr>
			<td>Retrieve a decision from the PDP</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";
	
	// Retrieve all policies for a client
	$response = XacmlPdpHandler::getClientsPolicies("MMS");
	print "
		<tr>
			<td>Retrieve all policies for a client</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";	
	
	// Retrieve a specific policy
	$response = XacmlPdpHandler::getServicePolicy("MMS", "unknown");
	print "
		<tr>
			<td>Retrieve a specific policy</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";
	
	// Put a policy
	$response = XacmlPdpHandler::putServicePolicy("MMS", "unknown", file_get_contents("../policies/example_policy.xml"));
	print "
		<tr>
			<td>Put a policy</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";	
	
	// Delete a policy
	$response = XacmlPdpHandler::deleteServicePolicy("MMS", "unknown");
	print "
		<tr>
			<td>Delete a policy</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";
	
	XacmlPdpHandler::putServicePolicy("MMS", "unknown", file_get_contents("../policies/example_policy.xml"));
	
	print "</tbody></table>";

?>