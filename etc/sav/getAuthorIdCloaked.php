<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
//include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getAuthorId_Cloaked($sqlInstance, $adGuid, $latitude, $longitude){
	if($sqlInstance != NULL) {
		$sqlInstance->getAuthorIdCloaked($adGuid, $latitude, $longitude);
	} else
    		echo("failed to open database\n");
}

?>
