<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getComment($sqlInstance, $graffiti_Id, $city_Id){
	if($sqlInstance != NULL) {
		$sqlInstance->getComment($graffiti_Id, $city_Id);
	} else
    		echo("failed to open database\n");
}

?>
