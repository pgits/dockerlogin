<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function iGetNumberOfLikes($sqlInstance, $graffiti_Id, $city_Id, $graffiti_author_Id, $envelope_Id){
	if($sqlInstance != NULL) {
		$sqlInstance->iGetNumberOfLikes($graffiti_Id, $city_Id, $graffiti_author_Id, $envelope_Id);
	} else
    		echo("failed to open database\n");
}

?>
