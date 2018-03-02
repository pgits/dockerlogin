<?php 

function daemon_check(){
$passedSmokeTest = 12;
$passedMergedSmokeTest=17;
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

	$sqlString = "SELECT * FROM $database.mergeTriggerPassedSmokeTest WHERE mergeStatus = 1 AND (operation = 12 OR operation = 17) ORDER BY transactionId ;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";

	while(true) {
		if($result = mysqli_query($conn, $sqlString)){
		$rows = array();
  		// Return the number of rows in result set
  		$rowcount=mysqli_num_rows($result);
		if($DEBUG == TRUE)
  			printf("Result set has %d rows.\n",$rowcount);
		if($rowcount == 0){
			echo "nothing to do, wait for 20 seconds\n";
		}
		else {
//call imageBuild routine
		}
	}

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
			var_dump($result);

        	$res = 0;
		mysqli_close($conn);
		return true;
	}//end while
}//end else
}
daemon_check();
?>
