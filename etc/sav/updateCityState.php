<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function updateCityState($sqlInstance, $city_Id, $CityNameAlternate, $StateAlternate, $ExistingCityName, $ExistingStateProvince){

	if($sqlInstance != NULL) 
		$sqlInstance->updateCityState($city_Id, $CityNameAlternate, $StateAlternate, $ExistingCityName, $ExistingStateProvince);
	 else
    		echo("failed to open database\n");
}

?>
