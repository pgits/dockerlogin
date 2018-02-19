<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
#$this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->database);
$conn = new mysqli($servername, $username, $password);
$DEBUG=TRUE;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	echo "Connected successfully";
	$database 	= "engOps";
	$table 		= "PackagesPassedSmokeTest";
	$debTable	= "debFilesPerPackage";
	if($DEBUG)
		$sqlString = "SELECT transactionId FROM " . $database . "." . $table . " WHERE expires <= DATE(now()) OR expires = NULL LIMIT 10;";
	else 
		$sqlString = "SELECT transactionId FROM " . $database . "." . $table . " WHERE expires <= DATE(now()) OR expires = NULL ;";
   	$result = mysqli_query($conn, $sqlString);
   	$rows = array();
   	if($result)
   	{
		while($r = mysqli_fetch_assoc($result)) {
			$rows[] = $r;
			$sqlRemoveString = "DELETE FROM " . $database . "." . $debTable . " WHERE packagesIndex = " . $r["transactionId"] . ";";
			if($DEBUG){
				printf("sqlRemoveString = %s\n", $sqlRemoveString);
				printf("remove debFilesPerPackage with packagesIndex = %d\n", $r["transactionId"]);
			}
			$removeResult = mysqli_query($conn, $sqlRemoveString);
			$removeResult = true;	
			if($removeResult){
				$sqlRemove = "DELETE FROM " . $database . "." . $table . " WHERE transactionId = " . $r["transactionId"] . " ;";
				if($DEBUG){
					printf("sqlRemove= %s\n", $sqlRemove);
					printf("remove debFilesPerPackage with packagesIndex = %d\n", $r["transactionId"]);
				}
				$myRemoveResult = mysqli_query($conn, $sqlRemove);
				//this is where we call the eflow command to remove the directory
				$myTransactionId = $r["transactionId"];
				$cmd = "/var/www/etc/iExpired.sh $myTransactionId >> /tmp/logTransaction.out";
  				$last_line = system($cmd,$return_value);
				printf("returnValue = %s and last_line = %s\n", $return_value, $last_line);
			}
		}
		if($DEBUG != TRUE)
			header("Status: 200");
		print json_encode($rows);
		return true;
   }

}
?>
