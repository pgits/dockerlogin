<?php


include('/var/www/etc/update-merge.php');

if(isset($_POST["transactionId"]))
        $transactionId              = escapeshellcmd($_POST['transactionId']);
else
	$transactionId = 0;
if(isset($_POST["mergeIndex"]))
        $mergeIndex      = escapeshellcmd($_POST['mergeIndex']);
if(isset($_POST["operation"]))
        $operation      = escapeshellcmd($_POST['operation']);

$transactionId = 42;
$mergeIndex = 48;
$operation = 5;//MergeImageBuilt=5, MergeImageBuildFailed = -5, 
update_merge_index($transactionId, $mergeIndex, $operation)

?>

