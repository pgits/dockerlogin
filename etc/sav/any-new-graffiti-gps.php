<?php

include '/home1/produdn8/etc/gps/src/AnthonyMartin/GeoLocation/GeoLocation.php';
use AnthonyMartin\GeoLocation\GeoLocation as GeoLocation;

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');

$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function boundRect($inLat, $inLong, $howFar, $milesOrKilos){
/*
	echo "incoming latitude = " . $inLat . "\n";
	echo "incoming longitude = " . $inLong . "\n";
	echo "how far = " . $howFar . "\n";
	echo "miles or kilos = " . $milesOrKilos . "\n";
*/
        $currentLocation = GeoLocation::fromDegrees($inLat, $inLong);
        $coordinates = $currentLocation->boundingCoordinates($howFar, $milesOrKilos);

/* 
        echo "min latitude: " . $coordinates[0]->getLatitudeInDegrees() . " \n";
        echo "min longitude: " . $coordinates[0]->getLongitudeInDegrees() . " \n";

        echo "max latitude: " . $coordinates[1]->getLatitudeInDegrees() . " \n";
        echo "max longitude: " . $coordinates[1]->getLongitudeInDegrees() . " \n";
 */
        return $coordinates;
}

function any_new_graffiti_gps($sqlInstance, $graffitiMessage, $city_Id, $country_code, $application_type, $gender, $age_range, $lat, $long, $radius, $milesOrKilos){
	$whereClause = "";
	$coordinates = boundRect($lat, $long, $radius, $milesOrKilos);
/*  
	printf("city_Id = %d\n", $city_Id);
	printf("country_code = [%s]\n", $country_code);
	printf("application_type = %d\n", $application_type);
	printf("gender = %s\n", $gender);
	printf("age_range = %d\n", $age_range);
*/
	$testIfStar = "\*";
	$justStar   = "*";
	if($sqlInstance != NULL) {
		$arrayResults = array();

		if($country_code === $testIfStar || $country_code === $justStar)
			$whereClause = sprintf(" WHERE `city_Id` = '%d' ", $city_Id); 
		else if($application_type < 4)
                        $whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` <= '%d' ", $city_Id, $application_type);
                       /*  $whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` <= '%d' AND `country_code` LIKE '%s' ", $city_Id, $application_type, $country_code);
			*/
		else if($application_type == '5')
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` <= '%d' AND `country_code` LIKE '%s' ", $city_Id, $application_type, $country_code); 
		else if($application_type == 4 || $application_type > 5)//Transparent Walls, iDine or just VDMS
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` = '%d' AND `country_code` LIKE '%s' ", $city_Id, $application_type, $country_code);

		$coordinateBounds1 = sprintf(" AND ((`x_coordinate` >= '%s' AND `x_coordinate` <= '%s') AND ", $coordinates[0]->getLatitudeInDegrees(), $coordinates[1]->getLatitudeInDegrees()); 
		$coordinateBounds2 = sprintf(" (`y_coordinate` >= '%s' AND `y_coordinate` <= '%s')); ", $coordinates[0]->getLongitudeInDegrees(), $coordinates[1]->getLongitudeInDegrees()); 
		$whereClause = $whereClause . $coordinateBounds1 . $coordinateBounds2;	
/*  
		printf("where clause = %s\n", $whereClause);
*/
		if( $sqlInstance->anyNewGraffitiMessages($graffitiMessage, $whereClause)){
			//var_dump($graffitiMessage);
			//printf("count of array results = %d\n", count($graffitiMessage));
			printf("<graffitiMessageList>\n");
			printf("<graffiti_Id>%s</graffiti_Id>\n", $graffitiMessage->graffiti_Id);
			printf("<created_timestamp>%s</created_timestamp>\n", $graffitiMessage->created_datetime);
			printf("</graffitiMessageList>\n");
             	}else
			printf("</graffitiMessageList>\n");
	}else
		echo("failed on mytestSql\n");
}


$graffitiMessage = new graffitiMessageInstance(NULL);

#$country_code = "USA";
#$city_Id = 0;
#any_new_graffiti($sqlInstance, $graffitiMessage, $city_Id, $country_code);

?>
