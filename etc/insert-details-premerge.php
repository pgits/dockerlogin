<?php 
function insert_details($branch, $packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName, $pipeline_id){
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
} 
	if($DEBUG == TRUE)
		echo "Connected successfully";
	$database 	= "engOps";
	$table 		= "mergeTriggerPassedSmokeTest";

	$sqlString = "SELECT transactionId FROM $database.$table WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND mergeStatus = 1 LIMIT 1;";
    if($DEBUG == TRUE) {
    	echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";
		echo "\nmust find the match to update and store the transaction after doing the update, cannot get the same row afterwards...\n";
	}
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
    if($result = mysqli_query($conn, $sqlString)){
    	while($r = mysqli_fetch_assoc($result)) {
        	$rows[] = $r;
		$packageTransactionId = $r["transactionId"];
		if($DEBUG == TRUE)
		    echo "on first select will match transaction id = " . $packageTransactionId . "\n";

	$sqlString = "UPDATE ". $database . "." . $table . " SET `packageBuiltPath` = '$packageBuiltPath', `lastGoodBuildUsed` = '$lastGoodBuildUsed', expires = DATE_ADD(CURRENT_DATE, INTERVAL 2 week), OS_RELEASE = '$OS_RELEASE', serverName = '$serverName', operation = 1, mergeStatus = 2 WHERE branch = '$branch' AND packageName = '$packageName' AND mergeStatus = 1 AND transactionId = $packageTransactionId ;";
//AND pipeline_id = \"$pipeline_id\";";

	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "table = " . $table . "\n";
	if($DEBUG == TRUE)
		echo "branch = " . $branch . "\n";
	if($DEBUG == TRUE)
		echo "packageName = " . $packageName . "\n";
	if($DEBUG == TRUE)
		echo "pacakgeBuiltPath = " . $packageBuiltPath . "\n";
	if($DEBUG == TRUE)
		echo "packageRevision = " . $packageRevision . "\n";
	if($DEBUG == TRUE)
		echo "lastGoodBuildUsed = " . $lastGoodBuildUsed . "\n";
	if($DEBUG == TRUE)
		echo "OS_RELEASE = " . $OS_RELEASE . "\n";
	if($DEBUG == TRUE)
		echo "serverName = " . $serverName . "\n";
	if($DEBUG == TRUE)
		echo "pipeline_id = " . $pipeline_id . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
	$res = 0;

        $res = mysqli_query($conn, $sqlString);
        if($res){
           	$transId = mysqli_insert_id($conn);
		 	$data = ['packageTransactionId' => $packageTransactionId ];
		 	header("Status: 200");
		 	header('Content-type: application/json');
		 	echo json_encode($data);
			return true;
		}
		}//end while
	}//end result query 
//we get here if there isn't one around
	if($DEBUG == TRUE)
		echo "didn't locate transaction with matching branch and packageName with mergeStatus = 1";
    $sqlString = "INSERT INTO " . $database . "." . $table . "(`branch`, `packageName`, `packageBuiltPath`, `packageRevision`, `lastGoodBuildUsed`, `OS_RELEASE`, `serverName`, `operation`, `expires`, `pipeline_id`, `mergeStatus`, `jsonContainer`)" . " VALUES (\"$branch\", \"$packageName\", \"$packageBuiltPath\", \"$packageRevision\", \"$lastGoodBuildUsed\", \"$OS_RELEASE\", \"$serverName\", 1, DATE_ADD(CURRENT_DATE, INTERVAL 2 week), \"$pipeline_id\", 2, '{\"failed to locate PackagePassedSmokeTest\": \"will be missing a PullRequestId!!!\"}');";
    $res = mysqli_query($conn, $sqlString);
    if($res){
    	$transId = mysqli_insert_id($conn);
		$data = ['packageTransactionId' => $transId ];
		if($DEBUG == TRUE){
			header("Status: 200");
		}
		header('Content-type: application/json');
		echo json_encode($data);
		return true;
	}
	
}//get_
?>
