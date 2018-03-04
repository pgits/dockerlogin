<?php

include('/var/www/etc/merge-trigger.php');

if (isset($_POST['my_branch']))
$my_branch                = escapeshellcmd($_POST['my_branch']);
if (isset($_POST['packageName']))
$packageName           = escapeshellcmd($_POST['packageName']);
if (isset($_POST['jsonContainer']))
$jsonContainer         = escapeshellcmd($_POST['jsonContainer']);
if (isset($_POST['mergeUser']))
$mergeUser             = escapeshellcmd($_POST['mergeUser']);
if (isset($_POST['packageRevision']))
$packageRevision       = escapeshellcmd($_POST['packageRevision']);
if (isset($_POST['pullRequestId']))
$pullRequestId       = escapeshellcmd($_POST['pullRequestId']);

$my_branch = "testing";
$packageName = "l2-services";
$packageRevision = "107";
$jsonContainer = "nada";
$mergeUser = "admin";
$pullRequestId = 9;
mergeTrigger($my_branch, $packageName, $packageRevision, $jsonContainer, $mergeUser, $pullRequestId )

?>

