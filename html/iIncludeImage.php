<?php

include('/var/www/etc/update-details.php');

if(isset($_POST["packageTransactionId"]))
	$packageTransactionId           = escapeshellcmd($_POST['packageTransactionId']);
if(isset($_POST["imageBaseName"]))
	$imageBaseName           	= escapeshellcmd($_POST['imageBaseName']);
if(isset($_POST["imageBaseFullPathAndName"]))
	$imageBaseFullPathAndName       = escapeshellcmd($_POST['imageBaseFullPathAndName']);

update_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName )

?>

