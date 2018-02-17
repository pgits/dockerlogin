<?php

include('/home1/produdn8/etc/groupon/dbcfg-2.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');

$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function get_graffiti_gps_grafId($sqlInstance, $graffiti_Id, $application_type, $viewer_Id){
	if($sqlInstance != NULL) {
		$arrayResults = array();
		$arrayFilterResults = array();
		if($application_type == 9){
			$whereClause = sprintf(" WHERE t1.graffiti_Id = '%d' AND envelope_Id = '%d'", $graffiti_Id, $application_type);
			//echo "whereClause = [" . $whereClause . "]\n";

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

		}//end if application == 9
	} else
    		echo("failed to open database\n");
}

?>
