<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function isUserBanished($sqlInstance, $city_Id, $author_Id, $email){
	if($sqlInstance != NULL) {
		$sqlInstance->isUserBanished($city_Id, $author_Id, $email);
	} else
    		echo("failed to open database\n");
}

?>
