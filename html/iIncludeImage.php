<?php

include('/var/www/etc/update-details.php');

if(isset($packageTransactionId))
	$packageTransactionId           = escapeshellcmd($_POST['packageTransactionId']);
if(isset($imageBaseName))
	$imageBaseName           	= escapeshellcmd($_POST['imageBaseName']);
if(isset($imageBaseFullPathAndName))
	$imageBaseFullPathAndName       = escapeshellcmd($_POST['imageBaseFullPathAndName']);

update_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName )

?>

