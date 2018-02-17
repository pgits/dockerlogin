<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getCityId($sqlInstance, $city, $state, $country, $country_abbr){
	if($sqlInstance != NULL) {
		$sqlInstance->GetCityId($city, $state, $country, $country_abbr);
	} else
    		echo("failed to open database\n");
}

?>
