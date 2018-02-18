<?php


include('/var/www/etc/complete-smoketest.php');

if(isset($transactionId))
        $transactionId          = escapeshellcmd($_POST['transactionId']);
if(isset($operation))
        $operation              = escapeshellcmd($_POST['operation']);
if(isset($BaseOrEnterprise))
        $BaseOrEnterprise      = escapeshellcmd($_POST['BaseOrEnterprise']);

complete_smoketest($transactionId, $operation, $BaseOrEnterprise)

?>

