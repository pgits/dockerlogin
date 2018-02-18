<?php

include('/var/www/etc/insert-details.php');

if(isset($branch))
	$branch                = escapeshellcmd($_POST['branch']);
if(isset($packageName))
	$packageName           = escapeshellcmd($_POST['packageName']);
if(isset($packageBuiltPath))
	$packageBuiltPath      = escapeshellcmd($_POST['packageBuiltPath']);
if(isset($packageRevision))
	$packageRevision       = escapeshellcmd($_POST['packageRevision'] );
if(isset($lastGoodBuildUsed))
	$lastGoodBuildUsed     = escapeshellcmd($_POST['lastGoodBuildUsed'] );
if(isset($OS_RELEASE))
	$OS_RELEASE	       = escapeshellcmd($_POST['OS_RELEASE'] );
if(isset($serverName))
	$serverName	       = escapeshellcmd($_POST['serverName'] );

$branch="testing";
$packageName="l2-services";
$packageRevision="3.0.1.496";
$packageBuiltPath="ngos/workspace/debian/jessie/x86_64/build/pas/package";
$lastGoodBuildUsed="6536";
$OS_RELEASE="jessie";
$serverName="build-eqx-02";

insert_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName )

?>

