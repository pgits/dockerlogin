<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function addComment($sqlInstance, $graffiti_Id, $city_Id, $graffiti_author_Id, $comment){
	if($sqlInstance != NULL) {
		$sqlInstance->addComment($graffiti_Id, $city_Id, $graffiti_author_Id, $comment);
	} else
    		echo("failed to open database\n");
}

?>
