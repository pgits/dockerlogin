<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function iBuy($sqlInstance, $graffiti_Id, $author_Id){
	if($sqlInstance != NULL) {
		$sqlInstance->i_Buy($graffiti_Id, $author_Id);
	} else
    		echo("failed to open database\n");
}

?>
