<?php 
function subsribe_4_calback($branch, $packageName, $pipeline_id, $jessie, $stretch, $which_service ){
$mysqlservername = "localhost";
$username = "root";
$password = "";

// Create connection
#$this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->database);
$conn = new mysqli($mysqlservername, $username, $password);

$DEBUG=FALSE;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    header("Status: 404");
    return false;
} else {
	if($DEBUG == TRUE)
		echo "Connected successfully";
	$database 	= "engOps";
	$table 		= "callBacks";
	$sqlString = "INSERT INTO " . $database . "." . $table . "(`branch`, `packageName`, `pipeline_id`, `transactionIdJessie`, `transactionIdStretch`, `serviceName`) VALUES (\"$branch\", \"$packageName\", \"$pipeline_id\", $jessie, $stretch, $which_service);";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "table = " . $table . "\n";
	if($DEBUG == TRUE)
		echo "packageTransactionId = " . $packageTransactionId . "\n";
	if($DEBUG == TRUE)
		echo "debFileName = " . $debFileName . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
	$res = 0;

        $res = mysqli_query($conn, $sqlString);
        if($res){
                 $transId = mysqli_insert_id($conn);
		 $data = ['callbackTransactionId' => $transId ];
		 header("Status: 200");
		 header('Content-type: application/json');
		 echo json_encode($data);
                 if($DEBUG == TRUE)
                     echo "transaction id now = [" . $transId . "]\n";
		return true;
	}
}//end else
}//get_
?>
