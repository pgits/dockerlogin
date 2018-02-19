<?php

include('/var/etc/mergeTrigger.php');

if(isset($_POST["branch"]))
$branch                = escapeshellcmd($_POST['branch']);
if(isset($_POST["packageName"]))
$packageName           = escapeshellcmd($_POST['packageName']);
if(isset($_POST["jsonContainer"]))
$jsonContainer         = escapeshellcmd($_POST['jsonContainer']);
if(isset($_POST["mergeUser"]))
$mergeUser             = escapeshellcmd($_POST['mergeUser']);
if(isset($_POST["packageRevision"]))
$packageRevision       = escapeshellcmd($_POST['packageRevision']);
mergeTrigger($branch, $packageName, $packageRevision, $jsonContainer, $mergeUser )

?>

