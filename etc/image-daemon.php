<?php 

//update the packages inside the branch with a 3 for the operation before sending to continuum to check the race condition
function update_branch_operation($myconn, $the_branch){
	$database   = "engOps";
	$table      = "mergeTriggerPassedSmokeTest";
	$DEBUG=TRUE;
	$sqlString = "UPDATE $database.$table set operation = 3 WHERE mergeStatus = 2 AND operation = 2 AND branch = '$the_branch' ;";
	if($DEBUG == TRUE)
		echo "\nupdate branch operation:  this is what we are sending to mySql:  [" . $sqlString . "]\n";
	$resOperation = mysqli_query($myconn, $sqlString);
	if($resOperation){
		$data = ['AffectedRows' => mysqli_affected_rows($myconn)];
		echo json_encode($data);
	}
	var_dump($resOperation);
}

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

	$sqlString = "SELECT DISTINCT branch FROM $database.$table WHERE mergeStatus = $mergeAbout2Build AND operation = $mergeAbout2Build ORDER BY transactionId ;";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";

	if($myresult = mysqli_query($conn, $sqlString)){
		$rows = array();
		// Return the number of rows in result set
		$rowcount=mysqli_num_rows($myresult);

		if($DEBUG == TRUE)
			printf("Result set has %d rows.\n",$rowcount);

//could be multiples, 
//so all of them should update with the same eflow-job
			while($r = mysqli_fetch_assoc($myresult)){
				$rows[] = $r;
				$branch = $r['branch'];
				if($DEBUG == TRUE){
					echo "branch located = $branch\n";
//may need to set the operation to 3 here before calling continuum....
					echo "calling update_branch_operation now\n";
				}
				update_branch_operation($conn, $branch);

			//call continuum imageBuild routine
				$someJSON="{
    					\"projectName\": \"F10-NGOS\",
    					\"procedureName\": \"CTM: Assemble Image (Merge Triggered)\",
    					\"body\": {
        					\"parameters\": {
            						\"actualParameter\": [
                 					{
                    					\"actualParameterName\": \"image_name\",
                    					\"value\": \"\"
                					}
            						],
            						\"actualParameter\": [
                 					{
                    					\"actualParameterName\": \"branch\",
                    					\"value\": \"$branch\"
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
				$curl_result = curl_exec($ch);
				print_r($curl_result);
				$jsonObj = json_decode($curl_result, true);
				print_r($jsonObj['jobId']);
				$eflow_jobId = $jsonObj['jobId'];
					//{"jobId":"3361a0d4-2018-11e8-ae34-00505694438f"}
        		$sqlString = "UPDATE $database.$table set `eflow_jobId` = \"$eflow_jobId\" WHERE mergeStatus = 2 AND operation = 3 AND branch = '$branch';";
				if($DEBUG == TRUE)
					echo "\nthis is what we are sending to mySql:  [" . $sqlString . "]\n";
        		$resUpdate = mysqli_query($conn, $sqlString);
        		if($resUpdate){
                		$data = ['AffectedRows' => mysqli_affected_rows($conn)];
                		echo json_encode($data);
        		}
				var_dump($resUpdate);
		}
		mysqli_close($conn);
		return true;
	}//end result
}//end else

}//end function daemon_check


daemon_check();
?>
