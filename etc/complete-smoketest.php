<?php 
function complete_smoketest($transactionId, $operation, $baseOrEnterprise, $PreMergeFlag){
$mysqlservername = "localhost";
$username = "root";
$password = "";
$DEBUG=TRUE;
// Create connection
#$this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->database);
$conn = new mysqli($mysqlservername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    header("Status: 404");
    return false;
} else {
	if($DEBUG != TRUE)
		header("Status: 200");
	if($DEBUG == TRUE)
		echo "Connected successfully";
	$database 	= "engOps";
	if($PreMergeFlag == TRUE)
		$table		= "PackagesPassedSmokeTest";
	else
		$table		= "mergeTriggerPassedSmokeTest";

	if($baseOrEnterprise == "base")
		$sqlString = "UPDATE $database.$table set operation = $operation, smokeTestStartedBase = NOW() where transactionId = $transactionId";
	else
		$sqlString = "UPDATE $database.$table set operation = $operation, smokeTestStartedEnterprise = NOW() where transactionId = $transactionId";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "baseOrEnterprise = " . $baseOrEnterprise . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";

        $res = mysqli_query($conn, $sqlString);
        if($res){
		$data = ['AffectedRows' => mysqli_affected_rows($conn)];
		header("Status: 200");
		header('Content-type: application/json');
		echo json_encode($data);
		return true;
	}
}//end else
}//get_
?>
