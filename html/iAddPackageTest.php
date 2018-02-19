<?php

include('/var/www/etc/insert-details.php');

if(isset($_POST["branch"]))
	$branch                = escapeshellcmd($_POST['branch']);
if(isset($_POST["packageName"]))
	$packageName           = escapeshellcmd($_POST['packageName']);
if(isset($_POST["packageBuiltPath"]))
	$packageBuiltPath      = escapeshellcmd($_POST['packageBuiltPath']);
if(isset($_POST["packageRevision"]))
	$packageRevision       = escapeshellcmd($_POST['packageRevision'] );
if(isset($_POST["lastGoodBuildUsed"]))
	$lastGoodBuildUsed     = escapeshellcmd($_POST['lastGoodBuildUsed'] );
if(isset($_POST["OS_RELEASE"]))
	$OS_RELEASE	       = escapeshellcmd($_POST['OS_RELEASE'] );
if(isset($_POST["serverName"]))
	$serverName	       = escapeshellcmd($_POST['serverName'] );
if(isset($_POST["pipeline_id"]))
	$pipeline_id	       = escapeshellcmd($_POST['pipeline_id'] );

$branch="testing";
$packageName="l2-services";
$packageRevision="3.0.1.496";
$packageBuiltPath="ngos/workspace/debian/jessie/x86_64/build/pas/package";
$lastGoodBuildUsed="6536";
$OS_RELEASE="jessie";
$serverName="build-eqx-02";
$pipeline_id="something-special-pipeline-id";

insert_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName, $pipeline_id )

?>

