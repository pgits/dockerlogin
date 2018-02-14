<?php

include('/home1/produdn8/etc/groupon/db-4-kilroy.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

$graffiti_Id 		= escapeshellcmd($_POST['graffiti_Id']);
$store_Id    		= escapeshellcmd($_POST['store_Id']);

$graffiti_Id		= 106;
$store_Id		= 16;
$OneWeekOut		= 1;

function getNextSwiggen($sqlInstance, $graffiti_Id, $store_Id){
	if($sqlInstance != NULL) {
		$sqlInstance->getNextSwiggen($graffiti_Id, $store_Id, $next_swiggen, $OneWeekOut);
		echo("next swiggen = " . $next_swiggen . "\n");
		echo("nowPlusOneWeek = " . $OneWeekOut . "\n");
	} else
    		echo("failed to open database\n");
}

getNextSwiggen($sqlInstance, $graffiti_Id, $store_Id);
?>
