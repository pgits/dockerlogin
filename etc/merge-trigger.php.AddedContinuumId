<?php 
#include curl

function mergeTrigger($branch, $packageName, $jsonContainer, $mergeUser, $pullRequestId ){

$DEBUG=TRUE;
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
	//header("Status: 200");
	//header('Content-type: application/json');
	if($DEBUG == TRUE)
		echo "Connected successfully\n";
	$database   = "engOps";
	$table      = "mergeTriggerPassedSmokeTest";
	#$sqlString = "SELECT * FROM $database.mergeTriggerPassedSmokeTest ";
	if($DEBUG == TRUE)
		echo "branch = $branch\n";
	if($DEBUG == TRUE)
		echo "packageName = $packageName\n";
	if($DEBUG == TRUE)
		echo "mergeUser = $mergeUser\n";
	if($DEBUG == TRUE)
		echo "pullRequestId = $pullRequestId\n";

	$sqlString = "SELECT transactionId, mergeStatus, packageName, packageBuiltPath, packageRevision, lastGoodBuildUsed, OS_RELEASE, operation, buildRetries, smokeTestTriesBase, serverName, imageBaseName, imageBaseFullPathAndName, pullRequestId FROM $database.mergeTriggerPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND mergeStatus > 0 ORDER BY transactionId DESC;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";

	#$result = $conn->query($sqlString);
	if($result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		// Return the number of rows in result set
  		$rowcount=mysqli_num_rows($result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($rowcount == 0){
			#pull the pipeline_id, transactionId, and packageRevision from the package table only on insert
			$transactionId = 0;
			$continuumId = "";
			$packageRevision = "";
			$sqlString = "SELECT transactionId, pipeline_id, packageRevision  FROM $database.PackagesPassedSmokeTest WHERE branch = \"$branch\" AND packageName = \"$packageName\" AND operation = 12 ORDER BY transactionId DESC LIMIT 1;";
        	if($DEBUG == TRUE)
            	echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";
			if($result = mysqli_query($conn, $sqlString)){ 
				while($r = mysqli_fetch_assoc($result)) {
					$rows[] = $r;
			    	$pipeline_id = $r["pipeline_id"];
			    	$packageTransactionId = $r["transactionId"];
			    	$packageRevision = $r["packageRevision"];
					if($DEBUG == TRUE){
						echo "\n found pipeline_id = $pipeline_id";
						echo "\n found packageTransactionId = $packageTransactionId";
						echo "\n found packageRevision = $packageRevision\n";
					}
				}
			}
			//this is a trigger to start
			$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `branch`, `mergeStatus`, `pullRequestId`, `pipeline_id`, `premergeTransactionId` ) VALUES (\"$packageName\", \"$packageRevision\", \"$branch\", 1, $pullRequestId, \"$pipeline_id\", $packageTransactionId );";
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
				$data1= "['MergeTriggered': 'trying..']";
				print json_encode($data1) . "\n";
				echo json_encode($data) . "\n";

        		//$commandString = "/usr/bin/python /var/www/CopyAndPaste/fromTempus2V1/fromTempus2V1.py";
						//echo "\n found pipeline_id = $pipeline_id";
						//echo "\n found packageTransactionId = $packageTransactionId";
						//echo "\n found packageRevision = $packageRevision\n";
			//$commandString = "curl -X POST -H \"Content-Type: application/json\" -H \"Authorization: Token 59553bf736ede316388f92ad\" -d \"{\"pi\": \"$pipeline_id\", \"key\": \"merge_trigger_returns\", \"value\": {\"list\": {\"build-id\": \"8598b442-1cac-11e8-ae34-00505694438f\", \"build-url\": \"https://eflow.force10networks.com/commander/jobDetails.php?jobId=8598b442-1cac-11e8-ae34-00505694438f\", \"outcome\": \"success\", \"version\": \"$packageRevision\", \"transactionIdJessie\": \"$packageTransactionId\", \"transactionIdStretch\": \"$packageTransactionId\", \"packageRevision\":\"$packageRevision\", \"tftpboot_path\": \"/tftpboot/NGOS/merge_testing/373/PKGS_OS10-Enterprise-testingE.X.6813-installer-x86_64.bin\", \"transactionId\": \"$transId\"}}}\" http://veronecontinuum-eqx-01.force10networks.com:8080/api/set_pi_data ";
			$url = 'http://veronecontinuum-eqx-01.force10networks.com:8080/api/set_pi_data';

$someJSON="{\"pi\": \"$pipeline_id\", \"key\": \"merge_trigger_returns\", \"value\": {\"list\": {\"build-id\": \"8598b442-1cac-11e8-ae34-00505694438f\", \"build-url\": \"https://eflow.force10networks.com/commander/jobDetails.php?jobId=8598b442-1cac-11e8-ae34-00505694438f\", \"outcome\": \"success\", \"version\": \"$packageRevision\", \"transactionIdJessie\": \"$packageTransactionId\", \"transactionIdStretch\": \"$packageTransactionId\", \"tftpboot_path\": \"/tftpboot/NGOS/merge_testing/373/PKGS_OS10-Enterprise-testingE.X.6813-installer-x86_64.bin\", \"transactionId\": \"$transId\"}}}";
// Convert JSON string to Array
  			$someArray = json_decode($someJSON, true);
  			print_r($someArray);        // Dump all data of the Array
  			//echo "pipeline_id = " . $someArray["pi"] . "\n"; // Access Array data

			$postJsonData = json_encode($someArray);
			echo "postJsonData now = " . $postJsonData . "\n";

			$ch = curl_init('http://veronecontinuum-eqx-01.force10networks.com:8080/api/set_pi_data');                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postJsonData);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
				'Authorization: Token 59553bf736ede316388f92ad',
				'Content-Type: application/json',
    			'Content-Length: ' . strlen($postJsonData))                                                                       
			);                                                                                                                   
			$result = curl_exec($ch);
			var_dump($result);

/*
			$options = array(
				'http' => array(
					'header'  => "Content-Type: application/json\r\n" 
						. "Authorization: Token 59553bf736ede316388f92ad\r\n"
						. 'Content-Length: ' . strlen($postJsonData) . "\r\n",
					'method'  => 'POST',
					'content' => $postJsonData
    			)
			);
			$options = array(
				'http' => array(
					'header'  => "Content-Type: application/json\r\n" 
						. "Authorization: Token 59553bf736ede316388f92ad\r\n"
						. 'Content-Length: ' . strlen($postJsonData),
					'method'  => 'POST',
					'content' => $postJsonData
    			)
			);

			$context  = stream_context_create($options);
			echo "context we are sending to file_get_contents = ";
			print_r($context);
			$result = file_get_contents($url, false, $context);
			if ($result === FALSE) { 
				// Handle error // 
				echo "http request failed with $result\n";
			}
			var_dump($http_response_header);
			var_dump($result);
*/
        	$res = 0;
        	#$res = system($commandString, $res);
        	#passthru($commandString, $res);
        	#if($DEBUG == TRUE)
			#	echo "call to continuun: here is the command we are executing :  [" . $commandString . "]\n";

            if($DEBUG == TRUE) {
            	echo "transaction id now = [" . $transId . "]\n";
			}
			mysqli_close($conn);
			return true;
		}
		}
		//rowcount > 0
		$MERGE_BUILT_AND_SMOKE_TESTED=25;
		while($r = mysqli_fetch_assoc($result)) {
				$rows[] = $r;
			    $myMergeStatus = $r["mergeStatus"];
				if($DEBUG)
					print("mergeStatus equals  $myMergeStatus");
				if($myMergeStatus >= $MERGE_BUILT_AND_SMOKE_TESTED)
					print("['MergeCheckPassed':'succeeded']");
				else
					print("['MergeCheck': $myMergeStatus ]");
			/*	
				if($DEBUG != TRUE){
					print json_encode($data);
				}else {
					print json_encode($rows);
					print json_encode($data);
				}
			*/
  				mysqli_free_result($result);
				mysqli_close($conn);
				return true;
		}
  	}
	}//end else
}
?>
