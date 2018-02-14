#v1 2 tempus
<?php 
function setup_copy(){
	$mysqlservername = "localhost";
	$username = "root";
	$password = "";
	$DEBUG=TRUE;
	// Create connection
	#$this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->database);
	#$conn = new mysqli($mysqlservername, $username, $password);
// Check connection
	#$database 	= "engOps";
	#$table 		= "PackagesPassedSmokeTest";
	
	$commandString = "/usr/bin/python /var/www/CopyAndPaste/fromTempus2V1/fromTempus2V1.py";
	if($DEBUG == TRUE)
		echo "this is the command we are executing :  [" . $commandString . "]\n";
	$res = 0;
	#$res = system($commandString, $res);
	passthru($commandString, $res);

        if($res){
		return true;
	}
	return false;
}//setup_copy()
?>
