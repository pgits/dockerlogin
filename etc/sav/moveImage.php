<?php

include('/home1/produdn8/etc/groupon/dbcfg-1.php');
include('/home1/produdn8/etc/groupon/db-4-kilroy-test-1.php');


$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function moveImage($sqlInstance, $from_city_Id, $to_city_Id, $graffiti_Id){
	if($sqlInstance != NULL) {
		$sqlInstance->moveImage($from_city_Id, $to_city_Id, $graffiti_Id);
		return true;
	}
	else
    		echo("failed to open database\n");
	return false;
}

//parse_str(implode('&', array_slice($argv, 1)), $_GET);
//$from_city = $_GET['from_city'];
//$to_city   = $_GET['to_city'];
//$graffiti_Id = $_GET['graffiti_Id'];
//$from_city = $argv[1];
//$to_city   = $argv[2];
//$graffiti_Id = $argv[3];
/* */
if (isset($argv)) {
	echo "argv is set \n";
    $from_city 	 = $argv[1];
    $to_city 	 = $argv[2];
    $g_Id = $argv[3];
}
else {
	//echo "argv is not set\n";
    $from_city   = $_GET['from_city'];
    $to_city     = $_GET['to_city'];
    $g_Id = $_GET['g_Id'];
}
echo "my from_city = " . $from_city . "<\p>\n";
echo "my to_city   = " . $to_city . "<\p>\n";
echo "my graffiti_Id = " . $g_Id . "<\p>\n";
//moveImage($sqlInstance, 307, 3442, 1207);
moveImage($sqlInstance, $from_city, $to_city, $g_Id);

?>
