<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');

$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function iOptOut($sqlInstance, $graffiti_Id, $author_Id, $days){
	if($sqlInstance != NULL) {
		$sqlInstance->i_OptOut($graffiti_Id, $author_Id, $days);
	} else
    		echo("failed to open database\n");
}

?>
