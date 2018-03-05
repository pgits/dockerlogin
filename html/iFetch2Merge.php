<?php


include('/var/www/etc/fetch-details.php');

if(isset($_POST["transactionId"]))
        $transactionId              = escapeshellcmd($_POST['transactionId']);
else
	$transactionId = 0;
if(isset($_POST["operation"]))
        $operation              = escapeshellcmd($_POST['operation']);
if(isset($_POST["OS_RELEASE"]))
        $OS_RELEASE      = escapeshellcmd($_POST['OS_RELEASE']);

fetch_details($transactionId, $operation, $OS_RELEASE)

?>

