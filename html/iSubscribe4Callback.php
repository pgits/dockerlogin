<?php

include('/var/www/etc/subscribe4callback.php');

if(isset($_POST["branch"]))
	$branch                = escapeshellcmd($_POST['branch']);
if(isset($_POST["repo"]))
	$packageName           = escapeshellcmd($_POST['repo']);
if(isset($_POST["pipeline_id"]))
	$pipeline_id	       = escapeshellcmd($_POST['pipeline_id']);
if(isset($_POST["jessie"]))
	$jessie      = escapeshellcmd($_POST['jessie']);
if(isset($_POST["stretch"]))
	$stretch      = escapeshellcmd($_POST['stretch']);
if(isset($_POST["which_service"]))
	$which_service      = escapeshellcmd($_POST['which_service']);

subsribe_4_calback($branch, $packageName, $pipeline_id, $jessie, $stretch, $which_service )

?>

