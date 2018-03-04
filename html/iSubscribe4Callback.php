<?php

include('/var/www/etc/subscribe4callback.php');

$data = json_decode(file_get_contents('php://input'), true);

	$branch                = escapeshellcmd($data["branch"]);
	$packageName           = escapeshellcmd($data["repo"]);
	$pipeline_id	       = escapeshellcmd($data["pipeline_id"]);
	$jessie      	       = escapeshellcmd($data["jessie"]);
	$stretch      	       = escapeshellcmd($data["stretch"]);
	$which_service         = escapeshellcmd($data["which_service"]);
	$mail_to	       = escapeshellcmd($data["mail-to"]);

subsribe_4_calback($branch, $packageName, $pipeline_id, $jessie, $stretch, $which_service )

?>

