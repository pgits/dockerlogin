<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy-test.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function isUserBanished($sqlInstance, $city_Id, $author_Id, $alias, $email){
	if($sqlInstance != NULL) {
		$sqlInstance->isUserBanished8($city_Id, $author_Id, $alias, $email);
	} else
    		echo("failed to open database\n");
}

?>
