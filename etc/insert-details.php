<?php 
function insert_details($packageName, $packageBuiltPath, $packageRevision, $lastGoodBuildUsed, $OS_RELEASE, $serverName){
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
	$table 		= "PackagesPassedSmokeTest";
	$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageBuiltPath`, `packageRevision`, `lastGoodBuildUsed`, `OS_RELEASE`, `serverName`, `operation`) VALUES (\"$packageName\", \"$packageBuiltPath\", \"$packageRevision\", \"$lastGoodBuildUsed\", \"$OS_RELEASE\", \"$serverName\", 0);";
	#$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `OS_RELEASE`) VALUES (\"cps-api", \"001\", \"jessie\");";
	if($DEBUG == TRUE)
		echo "database = " . $database . "\n";
	if($DEBUG == TRUE)
		echo "table = " . $table . "\n";
	if($DEBUG == TRUE)
		echo "packageName = " . $packageName . "\n";
	if($DEBUG == TRUE)
		echo "pacakgeBuiltPath = " . $packageBuiltPath . "\n";
	if($DEBUG == TRUE)
		echo "packageRevision = " . $packageRevision . "\n";
	if($DEBUG == TRUE)
		echo "lastGoodBuildUsed = " . $lastGoodBuildUsed . "\n";
	if($DEBUG == TRUE)
		echo "OS_RELEASE = " . $OS_RELEASE . "\n";
	if($DEBUG == TRUE)
		echo "serverName = " . $serverName . "\n";
	if($DEBUG == TRUE)
		echo "this is what we are sending to mySql:  [" . $sqlString . "]\n";
	$res = 0;

        #$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `OS_RELEASE`) VALUES (\"cps-api\", \"001\", \"jessie\");";
        $res = mysqli_query($conn, $sqlString);
        if($res){
                 $transId = mysqli_insert_id($conn);
                 #if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		 $data = ['packageTransactionId' => $transId ];
		 header("Status: 200");
		 header('Content-type: application/json');
		 echo json_encode($data);
                     #echo "transaction id now = [" . $transId . "]\n";
		return true;
	}
}//end else
}//get_
?>
