<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
#$this->mysqli = new mysqli($this->host, $this->user, $this->pwd, $this->database);
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
	echo "Connected successfully";
	$database 	= "engOps";
	$table 		= "PackagesPassedSmokeTest";
	$sqlString = "INSERT INTO " . $database . "." . $table . "(`packageName`, `packageRevision`, `OS_RELEASE`) VALUES (\"cps-api\", \"001\", \"jessie\");";
        $res = mysqli_query($conn, $sqlString);
        if($res){
                 $transId = mysqli_insert_id($conn);
                 #if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
                     echo "transaction id now = [" . $transId . "]\n";
                 return true;
        }

	#$conn->mysqli($my_command);
}
?>
