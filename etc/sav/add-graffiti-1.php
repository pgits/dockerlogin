<?php

#include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');


$graffit_message_id = -1;
$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function post_graffiti($sqlInstance, $graffiti_author, $graffitiMessage, $country_code, $x, $y, $z, $metersVisible, $now, $until){
	if($sqlInstance != NULL) {
		$arrayResults = array();
		$graffiti_message_id = $sqlInstance->postGraffitiMessage($graffiti_author, $graffitiMessage, $country_code, $x, $y, $z, $metersVisible, $now, $until);
		if($graffiti_message_id != -1) {
			return $graffiti_message_id;
		}else
	    		echo("failed on mytestSql\n");

	} else
    		echo("failed to open database\n");
}

$graffitiMessages = new graffitiMessageInstance(NULL);

#$country_code = "USA";
#$graffiti_author = "pgits@geekgaps.com";
#$graffitiMessage = "snoozing";
#$x = 10;
#$y = 3;
#$metersVisible = 31;
#$sqlInstance->getNowPlus($date, $until);
#post_graffiti($sqlInstance, $graffiti_author, $graffitiMessage, $country_code, $x, $y, NULL, $metersVisible, $date, $until);

?>
