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

function get_graffiti_gps_3($sqlInstance, $city_Id, $country_code, $application_type, $gender, $age_range, $lat, $long, $radius, $milesOrKilos, $viewer_Id){
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
		$arrayFilterResults = array();

		if($application_type == 9){
			$whereClause = sprintf(" WHERE viewer_Id = '%d' AND optOut.untilTime > NOW()", $viewer_Id);
			$sqlInstance->getOptOutDetails($arrayFilterResults, $whereClause);
				//returns a set of items to filter; i.e.don't send
			$whereClause = sprintf(" WHERE `envelope_Id` = '%d' AND `country_code` LIKE '%s' ", 
			$application_type, $country_code); 
		}

		$coordinateBounds1 = sprintf(" AND ((`x_coordinate` >= '%s' AND `x_coordinate` <= '%s') AND ", $coordinates[0]->getLatitudeInDegrees(), $coordinates[1]->getLatitudeInDegrees()); 
		$coordinateBounds2 = sprintf(" (`y_coordinate` >= '%s' AND `y_coordinate` <= '%s')) ORDER BY x_coordinate, y_coordinate DESC; ", $coordinates[0]->getLongitudeInDegrees(), $coordinates[1]->getLongitudeInDegrees()); 
		$whereClause = $whereClause . $coordinateBounds1 . $coordinateBounds2;	
/*  
		printf("where clause = %s\n", $whereClause);
*/
		if( $sqlInstance->getGraffitiMessages_3($arrayResults, $whereClause, $arrayFilterResults)){
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
