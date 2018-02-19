<?php

include('/var/www/etc/insert-details.php');

//if (isset($_POST["mail"]) && !empty($_POST["mail"]))
if(isset($_POST["branch"]))
	$branch                = escapeshellcmd($_POST['branch']);
if(isset($_POST["packageName"]))
	$packageName           = escapeshellcmd($_POST['packageName']);
if(isset($_POST["packageBuiltPath"]))
	$packageBuiltPath      = escapeshellcmd($_POST['packageBuiltPath']);
if(isset($_POST["packageRevision"]))
	$packageRevision       = escapeshellcmd($_POST['packageRevision']);
if(isset($_POST["lastGoodBuildUsed"]))
	$lastGoodBuildUsed     = escapeshellcmd($_POST['lastGoodBuildUsed']);
if(isset($_POST["OS_RELEASE"]))
	$OS_RELEASE	       = escapeshellcmd($_POST['OS_RELEASE']);
if(isset($_POST["serverName"]))
	$serverName	       = escapeshellcmd($_POST['serverName']);
if(isset($_POST["pipeline_id"]))
	$pipeline_id	       = escapeshellcmd($_POST['pipeline_id']);
printf("pipeline_id = %s", $pipeline_id);
insert_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName, $pipeline_id )

?>

