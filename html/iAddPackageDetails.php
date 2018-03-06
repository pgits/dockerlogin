<?php


include('/var/www/etc/insert-package-details.php');

if(isset($_POST["packageTransactionId"]))
        $packageTransactionId     = escapeshellcmd($_POST['packageTransactionId']);
if(isset($_POST["debFileName"]))
        $debFileName              = escapeshellcmd($_POST['debFileName']);
if(isset($_POST["debArtifactoryName"]))
        $debArtifactoryName       = escapeshellcmd($_POST['debArtifactoryName']);
if(isset($_POST["preMergeFlag"]))
        $preMergeFlag       = escapeshellcmd($_POST['preMergeFlag']);

insert_package_details($packageTransactionId, $debFileName, $debArtifactoryName, $preMergeFlag)

?>

