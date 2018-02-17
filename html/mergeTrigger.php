<?php

include('/var/etc/mergeTrigger.php');

$branch                = escapeshellcmd($_POST['branch']);
$packageName           = escapeshellcmd($_POST['packageName']);
$jsonContainer         = escapeshellcmd($_POST['jsonContainer']);
$mergeUser             = escapeshellcmd($_POST['mergeUser']);
$packageRevision       = escapeshellcmd($_POST['packageRevision']);
mergeTrigger($branch, $packageName, $packageRevision, $jsonContainer, $mergeUser )

?>

