<?php 

function daemon_check(){
$mergeAbout2Build = 2;
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

	$sqlString = "SELECT * FROM $database.mergeTriggerPassedSmokeTest WHERE mergeStatus = $mergeAbout2Build AND operation = $mergeAbout2Build ORDER BY transactionId ;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";

	if($result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		// Return the number of rows in result set
  		$rowcount=mysqli_num_rows($result);

		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($rowcount == 0){
			echo "nothing to do, wait for 20 seconds\n";
			sleep(20);
		}
		else {
//could be multiples, 
//so all of them should update with the same eflow-job
			//while($r = mysqli_fetch_assoc($result)) 
		//		$transactionId = $r['transactionId'];
			//call imageBuild routine
				$someJSON="{
    					\"projectName\": \"F10-NGOS\",
    					\"procedureName\": \"CTM: Assemble Image (Post Smoke Tests Passed)\",
    					\"body\": {
        					\"parameters\": {
            						\"actualParameter\": [
                 					{
                    					\"actualParameterName\": \"image_name\",
                    					\"value\": \"\"
                					}
            						]
        					}
    					}

				}";
			// Convert JSON string to Array
  			$someArray = json_decode($someJSON, true);
  			print_r($someArray);        // Dump all data of the Array

			$postJsonData = json_encode($someArray);
			echo "postJsonData now = " . $postJsonData . "\n";

			$ch = curl_init('https://eflow.force10networks.com/rest/v1.0/jobs?request=runProcedure');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postJsonData);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 					'Authorization: Basic bmV0YnVpbGQ6MUZpcmUuZHJpbGw=',
					'Content-Type: application/json',
    					'Content-Length: ' . strlen($postJsonData))
			);                                                                                                                   
			$result = curl_exec($ch);
			print_r($result);
			$jsonObj = json_decode($result, true);
			print_r($jsonObj['jobId']);
			$eflow_jobId = $jsonObj['jobId'];
//{"jobId":"3361a0d4-2018-11e8-ae34-00505694438f"}
        		$sqlString = "UPDATE $database.mergeTriggerPassedSmokeTest set `eflow_jobId` = \"$eflow_jobId\" WHERE mergeStatus = 2 AND operation = 2 ;";
			if($DEBUG == TRUE)
				echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";
        		$resUpdate = mysqli_query($conn, $sqlString);
        		if($resUpdate){
                		$data = ['AffectedRows' => mysqli_affected_rows($conn)];
                		echo json_encode($data);
        		}
			var_dump($result);

        		$res = 0;
			mysqli_close($conn);
			return true;
		}//end else
	}//end result
}//end else

}//end function daemon_check

daemon_check();
?>
