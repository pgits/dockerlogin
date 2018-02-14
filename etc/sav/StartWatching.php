<?php

#include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function start_watching($sqlInstance, &$whereAmI, $mac_address, $graffiti_author, $city_code){
	if($sqlInstance != NULL) {
		$arrayResults = array();
		if( $sqlInstance->sqlStartWatching($whereAmI, $mac_address, $graffiti_author, $city_code)){
            		if(count($arrayResults) > 0){
				printf("count of array results = %d\n", count($arrayResults));
                		foreach($arrayResults as &$ArrayFetch){
                    			$whereAmI = $ArrayFetch;
                    			echo($ArrayFetch );
                		}
             		}else
                 		printf("<result>TRUE</result>\n");
		}else
	    		echo("</AlreadyWatching>\n");

	} else
    		echo("failed to open database\n");
}

?>
