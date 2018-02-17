<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy-test.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getAuthorIdForiPad($sqlInstance, $nickname, $email, $adGuid, $street, $city_Id, $city, $state, $zip_code, $country_name, $country_code, $gender, $age, $gpsOn, $latitude, $longitude){
	if($sqlInstance != NULL) {
		$sqlInstance->getAuthorIdForiPad($nickname, $email, $adGuid, $street, $city_Id, $city, $state, $zip_code, $country_name, $country_code, $gender, $age, $gpsOn, $latitude, $longitude);
	} else
    		echo("failed to open database\n");
}

?>
