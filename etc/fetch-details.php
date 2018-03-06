<?php 
//do I need the branch here????
function fetch_details($transactionId, $branch, $operation, $os_release ){
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
	$tableMerge     = "mergeTriggerPassedSmokeTest";
	$tableDetails   = "debFilesPerPackage";

	if($transactionId <= 0)
	    $sqlString = "SELECT $tableMerge.transactionId, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, packagesIndex, debFileName, debArtifactoryName, buildRetries, smokeTestTriesBase, smokeTestTriesEnterprise, serverName, imageBaseName, imageEnterpriseName, imageBaseFullPathAndName, imageEnterpriseFullPathAndName FROM $database.$tableMerge JOIN $database.debFilesPerPackage ON $tableMerge.transactionId = debFilesPerPackage.packagesIndex WHERE $tableMerge.operation = $operation AND $tableMerge.OS_RELEASE = '$os_release'  AND $tableMerge.branch = '$branch' AND PreMerge = 0 ORDER BY $tableMerge.transactionId DESC;";
	else 
	   $sqlString = "SELECT $tableMerge.transactionId, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, packagesIndex, debFileName, debArtifactoryName, buildRetries, smokeTestTriesBase, smokeTestTriesEnterprise, serverName, imageBaseName, imageEnterpriseName, imageBaseFullPathAndName, imageEnterpriseFullPathAndName FROM $database.$tableMerge JOIN $database.debFilesPerPackage ON $tableMerge.transactionId = debFilesPerPackage.packagesIndex WHERE $tableMerge.transactionId = $transactionId AND $tableMerge.operation = '$operation' AND $tableMerge.OS_RELEASE = '$os_release' AND $tableMerge.branch = '$branch' AND PreMerge = 0 ORDER BY $tableMerge.transactionId DESC;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "transactionId = " . $transactionId . "\n";
	if($DEBUG == TRUE)
		echo "operation = " . $operation . "\n";
	if($DEBUG == TRUE)
		echo "OS_RELEASE = " . $os_release . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";

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
