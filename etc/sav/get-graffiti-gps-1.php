<?php

include '/home1/produdn8/etc/gps/src/AnthonyMartin/GeoLocation/GeoLocation.php';
use AnthonyMartin\GeoLocation\GeoLocation as GeoLocation;

//include('/home1/produdn8/etc/groupon/db-4-kilroy.php');
include('/home1/produdn8/etc/groupon/dbcfg-2.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');

$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function boundRect($inLat, $inLong, $howFar, $milesOrKilos){
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

function get_graffiti_gps_1($sqlInstance, $city_Id, $country_code, $application_type, $gender, $age_range, $lat, $long, $radius, $milesOrKilos){
	$whereClause = "";
	$coordinates = boundRect($lat, $long, $radius, $milesOrKilos);
/* 
	printf("city_Id = %d\n", $city_Id);
	printf("country_code = %s\n", $country_code);
	printf("application_type = %d\n", $application_type);
	printf("gender = %s\n", $gender);
	printf("age_range = %d\n", $age_range);
*/
	$testIfStar = "\*";
	$justStar   = "*";
	if($sqlInstance != NULL) {
		$arrayResults = array();

		if($country_code === $testIfStar || $country_code === $justStar)
			$whereClause = sprintf(" WHERE t1.`city_Id` = '%d' ", $city_Id); 
		else if($application_type < 4)
                        $whereClause = sprintf(" WHERE t1.`city_Id` = '%d' AND t1.`envelope_Id` <= '%d' ", $city_Id, $application_type);
                        /* $whereClause = sprintf(" WHERE t1.`city_Id` = '%d' AND t1.`envelope_Id` <= '%d' AND t1.`country_code` LIKE '%s' ", $city_Id, $application_type, $country_code);
			*/
		else if($application_type == 9)
			$whereClause = sprintf(" WHERE t1.`envelope_Id` = '%d' AND t1.`country_code` LIKE '%s' ", $application_type, $country_code); 
		else if($application_type == 8)
			$whereClause = sprintf(" WHERE t1.`envelope_Id` = '%d' AND t1.`country_code` LIKE '%s' ", $application_type, $country_code); 
		else if($application_type == '5')
			$whereClause = sprintf(" WHERE t1.`city_Id` = '%d' AND t1.`envelope_Id` <= '%d' AND t1.`country_code` LIKE '%s' ", $city_Id, $application_type, $country_code); 
		else if($application_type == 4 || $application_type > 5)//Transparent Walls, iDine or just VDMS
			$whereClause = sprintf(" WHERE t1.`city_Id` = '%d' AND t1.`envelope_Id` <= '%d' ", $city_Id, $application_type);

		$coordinateBounds1 = sprintf(" AND ((`x_coordinate` >= '%s' AND `x_coordinate` <= '%s') AND ", $coordinates[0]->getLatitudeInDegrees(), $coordinates[1]->getLatitudeInDegrees()); 
		$coordinateBounds2 = sprintf(" (`y_coordinate` >= '%s' AND `y_coordinate` <= '%s')) ORDER BY x_coordinate, y_coordinate DESC; ", $coordinates[0]->getLongitudeInDegrees(), $coordinates[1]->getLongitudeInDegrees()); 
		//$coordinateBounds2 = sprintf(" (`y_coordinate` >= '%s' AND `y_coordinate` <= '%s')) ORDER BY t1.`graffiti_Id` DESC; ", $coordinates[0]->getLongitudeInDegrees(), $coordinates[1]->getLongitudeInDegrees()); 
		$whereClause = $whereClause . $coordinateBounds1 . $coordinateBounds2;	
/* 
		printf("where clause = %s\n", $whereClause);
*/
		if( $sqlInstance->getGraffitiMessages($arrayResults, $whereClause)){
            		if(count($arrayResults) > 0){
				//printf("count of array results = %d\n", count($arrayResults));
				printf("<graffitiMessageList>\n");
                		foreach($arrayResults as &$ArrayFetch){
                    			echo($ArrayFetch );
                		}
				printf("</graffitiMessageList>\n");
             		}else
                 		printf("</graffitMessageList>\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

function get_graffitiTransparentWalls($sqlInstance, $city_Id, $country_code, $application_type, $gender, $age_range, $buildingId, $floorNumber){
	$whereClause = "";
/* 
	printf("city_Id = %d\n", $city_Id);
	printf("country_code = %s\n", $country_code);
	printf("application_type = %d\n", $application_type);
	printf("gender = %s\n", $gender);
	printf("age_range = %d\n", $age_range);
*/
	$testIfStar = "\*";
	$justStar   = "*";
	if($sqlInstance != NULL) {
		$arrayResults = array();

		if($country_code === $testIfStar || $country_code === $justStar)
			$whereClause = sprintf(" WHERE `city_Id` = '%d' ;", $city_Id); 
		else if($application_type < 4)
                        $whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` <= '%d' AND `country_code` LIKE '%s' ;", $city_Id, $application_type, $country_code);
		else if($application_type == '5')
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` <= '%d' AND `country_code` LIKE '%s' ;", $city_Id, $application_type, $country_code); 
		else if($application_type == 4 || $application_type == 5 || $application_type == 6)
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` = '%d' AND `country_code` LIKE '%s' ;", $city_Id, $application_type, $country_code);
		else if($application_type == 7)//Transparent Walls
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` = '%d' AND `building_Id` = '%d' AND `building_floor` = '%d' AND `country_code` LIKE '%s' ;", $city_Id, $application_type, $buildingId, $floorNumber, $country_code);
/*
		printf("where clause = %s\n", $whereClause);
*/
		if( $sqlInstance->getGraffitiMessages($arrayResults, $whereClause)){
            		if(count($arrayResults) > 0){
				//printf("count of array results = %d\n", count($arrayResults));
				printf("<graffitiMessageList>\n");
                		foreach($arrayResults as &$ArrayFetch){
                    			echo($ArrayFetch );
                		}
				printf("</graffitiMessageList>\n");
             		}else
                 		printf("</graffitMessageList>\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

?>
