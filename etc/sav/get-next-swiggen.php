<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getNextSwiggen($sqlInstance, $graffiti_Id, $store_Id ){
	if($sqlInstance != NULL) {
/*
			echo "inside ~/etc/starbucks/reclaimSwiggen.php  calling reclaimSwiggen with";
			echo "graffiti_Id = " . $graffiti_Id;
			echo "user_Id = " . $reviewer_Id;
			echo "store_Id = " . $store_Id;
			echo "swiggen_descr = " . $swiggen_descr;
*/
		$sqlInstance->getNextSwiggen($graffiti_Id, $store_Id);
	} else
    		echo("failed to open database\n");
}

?>
