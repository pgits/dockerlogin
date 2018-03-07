<?php 

function mergeTrigger($branch, $packageName, $packageRevision, $jsonContainer, $mergeUser, $pullRequestId ){

$DEBUG=TRUE;
$mysqlservername = "localhost";
$username = "root";
$password = "";
$ONLY_TESTING=false;
// Create connection
#$this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->database);
$conn = new mysqli($mysqlservername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    header("Status: 404");
    return false;
} else {
	//header("Status: 200");
	//header('Content-type: application/json');
	if($DEBUG == TRUE)
		echo "Connected successfully\n";
	$database   = "engOps";
	$table      = "mergeTriggerPassedSmokeTest";
	if($DEBUG == TRUE)
		echo "branch = $branch\n";
	if($DEBUG == TRUE)
		echo "packageName = $packageName\n";
	if($DEBUG == TRUE)
		echo "packageRevision = $packageRevision\n";
	if($DEBUG == TRUE)
		echo "mergeUser = $mergeUser\n";
	if($DEBUG == TRUE)
		echo "pullRequestId = $pullRequestId\n";

	$sqlString = "SELECT transactionId, mergeStatus, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, buildRetries, smokeTestTriesBase, serverName, imageBaseName, imageBaseFullPathAndName, pullRequestId FROM $database.mergeTriggerPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND packageRevision = \"$packageRevision\" AND mergeStatus > 0 ;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "." . $table . "\n";
	if($DEBUG == TRUE)
		echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";

	if($select_result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		// Return the number of rows in result set
  		$rowcount=mysqli_num_rows($select_result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($rowcount == 0){
			//this is a trigger to start
			$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `branch`, `mergeStatus`, `pullRequestId`, `expires`) VALUES (\"$packageName\", $packageRevision, \"$branch\", 1, $pullRequestId, DATE_ADD(CURRENT_DATE, INTERVAL 2 week) );";
			if($DEBUG == TRUE)
				echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
			if($DEBUG == TRUE)
				echo "database = " . $database . "\n";
			if($DEBUG == TRUE)
				echo "table = " . $table . "\n";
			if($DEBUG == TRUE)
				echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
			if($ONLY_TESTING == TRUE && strcasecmp( $branch, 'testing' ) != 0 )
				print("['MergeCheckPassed':'succeeded']");
				error_log("sqlString = [" . $sqlString . "]\n" , 3, "../html/merge-trigger.log");
		}
			$insertRes = 0;
			$insertRes = mysqli_query($conn, $sqlString);
			if($insertRes){
				$transId = mysqli_insert_id($conn);
		 		$data = ['transactionId' => $transId ];
				$data1= "['MergeTriggered': 'trying..']";
				print json_encode($data1);
				echo json_encode($data);
				if($DEBUG == TRUE) {
					echo "transaction id now = [" . $transId . "]\n";
					echo "call system call back to continuum with jsonContents and transactionId";
				}

				$ch = curl_init('http://veronecontinuum-eqx-01.force10networks.com:8080/api/promote_revision');
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "package=$packageName&branch=$branch&revision=$packageRevision&phase=Trigger Merge Package Build");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token 59553bf736ede316388f92ad'
    				)
				);                                                                                                                   
				$curl_result = curl_exec($ch);
				echo "response from curl = " . $curl_result . "\n";
				curl_close($ch);
				var_dump($curl_result);
				mysqli_close($conn);
				return true;
			}
		}
		//rowcount > 0
		$MERGE_BUILT_AND_SMOKE_TESTED=25;
		while($r = mysqli_fetch_assoc($select_result)) {
				$rows[] = $r;
				//if($r.mergeStatus == 4)

			    $myMergeStatus = $r["mergeStatus"];
				if($DEBUG)
					print("mergeStatus equals  $myMergeStatus");
				if($myMergeStatus >= $MERGE_BUILT_AND_SMOKE_TESTED){
					print("['MergeCheckPassed':'succeeded']");
				}
				else {
					print("['MergeCheck': $myMergeStatus ]");
				}
  				mysqli_free_result($select_result);
				mysqli_close($conn);
				return true;
		}
  	}//end of else
}
?>
