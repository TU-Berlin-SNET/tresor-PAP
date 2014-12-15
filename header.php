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

	include("inc/global/globals.inc.php");
	include(MODEL_PATH."SessionManagement.class.php");

	print "
		<table cellpadding='0' cellspacing='0'>
			<thead>
				<tr>
					<td>Key</td>
					<td width='50%'>Value</td>
				</tr>
			</thead>
			<tbody>
	";	
	
	$session = new SessionManagement();
	foreach($session->getTresorHeader() as $key => $value) {
		if(is_array($value)) {
			foreach($value as $k => $v)
				if(is_array($v))
					foreach($v as $i => $j)
						print "
							<tr><td>".$k."</td><td>".$j."</td></tr>
						";
				else
					print "
						<tr><td>".$k."</td><td>".$v."</td></tr>
					";
		}
		else
			print "
				<tr><td>".$key."</td><td>".$value."</td></tr>
			";
	}
	
	print "</tbody></table>";	

?>