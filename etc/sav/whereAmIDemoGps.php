<?php

//include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function getWhereAmIDemoGps($sqlInstance, $city_code){
	if($sqlInstance != NULL) {
		$arrayResults = array();
		if( $sqlInstance->sqlGetWhereAmIDemoGps($arrayResults, $city_code)){
            		if(count($arrayResults) > 0){
				//printf("count of array results = %d\n", count($arrayResults));
                		foreach($arrayResults as &$ArrayFetch){
                    			$whereAmI = $ArrayFetch;
                    			echo($ArrayFetch );
                		}
             		}else
                 		printf("</whereAmIDemoGps>\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

?>
