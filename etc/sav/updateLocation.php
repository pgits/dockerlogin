<?php

//include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function post_location($sqlInstance, $macAddress, $x, $y, $z, $city_Id, $building_Id, $building_floor ){
	if($sqlInstance != NULL) {
		$arrayResults = array();
		if( $sqlInstance->sqlPostLocationUpdates(&$arrayResults, $macAddress, $x, $y, $z, $city_Id, $buding_Id, $building_floor)){
            		if(count($arrayResults) > 0){
				printf("count of array results = %d\n", count($arrayResults));
                		foreach($arrayResults as &$ArrayFetch){
                    			$Messages = $ArrayFetch;
                    			echo("returned element " . $ArrayFetch );
                		}
             		}else
                 		printf("<result>TRUE</result>\n");//printf("arrayResults = 0\n");
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

?>
