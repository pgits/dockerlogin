<?php 

function fetch_last_package_that_passed_smoketest($DEBUG, $conn, $database, $branch, $packageName){
	$myPackageRevision = "1";
$PASSED_SMOKE_TEST=12;

	$sqlString = "SELECT transactionid, packageRevision FROM $database.PackagesPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND operation = $PASSED_SMOKE_TEST ORDER BY transactionId DESC LIMIT 1;";
	if($DEBUG == TRUE)
		echo "\nfetch_last_package()= this is what we are sending to mySql:  [" . $sqlString . "]\n";
	if($select_revision_result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		$rowcount=mysqli_num_rows($select_revision_result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		while($r = mysqli_fetch_assoc($select_revision_result)) {
                	$rows[] = $r;
			print_r($rows);
			$myPackageRevision = $r["packageRevision"];
			$packageRevisionJustNumber = substr($myPackageRevision, strrpos($myPackageRevision, ".") + 1);
			print("packageRevision found = $packageRevisionJustNumber\n");
			return $packageRevisionJustNumber;
		}
	}
	return $myPackageRevision;
}

function fetch_last_package($DEBUG, $conn, $database, $branch, $packageName){
	$myPackageRevision = "1";

	$sqlString = "SELECT transactionid, packageRevision FROM $database.PackagesPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" ORDER BY transactionId DESC LIMIT 1;";
	if($DEBUG == TRUE)
		echo "\nfetch_last_package()= this is what we are sending to mySql:  [" . $sqlString . "]\n";
	if($select_revision_result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		$rowcount=mysqli_num_rows($select_revision_result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		while($r = mysqli_fetch_assoc($select_revision_result)) {
                	$rows[] = $r;
			print_r($rows);
			$myPackageRevision = $r["packageRevision"];
			$packageRevisionJustNumber = substr($myPackageRevision, strrpos($myPackageRevision, ".") + 1);
			print("packageRevision found = $packageRevisionJustNumber\n");
			return $packageRevisionJustNumber;
		}
	}
	return $myPackageRevision;
}


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
	if($packageRevision === "0")
		$sqlString = "SELECT transactionId, mergeStatus, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, buildRetries, smokeTestTriesBase, serverName, imageBaseName, imageBaseFullPathAndName, pullRequestId FROM $database.mergeTriggerPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND mergeStatus > 0 ORDER BY transactionId DESC;";
	else 
		$sqlString = "SELECT transactionId, mergeStatus, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, buildRetries, smokeTestTriesBase, serverName, imageBaseName, imageBaseFullPathAndName, pullRequestId FROM $database.mergeTriggerPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND mergeStatus > 0 AND packageRevision = \"$packageRevision\" ORDER BY transactionId DESC ;";

	if($DEBUG == TRUE)
		echo "database = " . $database . "." . $table . "\n";
	if($DEBUG == TRUE)
		echo "\nSELECT(1)this is what we are sending to mySql:  [" . $sqlString . "]\n";

	if($select_result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		// Return the number of rows in result set
  		$rowcount=mysqli_num_rows($select_result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($rowcount == 0){

		if($packageRevision == 0)
		//need to locate the last packageRevision
			$packageRevision = fetch_last_package_that_passed_smoketest($DEBUG, $conn, $database, $branch, $packageName);

		//this is a trigger to start
		$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `branch`, `mergeStatus`, `pullRequestId`, `expires`) VALUES (\"$packageName\", \"$packageRevision\", \"$branch\", 1, $pullRequestId, DATE_ADD(CURRENT_DATE, INTERVAL 2 week) );";
		if($DEBUG == TRUE)
			echo "database = " . $database . "\n";
		if($DEBUG == TRUE)
			echo "table = " . $table . "\n";
		if($DEBUG == TRUE)
			echo "INSERT(A) this is what we are sending to mySql:  [" . $sqlString . "]\n";
		if($ONLY_TESTING == TRUE && strcasecmp( $branch, 'testing' ) != 0 )
			print("['MergeCheckPassed':'succeeded']");
		error_log("sqlString = [" . $sqlString . "]\n" , 3, "../html/merge-trigger.log");
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
				echo "call system call back to continuum with jsonContents and transactionId\n";
			}
			//promote_revision($ch, $packageName, $branch, $packageRevision);
			setapi($DEBUG, $conn, $database, $packageName, $branch, $packageRevision, $transId);
			mysqli_close($conn);
			return true;
		}
		}//end of row_count = 0
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
		}
	}
	mysqli_close($conn);
	return true;
  }//end of else
}

function promote_revision($ch, $packageName, $branch, $packageRevision) {
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
}

function fetchSubscription($DEBUG, $conn, $database, $packageName, $branch, &$pipeline_id, &$packageRevision){
	$sqlString = "SELECT * FROM $database.callBacks WHERE branch = \"$branch\" AND packageName = \"$packageName\" ORDER BY transId DESC LIMIT 1;";
	//return the rows with the continuumId and the revision	
	if($DEBUG == TRUE)
		echo "\nfetchSubscription()= this is what we are sending to mySql:  [" . $sqlString . "]\n";
	
	$resSelect = mysqli_query($conn, $sqlString);
	if($resSelect){
		$rows = array();
  		$rowcount=mysqli_num_rows($resSelect);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($resSelect)
        	{
                	while($r = mysqli_fetch_assoc($resSelect))
                	{
				$rows[] = $r;
				print_r($r);
				$pipeline_id = $r['pipeline_id'];
				$packageRevision = $r['packageRevision'];
				echo "packageRevision = $packageRevision\n";
				echo "pipeline_id = $pipeline_id\n";
			}
		}
	}
}

//calls fetchSubscription to pull down both packageRevision and the pipeline_id
// and then uses that to continue the pipeline to nudge continuum to the next phase

function setapi($DEBUG, $conn, $database, $packageName, $branch, $packageRevision, $transactionId) {
	if($DEBUG == TRUE){
		echo "packageName = [$packageName]\n";
		echo "branch = [$branch]\n";
		echo "packageRevision = [$packageRevision]\n";
		echo "transactionId = [$transactionId]\n";
	}
	fetchSubscription($DEBUG, $conn, $database, $packageName, $branch, $pipeline_id, $packageRevision);	
	$packageRevisionJustNumber = substr($packageRevision, strrpos($packageRevision, ".") + 1);

/*
{"pi": "5aa2bd8f36ede36cae22a45d", 
 "key": "merge_trigger_returns", "value": {
   "list": {
     "version": "28", 
     "transactionIdStretch": "40", 
     "package": "infra-chm", 
     "branch": "integration", 
     "transactionId": "40" 
     }
 }
}
*/
	$someJSON="{\"pi\": \"$pipeline_id\", \n" .
			    "\"key\": \"merge_trigger_returns\", \n" .
				"\t\"value\": {\n" .
					"\t\t\"list\": {\n" .
				   	"\t\t\t\"version\": \"$packageRevisionJustNumber\", \n" .
				   	"\t\t\t\"transactionIdStretch\": \"$transactionId\", \n" .
				   	"\t\t\t\"package\": \"$packageName\", \n" .
				   	"\t\t\t\"branch\": \"$branch\", \n" .
	//				"\t\t\t\"tftpboot_enterprise_path\": \"$imageEnterpriseFullPathAndName\", \n" .
	//				"\t\t\t\"tftpboot_base_path\": \"$imageBaseFullPathAndName\", \n" .
				   	"\t\t\t\"transactionId\": \"$transactionId\" \n" .
				        "\t\t\t}\n" .
		  		"\t}\n".
		"}";
	print_r($someJSON);
		// Convert JSON string to Array
  	$someArray = json_decode($someJSON, true);
  	print_r($someArray);        // Dump all data of the Array

	$postJsonData = json_encode($someArray);
	echo "postJsonData now = " . $postJsonData . "\n";

	$ch = curl_init('http://veronecontinuum-eqx-01.force10networks.com:8080/api/set_pi_data');                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postJsonData);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Authorization: Token 59553bf736ede316388f92ad',
		'Content-Type: application/json',
    		'Content-Length: ' . strlen($postJsonData)
		)
	);
	$result = curl_exec($ch);
	var_dump($result);
}

?>
