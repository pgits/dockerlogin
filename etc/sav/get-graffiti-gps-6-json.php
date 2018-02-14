<?php

include '/home1/produdn8/etc/gps/src/AnthonyMartin/GeoLocation/GeoLocation.php';
use AnthonyMartin\GeoLocation\GeoLocation as GeoLocation;

//include('/home1/produdn8/etc/groupon/db-4-kilroy.php');
include('/home1/produdn8/etc/groupon/dbcfg-2.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-3.php');

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

function get_graffiti_gps_6_json($sqlInstance, $lat, $long, $radius, $milesOrKilos, $viewer_Id){
	$application_type = 9;
	$whereClause = "";
	$coordinates = boundRect($lat, $long, $radius, $milesOrKilos);
	if($sqlInstance != NULL) {
		$arrayResults = array();

		$whereClause = sprintf(" WHERE `envelope_Id` = '%d' AND `expires` > NOW()  ", $application_type); 
	}

	$coordinateBounds1 = sprintf(" AND ((`x_coordinate` >= '%s' AND `x_coordinate` <= '%s') AND ", $coordinates[0]->getLatitudeInDegrees(), $coordinates[1]->getLatitudeInDegrees()); 
	$coordinateBounds2 = sprintf(" (`y_coordinate` >= '%s' AND `y_coordinate` <= '%s')) ORDER BY x_coordinate, y_coordinate DESC; ", $coordinates[0]->getLongitudeInDegrees(), $coordinates[1]->getLongitudeInDegrees()); 
	$whereClause = $whereClause . $coordinateBounds1 . $coordinateBounds2;	
	//printf("where clause = %s\n", $whereClause);
	if( $sqlInstance->getGraffitiMessages_6($arrayResults, $whereClause)){
/*
	    if(count($arrayResults) > 0){
		$i = 0;
                foreach($arrayResults as $myResult){
		    printf("arrayResults[%d]=[%s]\n",  $i, $myResult);
		    $i++;
		}
	    }
	    header('Content-Type: application/json');
*/
            if(count($arrayResults) > 0){
		//printf("{\n\"graffitiMessageList\":\n");
		echo(json_encode($arrayResults));
		//printf("\n}");
             }else
                 //printf("{\n\"graffitMessageList\":[]\n}");
                 printf("[]");
	}else
	    echo("failed on mytestSql\n");

}

?>
