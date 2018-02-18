<?php


include('/var/www/etc/update-smoketest.php');

if(isset("transactionId"))
        $transactionId          = escapeshellcmd($_POST['transactionId']);
if(isset("operation"))
        $operation              = escapeshellcmd($_POST['operation']);
if(isset("BaseOrEnterprise"))
        $BaseOrEnterprise      = escapeshellcmd($_POST['BaseOrEnterprise']);

update_smoketest($transactionId, $operation, $BaseOrEnterprise)

?>

