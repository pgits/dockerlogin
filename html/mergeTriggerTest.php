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

$my_branch = "ar-rel_10.4.0-X2";
$my_branch = "testing";
$packageName = "base-model";
$packageName = "mgmt-cm";
$packageName = "l2-services";
#$packageRevision = "49";
#$packageRevision = "68";
#$packageRevision = "108";

$my_branch = "ar-rel_10.4.0-R3";
$packageName = "mgmt-monitoring";
$packageName = "mgmt-clish";
$my_branch = "integration";
#$packageRevision = "105";

$jsonContainer = "nothing";
$mergeUser = "admin";
$pullRequestId = 11;
$packageRevision = "0";

mergeTrigger($my_branch, $packageName, $packageRevision, $jsonContainer, $mergeUser, $pullRequestId )

?>

