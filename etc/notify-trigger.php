<?php 

function curl_enclosed($ch){
	echo "calling curl_exec()\n";
	print_r($ch);
	$curl_result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
	echo "response code :" . $httpCode + ", from curl = " . $curl_result . "\n";
	return $curl_result;
}

function mergeTrigger($branch, $packageName, $packageRevision, $jsonContainer, $mergeUser, $pullRequestId ){

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
	echo "calling veronecontinuum url\n";
	$ch = curl_init('https://veronecontinuum-eqx-01.force10networks.com:8080/api/promote_revision');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "package=$packageName&branch=$branch&revision=$packageRevision&phase=Trigger Merge Package Build");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Token 59553bf736ede316388f92ad',
					'Content-Type:  application/json'
    				)
				);                                                                                                                   
	if (curl_exec($ch) === FALSE) {
    		die("Curl failed: " . curl_error($c));
	}
	$curl_result = curl_enclosed($ch);

	curl_close($ch);
	var_dump($curl_result);
	mysqli_close($conn);
	return true;
}
}
?>
