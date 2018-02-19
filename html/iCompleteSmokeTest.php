<?php


include('/var/www/etc/complete-smoketest.php');

if(isset($_POST["transactionId"]))
        $transactionId          = escapeshellcmd($_POST['transactionId']);
if(isset($_POST["operation"]))
        $operation              = escapeshellcmd($_POST['operation']);
if(isset($_POST["BaseOrEnterprise"]))
        $BaseOrEnterprise      = escapeshellcmd($_POST['BaseOrEnterprise']);

complete_smoketest($transactionId, $operation, $BaseOrEnterprise)

?>

