<?php


include('/var/www/etc/complete-smoketest.php');

if(isset($_POST["transactionId"]))
        $transactionId          = escapeshellcmd($_POST['transactionId']);
if(isset($_POST["operation"]))
        $operation              = escapeshellcmd($_POST['operation']);
if(isset($_POST["BaseOrEnterprise"]))
        $BaseOrEnterprise      = escapeshellcmd($_POST['BaseOrEnterprise']);
if(isset($_POST["PreMergeFlag"]))
        $PreMergeFlag      = escapeshellcmd($_POST['PreMergeFlag']);

complete_smoketest($transactionId, $operation, $BaseOrEnterprise, $PreMergeFlag)

?>

