<?php


include('/var/www/etc/insert-package-details.php');

if(isset($packageTransactionId))
        $packageTransactionId     = escapeshellcmd($_POST['packageTransactionId']);
if(isset($debFileName))
        $debFileName              = escapeshellcmd($_POST['debFileName']);
if(isset($debArtifactoryName))
        $debArtifactoryName       = escapeshellcmd($_POST['debArtifactoryName']);

insert_package_details($packageTransactionId, $debFileName, $debArtifactoryName)

?>

