<?php
//this code will look for all GeoNameId = NULL and VERSSION = 1.8.02 and CityNameAlternate = NULL
//and fill it in with the appropriate names

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-utils.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);
$sqlInstance2 = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function updateCityState($sqlInstance, $city_Id, $CityNameAlternate, $StateAlternate, $ExistingCityName, $ExistingStateProvince){

	if($sqlInstance != NULL) 
		$sqlInstance->updateCityState($city_Id, $CityNameAlternate, $StateAlternate, $ExistingCityName, $ExistingStateProvince);
	 else
    		echo("failed to open database\n");
}


if($sqlInstance != NULL) 
	$sqlInstance->script_getAllCities($sqlInstance2);
else
	echo "sqlInstance is null";

//	var $citiesTable                = "`cities`";

?>
