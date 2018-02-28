<?php

include('/var/www/etc/update-details.php');

if(isset($_POST["packageTransactionId"]))
	$packageTransactionId           = escapeshellcmd($_POST['packageTransactionId']);
if(isset($_POST["imageBaseName"]))
	$imageBaseName           	= escapeshellcmd($_POST['imageBaseName']);
if(isset($_POST["imageEnterpriseName"]))
	$imageEnterpriseName           	= escapeshellcmd($_POST['imageEnterpriseName']);
else
	$imageEnterpriseName		= "unknown";
if(isset($_POST["imageBaseFullPathAndName"]))
	$imageBaseFullPathAndName       = escapeshellcmd($_POST['imageBaseFullPathAndName']);
if(isset($_POST["imageEnterpriseFullPathAndName"]))
	$imageEnterpriseFullPathAndName       = escapeshellcmd($_POST['imageEnterpriseFullPathAndName']);

update_details($packageTransactionId, $imageBaseName, $imageBaseFullPathAndName, $imageEnterpriseName, $imageEnterpriseFullPathAndName )

?>

