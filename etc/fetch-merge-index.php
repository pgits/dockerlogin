<?php 
function fetch_merge_index(){
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
	if($DEBUG != TRUE)
		header("Status: 200");
	if($DEBUG == TRUE)
		echo "Connected successfully";
	$database 	= "engOps";
	$table		= "mergeIndex";
	#$sqlString = "SELECT * FROM $database.mergeIndex ";


	$sqlString = "INSERT INTO $database.$table SET timestamp = NOW()";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";

        $res = mysqli_query($conn, $sqlString);
        if($res){
                 $transId = mysqli_insert_id($conn);
                 #if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
                 $data = ['mergeIndex' => $transId ];
                 header("Status: 200");
                 header('Content-type: application/json');
                 echo json_encode($data);
                return true;
        }

}//end else
}//get_
?>
