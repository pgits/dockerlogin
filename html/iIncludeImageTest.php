<?php

include('/var/www/etc/update-details.php');

if(isset($packageTransactionId))
	$packageTransactionId           = escapeshellcmd($_POST['packageTransactionId']);
if(isset($imageBaseName))
	$imageBaseName           	= escapeshellcmd($_POST['imageBaseName']);
if(isset($imageEnterpriseName))
	$imageEnterpriseName           	= escapeshellcmd($_POST['imageEnterpriseName']);
else
	$imageEnterpriseName		= "unknown";
if(isset($imageBaseFullPathAndName))
	$imageBaseFullPathAndName       = escapeshellcmd($_POST['imageBaseFullPathAndName']);
if(isset($imageEnterpriseFullPathAndName))
	$imageEnterpriseFullPathAndName       = escapeshellcmd($_POST['imageEnterpriseFullPathAndName']);

$packageTransactionId="247";
$imageBaseName="PKGS_OS10-Base-integrationB.X.6536-installer-x86_64.bin";
$imageEnterpriseName="PKGS_OS10-Enterprise-integrationE.X.6536-installer-x86_64.bin";
$imageBaseFullPathAndName="/tftpboot/NGOS/merge_testing/$packageTransactionId/PKGS_OS10-Base-integrationB.X.6536-installer-x86_64.bin";
$imageEnterpriseFullPathAndName="/tftpboot/NGOS/merge_testing/$packageTransactionId/PKGS_OS10-Enterprise-integrationE.X.6536-installer-x86_64.bin";
#$imageEnterpriseName="unknown";
update_details($packageTransactionId, $imageBaseName, $imageBaseFullPathAndName, $imageEnterpriseName, $imageEnterpriseFullPathAndName )

?>

