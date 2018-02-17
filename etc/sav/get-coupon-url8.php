<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy-test.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function get_coupon_url($sqlInstance, $city_id, $graffiti_Id, $gender, $age_range){
/*  
	printf("city_id = %d\n", $city_id);
	printf("graffiti_Id = %d\n", $graffiti_Id);
	printf("gender = %s\n", $gender);
	printf("age_range = %d\n", $age_range);
 */
	if($sqlInstance != NULL) {
		$arrayResults = array();
		$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `graffiti_Id` = '%d' ;", $city_id, $graffiti_Id);
		//printf("where clause = %s\n", $whereClause);
		if( $sqlInstance->getGraffitiCouponUrl($arrayResults, $whereClause)){
            		if(count($arrayResults) > 0){
				//printf("count of array results = %d\n", count($arrayResults));
				printf("<graffitiCouponHeader>\n");
                		foreach($arrayResults as &$ArrayFetch){
                    			echo($ArrayFetch );
                		}
				printf("</graffitiCouponHeader>\n");
             		}else
                 		printf("</graffitiCouponHeader>\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

?>
