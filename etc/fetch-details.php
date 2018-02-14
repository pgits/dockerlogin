<?php 
function fetch_details($transactionId, $operation, $os_release ){
$mysqlservername = "localhost";
$username = "root";
$password = "";
$DEBUG=FALSE;
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
	#$sqlString = "SELECT * FROM $database.PackagesPassedSmokeTest ";


	if($transactionId <= 0)
	    $sqlString = "SELECT PackagesPassedSmokeTest.transactionId, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, packagesIndex, debFileName, debArtifactoryName, buildRetries, smokeTestRetries, serverName, imageName, imageFullPathAndName FROM $database.PackagesPassedSmokeTest JOIN $database.debFilesPerPackage ON PackagesPassedSmokeTest.transactionId = debFilesPerPackage.packagesIndex WHERE PackagesPassedSmokeTest.operation = $operation AND PackagesPassedSmokeTest.OS_RELEASE = '$os_release' ORDER BY PackagesPassedSmokeTest.transactionId DESC;";
	else 
	   $sqlString = "SELECT PackagesPassedSmokeTest.transactionId, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, packagesIndex, debFileName, debArtifactoryName, buildRetries, smokeTestRetries, serverName, imageName, imageFullPathAndName FROM $database.PackagesPassedSmokeTest JOIN $database.debFilesPerPackage ON PackagesPassedSmokeTest.transactionId = debFilesPerPackage.packagesIndex WHERE PackagesPassedSmokeTest.transactionId = $transactionId AND PackagesPassedSmokeTest.operation = '$operation' AND PackagesPassedSmokeTest.OS_RELEASE = $os_release ORDER BY PackagesPassedSmokeTest.transactionId DESC;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "transactionId = " . $transactionId . "\n";
	if($DEBUG == TRUE)
		echo "operation = " . $operation . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";

	#$result = $conn->query($sqlString);
        $result = mysqli_query($conn, $sqlString);
	$rows = array();
	if($result)
	{
		while($r = mysqli_fetch_assoc($result))
		{
			$rows[] = $r;
		}
		if($DEBUG != TRUE)
			header("Status: 200");
		print json_encode($rows);
		return true;
	}
}//end else
}//get_
?>
