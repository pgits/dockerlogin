<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-groupon-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function isUserBanished8($sqlInstance, $city_Id, $author_Id, $alias, $email){
	if($sqlInstance != NULL) {
		$sqlInstance->isUserBanished8($city_Id, $author_Id, $alias, $email);
	} else
    		echo("failed to open database\n");
}

?>
