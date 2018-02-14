<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function updateAuthorDetails($sqlInstance, $author_Id, $city_Id, $alias, $email, $gender, $age){
	if($sqlInstance != NULL) {
//echo "./etc/updateAuthorDetails.php author_Id is now [" . $author_Id . "]\n";
		$sqlInstance->updateAuthor_Details($author_Id, $city_Id, $alias, $email, $gender, $age );
	} else
    		echo("failed to open database\n");
}

?>
