<?php 
function update_details($packageTransactionId, $imageBaseName, $imageBaseFullPathAndName, $imageEnterpriseName, $imageEnterpriseFullPathAndName){
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
	if($DEBUG == TRUE)
		echo "Connected successfully";
	$database 	= "engOps";
	$table 		= "mergeTriggerPassedSmokeTest";
	if($imageEnterpriseName == "unknown")
		$sqlString = "UPDATE ". $database . "." . $table . " SET imageBaseName = '$imageBaseName', imageBaseFullPathAndName = '$imageBaseFullPathAndName', expires = DATE_ADD(CURRENT_DATE, INTERVAL 2 week), operation = 2 WHERE transactionId = $packageTransactionId;";
	else
		$sqlString = "UPDATE ". $database . "." . $table . " SET imageEnterpriseName = '$imageEnterpriseName', imageEnterpriseFullPathAndName = '$imageEnterpriseFullPathAndName', expires = DATE_ADD(CURRENT_DATE, INTERVAL 2 week), operation = 2 WHERE transactionId = $packageTransactionId;";

	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "table = " . $table . "\n";
	if($DEBUG == TRUE)
		echo "imageBaseName = " . $imageBaseName . "\n";
	if($DEBUG == TRUE)
		echo "imageEnterpriseName = " . $imageEnterpriseName . "\n";
	if($DEBUG == TRUE)
		echo "imageEnterpriseFullPathAndName = " . $imageEnterpriseFullPathAndName . "\n";
	if($DEBUG == TRUE)
		echo "imageBaseFullPathAndName = " . $imageBaseFullPathAndName . "\n";
	if($DEBUG == TRUE)
		echo "packageTransactionId = " . $packageTransactionId . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
	$res = 0;

        $res = mysqli_query($conn, $sqlString);
        if($res){
                 $transId = mysqli_insert_id($conn);
                 #if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		 $data = ['AffectedRows' => mysqli_affected_rows($conn)];
		 header("Status: 200");
		 header('Content-type: application/json');
		 echo json_encode($data);
		return true;
	}
}//end else
}//get_
?>
