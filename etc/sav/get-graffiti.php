<?php

//include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function get_graffiti($sqlInstance, $city_id, $country_code){

	$testIfStar = "\*";
	$justStar   = "*";
	if($sqlInstance != NULL) {
		$arrayResults = array();
		if($country_code === $testIfStar || $country_code === $justStar)
			$whereClause = sprintf(" WHERE `city_Id` = '%d' ;", $city_id); 
		else 
			$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `country_code` LIKE '%s' ;", $city_id, $country_code); 
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
