<?php 
function update_merge_index($transactionId, $mergeIndex, $operation){
$mysqlservername = "localhost";
$username = "root";
$password = "";
$DEBUG=TRUE;
$database 	= "engOps";
$table		= "mergeTriggerPassedSmokeTest";
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
		echo "Connected successfully\n";
	if($DEBUG == TRUE)
		echo "satisfies the need to call to update the database operation to built\n";
	$sqlString = "UPDATE $database.$table set mergeIndex = $mergeIndex, operation =  $operation where transactionId = $transactionId";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";

        $res = mysqli_query($conn, $sqlString);
	$data = ['AffectedRows' => mysqli_affected_rows($conn)];
	if($DEBUG == FALSE){
		header("Status: 200");
		header('Content-type: application/json');
	}
	echo json_encode($data);
	$pipeline_id = "";
//get all the fields
	$sqlString = "select * from $database.$table where transactionId = $transactionId";
	if($DEBUG == TRUE){
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
	}
	$resSelect = mysqli_query($conn, $sqlString);
	if($resSelect){
		$rows = array();
		if($resSelect)
        	{
                	while($r = mysqli_fetch_assoc($resSelect))
                	{
				$rows[] = $r;
				print_r($r);
				$packageName = $r['packageName'];
				$branch = $r['branch'];
				$pullRequestId = $r['pullRequestId'];
				$lastGoodBuildUsed = $r['lastGoodBuildUsed'];
				$imageEnterpriseFullPathAndName = $r['imageEnterpriseFullPathAndName'];
				$imageBaseFullPathAndName = $r['imageBaseFullPathAndName'];
				$OS_RELEASE = $r['OS_RELEASE'];
				$pipeline_id = $r['pipeline_id'];
				$packageRevision = $r['packageRevision'];
				echo "packageRevision = $packageRevision\n";
				echo "pipeline_id = $pipeline_id\n";
				$mergeIndex = $r['mergeIndex'];
			}
		}
	}else {
		print("failed to select any matches for transactionId = $transactionId\n");
	}

	$packageRevisionJustNumber = substr($packageRevision, strrpos($packageRevision, ".") + 1);
	echo "packageRevision just the number = [$packageRevisionJustNumber]\n";
	echo "calling continuum to move to the next phase\n";
	$url = 'http://veronecontinuum-eqx-01.force10networks.com:8080/api/set_pi_data';
	if($operation > 0)
		$didSucceed = "success";
	else
		$didSucceed = "fail";
	$someJSON="{\"pi\": \"$pipeline_id\", " .
			    "\"key\": \"returns\", \"value\": " .
				"{\"list\": " .
				  "{\"build-id\": \"8598b442-1cac-11e8-ae34-00505694438f\", " . 
				   "\"build-url\": \"https://eflow.force10networks.com/commander/jobDetails.php?jobId=8598b442-1cac-11e8-ae34-00505694438f\", " .
				   "\"outcome\": \"$didSucceed\", " .
				   "\"version\": \"$packageRevisionJustNumber\", " .
				   "\"transactionIdJessie\": \"$transactionId\", " .
				   "\"transactionIdStretch\": \"$transactionId\", " .
				   "\"package\": \"$packageName\", " .
				   "\"branch\": \"$branch\", " .
				   "\"lastGoodBuildUsed\": \"$lastGoodBuildUsed\", " .
				   "\"tftpboot_enterprise_path\": \"$imageEnterpriseFullPathAndName\", " .
				   "\"tftpboot_base_path\": \"$imageBaseFullPathAndName\", " .
				   "\"transactionId\": \"$transactionId\", " .
				   "\"pullRequestId\": \"$pullRequestId\" " . 
				"	}" .
				"}" .
			"}";
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

	print("*********** forcing to next phase *************\n");

	$ch = curl_init('http://veronecontinuum-eqx-01.force10networks.com:8080/api/promote_revision');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "package=$packageName&branch=$branch&revision=$packageRevisionJustNumber&phase=Trigger Merge Smoke Test");
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

/* this may need to happen after the final smoke test has succeeded */
//curl -H "Content-Type:application/json" -H "Accept:application/json" --user admin:admin -X GET http://localhost:7990/bitbucket/rest/api/1.0/projects/AR/repos/petes-repo/pull-requests/2/merge?version=0
		echo "now need to call the merge api on stash \n";
		return true;
}//end else
}//get_
?>
