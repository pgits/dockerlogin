<?php
$servername = "localhost";
$username = "root";
$password = "";

$dbname    = "engOps";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM PackagesPassedSmokeTest";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "row = " . $row["transactionId"] . ", packageName=[" . $row["packageName"] . ", packageBuiltPath=[" . $row["packageBuiltPath"] . ", packageRevision=[" . $row["packageRevision"] . ", lastGoodBuildUsed=[" . $row["lastGoodBuildUsed"] . ", OS_RELEASE=[" . $row["OS_RELEASE"] . ", serverName=[" . $row["serverName"] . ", inserting=[" . $row["inserting"] . "]\n". "<br>";
        #echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
