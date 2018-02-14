<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
//include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function get_groupon_page_json($sqlInstance, $city_Id, $graffiti_Id, $gender, $age_range){
/*  
	printf("city_id = %d\n", $city_id);
	printf("graffiti_Id = %d\n", $graffiti_Id);
	printf("gender = %s\n", $gender);
	printf("age_range = %d\n", $age_range);
 */
	if($sqlInstance != NULL) {
		$arrayResults = array();
		if($city_Id >= 1)
			$whereClause = sprintf(" WHERE city_Id = '%d' AND graffiti_Id = '%d' ;", $city_Id, $graffiti_Id);
		else
			$whereClause = sprintf(" WHERE graffiti_Id = '%d' ;", $graffiti_Id);
		//printf("where clause = %s\n", $whereClause);
		if( $sqlInstance->getGraffitiCouponUrl($arrayResults, $whereClause)){
		//if( $sqlInstance->getGraffitiCouponUrl($arrayResults, $whereClause))
            		if(count($arrayResults) > 0){
				//printf("count of array results = %d\n", count($arrayResults));
				printf("{\n\"graffitiCouponHeader\":\n");
				echo(json_encode($arrayResults));
				printf("\n}");
             		}else
                 		printf("{\n\"graffitiCouponHeader\":[]}\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

?>
