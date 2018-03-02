#v1 2 tempus
<?php 
function setup_copy(){
	$mysqlservername = "localhost";
	$username = "root";
	$password = "";
	$DEBUG=TRUE;
	
	$commandString = "/usr/bin/python /var/www/etc/CopyAndPaste/fromV1toTempusFeaturesPlusEpics/copyFromV1.py";
	if($DEBUG == TRUE)
		echo "this is the command we are executing :  [" . $commandString . "]\n";
	$res = 0;
	passthru($commandString, $res);

        if($res){
		return true;
	}
	return false;
}//setup_copy()
?>
