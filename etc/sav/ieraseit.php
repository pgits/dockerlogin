<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function iEraseIt($sqlInstance, $graffiti_Id, $city_Id, $graffiti_author_Id, $icon_name){
	if($sqlInstance != NULL) {
		$sqlInstance->iEraseIt($graffiti_Id, $city_Id, $graffiti_author_Id, $icon_name);
	} else
    		echo("failed to open database\n");
}

?>
