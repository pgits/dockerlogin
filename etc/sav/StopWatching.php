<?php

//include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function stop_watching($sqlInstance, &$whereAmI, $mac_address, $city_code){
	if($sqlInstance != NULL) {
		$arrayResults = array();
		if( $sqlInstance->sqlStopWatchingSelect($whereAmI, $mac_address, $city_code)){
            		if(count($arrayResults) > 0){
				printf("count of array results = %d\n", count($arrayResults));
                		foreach($arrayResults as &$ArrayFetch){
                    			$whereAmI = $ArrayFetch;
                    			echo("returned element " . $ArrayFetch );
                		}
             		}else
                 		printf("<result>TRUE</result>\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

?>
