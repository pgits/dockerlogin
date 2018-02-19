<?php


include('/var/www/etc/insert-package-details.php');

if(isset($_POST["packageTransactionId"]))
        $packageTransactionId     = escapeshellcmd($_POST['packageTransactionId']);
if(isset($_POST["debFileName"]))
        $debFileName              = escapeshellcmd($_POST['debFileName']);
if(isset($_POST["debArtifactoryName"]))
        $debArtifactoryName       = escapeshellcmd($_POST['debArtifactoryName']);

$packageTransactionId=247;
$debFileName="dn-platform-utils-lib_3.0.1.259+git20180125.ddbb426_amd64.deb";
$debArtifactoryName="http://artifactory.force10networks.com/ar/pool/dn-platform-utils-lib/dn-platform-utils-lib_3.0.1.259+git20180125.ddbb426_amd64.deb";

insert_package_details($packageTransactionId, $debFileName, $debArtifactoryName)

?>

