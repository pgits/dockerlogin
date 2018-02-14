<?php
#this routine returns success if the record is updated

include('/home1/produdn8/etc/groupon/reclaimSwiggen8.php');

$graffiti_Id 		= escapeshellcmd($_GET['g_Id']);
$user_Id 		= escapeshellcmd($_GET['u_Id']);
$store_Id 		= escapeshellcmd($_GET['store']);
$swiggen_number 	= escapeshellcmd($_GET['sn']);
$swiggen_descr 		= escapeshellcmd($_GET['sd']);
$city_Id    		= escapeshellcmd($_GET['city_Id']);

$graffiti_Id = 58;
$city_Id = 9;
$user_Id = 37;
$store_Id = 16;
$swiggen_number = 17;
$swiggen_descr = "testing php scripts";

reclaimSwiggen8($sqlInstance, $city_Id, $graffiti_Id, $user_Id, $store_Id, $swiggen_number, $swiggen_descr);

?>
