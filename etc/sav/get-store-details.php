<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function get_store_details($sqlInstance, $store_Id){
/* 
	printf("store_Id = %d\n", $store_Id);
	printf("gender = %s\n", $gender);
	printf("age_range = %d\n", $age_range);
 */
	if($sqlInstance != NULL) {
		$arrayResults = array();
		//$whereClause = sprintf(" WHERE `store_Id` = '%d' ;", $store_Id);
		$whereClause = sprintf(" WHERE `store_Id` = '%d' ;", $store_Id);
		//printf("where clause = %s\n", $whereClause);
		if( $sqlInstance->getStoreDetails($arrayResults, $whereClause)){
            		if(count($arrayResults) > 0){
				//printf("count of array results = %d\n", count($arrayResults));
				printf("<storeDetailsList>\n");
                		foreach($arrayResults as &$ArrayFetch){
                    			echo($ArrayFetch );
                		}
				printf("</storeDetailsList>\n");
             		}else
                 		printf("</storeDetailsList>\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}


?>
