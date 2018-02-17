<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
//include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getCityId1($sqlInstance, $latitude, $longitude){
	if($sqlInstance != NULL) {
		//error_log("getCityId1:latitude = " . $latitude . ", longitude = " . $longitude . "\n" , 3, "/home1/produdn8/etc/groupon/NewCityLookup.1.log");
		$sqlInstance->GetCityId_1($latitude, $longitude);
	} else
    		echo("failed to open database\n");
}

?>
