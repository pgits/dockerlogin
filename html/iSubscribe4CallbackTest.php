<?php

include('/var/www/etc/subscribe4callback.php');
$data = json_decode(file_get_contents('php://input'), true);
print_r($data);
echo $data["operacion"];

	$branch                = escapeshellcmd($data["branch"]);
	$packageName           = escapeshellcmd($data["repo"]);
	$packageRevision       = escapeshellcmd($data["package_revision"]);
	$pipeline_id	       = escapeshellcmd($data["pipeline_id"]);
	$jessie      	       = escapeshellcmd($data["jessie"]);
	$stretch      	       = escapeshellcmd($data["stretch"]);
	$which_service         = escapeshellcmd($data["which_service"]);

$branch="testing";
$packageName="l2-services";
$packageName="infra-chm";
$branch="integration";
$packageRevision="integration.28";
$pipeline_id="5aa2b9b236ede36cae22a443";
$jessie=371;
$stretch=371;
$which_service=1;

subsribe_4_calback($branch, $packageName, $packageRevision, $pipeline_id, $jessie, $stretch, $which_service )

?>

