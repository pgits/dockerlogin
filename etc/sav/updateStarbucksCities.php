<?php
//this will duplicate all Starbucks graffiti_transactions, copuon_details, pkpass_details, stores_backing_coupon, and store_details
//adds a version number to this update to track it

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-utils.php');


$sqlInstance1 = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);
$sqlInstance2 = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

/*
function updateStarbucks($sqlInstance1, $city_Id, $CityNameAlternate, $StateAlternate, $ExistingCityName, $ExistingStateProvince){

	if($sqlInstance != NULL) 
		$sqlInstance->updateCityState($city_Id, $CityNameAlternate, $StateAlternate, $ExistingCityName, $ExistingStateProvince);
	 else
    		echo("failed to open database\n");
}
*/

if($sqlInstance1 != NULL) 
	$sqlInstance1->copyStarbucks($sqlInstance2);
else
	echo "sqlInstance is null";


?>
