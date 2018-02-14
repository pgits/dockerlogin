<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function iReportedIt($sqlInstance, $graffiti_Id, $city_Id, $graffiti_author_Id){
	if($sqlInstance != NULL) {
		$sqlInstance->iReportedIt($graffiti_Id, $city_Id, $graffiti_author_Id);
	} else
    		echo("failed to open database\n");
}

?>
