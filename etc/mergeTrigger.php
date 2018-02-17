<?php 


function mergeTrigger($branch, $packageName, $packageRevision, $jsonContainer, $mergeUser ){

$DEBUG=FALSE;
$mysqlservername = "localhost";
$username = "root";
$password = "";
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
	$database   = "engOps";
	$table      = "mergeTriggerPassedSmokeTest";
	#$sqlString = "SELECT * FROM $database.mergeTriggerPassedSmokeTest ";


	$sqlString = "SELECT transactionId, mergeStatus, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, buildRetries, smokeTestTriesBase, serverName, imageBaseName, imageBaseFullPathAndName FROM $database.mergeTriggerPassedSmokeTest WHERE branch = '$branch' AND packageName = '$packageName' AND packageRevision = '$packageRevision' AND mergeStatus > 0 ;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";

	#$result = $conn->query($sqlString);
	if($result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		// Return the number of rows in result set
  		$rowcount=mysqli_num_rows($result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($rowcount == 0){
			//this is a trigger to start
			$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `branch`, `mergeStatus`) VALUES (\"$packageName\", \"$packageRevision\", \"$branch\", 1);";
			if($DEBUG == TRUE)
				echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
			if($DEBUG == TRUE)
				echo "database = " . $database . "\n";
			if($DEBUG == TRUE)
				echo "table = " . $table . "\n";
			if($DEBUG == TRUE)
				echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
			$res = 0;
        	$res = mysqli_query($conn, $sqlString);
        	if($res){
            	$transId = mysqli_insert_id($conn);
		 		$data = ['transactionId' => $transId ];
		 		header("Status: 200");
		 		header('Content-type: application/json');
		 		echo json_encode($data);
                if($DEBUG == TRUE) {
                	echo "transaction id now = [" . $transId . "]\n";
		     		echo "call system call back to continuum with jsonContents and transactionId";
		}
			mysqli_close($conn);
			return true;
		}
		}
		//rowcount > 0
		while($r = mysqli_fetch_assoc($result)) {
				$rows[] = $r;
				//if($r.mergeStatus == 4)
				if($DEBUG != TRUE)
					header("Status: 200");
				print json_encode($rows);
  				mysqli_free_result($result);
				mysqli_close($conn);
				return true;
		}
  	}
	}//end else
}
?>
