<?php

include('/var/www/etc/update-details-premerge.php');

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

$branch="feature/petes-branch-b";
$packageName="petes-repo";
$packageRevision="4.0.0.90";
$packageBuiltPath="ngos/workspace/debian/jessie/x86_64/build/pas/package";
$lastGoodBuildUsed="6536";
$OS_RELEASE="jessie";
$serverName="build-eqx-02";
$pipeline_id="5a94b24b36ede31c43915462";

$packageTransactionId="7";
$imageBaseName="PKGS_OS10-Base-integrationB.X." . $lastGoodBuildUsed . "-installer-x86_64.bin";
$imageEnterpriseName="PKGS_OS10-Enterprise-integrationE.X." . $lastGoodBuildUsed . "-installer-x86_64.bin";
$imageBaseFullPathAndName="/tftpboot/NGOS/merge_testing/$packageTransactionId/PKGS_OS10-Base-integrationB.X." . $lastGoodBuildUsed . "-installer-x86_64.bin";
$imageEnterpriseFullPathAndName="/tftpboot/NGOS/merge_testing/$packageTransactionId/PKGS_OS10-Enterprise-integrationE.X." . $lastGoodBuildUsed . "-installer-x86_64.bin";
#$imageEnterpriseName="unknown";
update_details($packageTransactionId, $imageBaseName, $imageBaseFullPathAndName, $imageEnterpriseName, $imageEnterpriseFullPathAndName )

?>

