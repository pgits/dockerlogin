<?php

include('/var/etc/insert-details.php');

$branch                = escapeshellcmd($_POST['branch']);
$packageName           = escapeshellcmd($_POST['packageName']);
$packageBuiltPath      = escapeshellcmd($_POST['packageBuiltPath']);
$packageRevision       = escapeshellcmd($_POST['packageRevision']);
$lastGoodBuildUsed     = escapeshellcmd($_POST['lastGoodBuildUsed']);
$OS_RELEASE	       = escapeshellcmd($_POST['OS_RELEASE']);
$serverName	       = escapeshellcmd($_POST['serverName']);
insert_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName )

?>

