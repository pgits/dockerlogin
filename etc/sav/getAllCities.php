<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getAllCities($sqlInstance, $city_IdStart, $city_IdEnd){
	if($sqlInstance != NULL) {
		$sqlInstance->GetAllCities($city_IdStart, $city_IdEnd);
	} else
    		echo("failed to open database\n");
}

?>
