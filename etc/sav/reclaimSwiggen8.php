<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy-test.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function reclaimSwiggen8($sqlInstance, $city_Id, $graffiti_Id, $reviewer_Id, $store_Id, $swiggen_number, $swiggen_descr){
	if($sqlInstance != NULL) {
/* 
			echo "inside ~/etc/groupon/reclaimSwiggen.php  calling reclaimSwiggen with";
			echo "graffiti_Id = " . $graffiti_Id;
			echo "user_Id = " . $reviewer_Id;
			echo "store_Id = " . $store_Id;
			echo "swiggen_descr = " . $swiggen_descr;
*/
		$sqlInstance->reclaimSwiggen8($city_Id, $graffiti_Id, $reviewer_Id, $store_Id, $swiggen_number, $swiggen_descr);
	} else
    		echo("failed to open database\n");
}

?>
