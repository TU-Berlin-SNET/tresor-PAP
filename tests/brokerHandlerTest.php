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
	include("../".MODEL_PATH."BrokerHandler.class.php");
	
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
	
	// Retrieve bookings of a client
	$response = BrokerHandler::getBookings("92707293-e2e9-4d09-bad1-5c37bdfd0b09");
	print "
		<tr>
			<td>Retrieve bookings of a client</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";
	
	// Parse bookings of a client
	$response = BrokerHandler::parseBookings($response["content"]);
	
	// Retrieve a certain service
	$response = BrokerHandler::getService($response[0]["service_url"]);
	print "
		<tr>
			<td>Retrieve a certain service</td>
			<td>".$response["info"]["http_code"]."</td>
			<td>".htmlentities($response["content"])."</td>
		</tr>
	";
	
	// Parse a certain service
	$response = BrokerHandler::parseService($response["content"]);
	
	print "</tbody></table>";

?>