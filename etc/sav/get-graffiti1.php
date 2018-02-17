<?php

//include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function get_graffiti1($sqlInstance, $city_Id, $country_code, $application_type, $gender, $age_range){
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
		else if($application_type == 4 || $application_type > 5)//Transparent Walls, iDine or just VDMS
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `envelope_Id` = '%d' AND `country_code` LIKE '%s' ;", $city_Id, $application_type, $country_code);
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
