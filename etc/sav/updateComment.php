<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function updateComment($sqlInstance, $graffiti_Id, $city_Id, $reviewer_Id, $comment){
	if($sqlInstance != NULL) {
		$sqlInstance->updateComment($graffiti_Id, $city_Id, $reviewer_Id, $comment);
	} else
    		echo("failed to open database\n");
}

?>
