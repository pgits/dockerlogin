<?php
// set your API key here
//browser $api_key = "AIzaSyAoIAKFDCpYMDn1AERvoizZP9qWjVPWJOY";
$api_key = "AIzaSyBOlrqpu7CqbSMI0O7pYaMzjK5uUrKHlR8";
// format this string with the appropriate latitude longitude
//old version $url = "http://maps.google.com/maps/geo?q=40.714224,-73.961452&output=json&sensor=true_or_false&key=$api_key";
$url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=34.7564,32.417158&key=$api_key";
// make the HTTP request
echo "performing request on url = [" . $url . "]\n";
$data = file_get_contents($url);
print_r($data);
exit;
// parse the json response
$jsondata = json_decode($data,true);
print_r($jsondata);
/*
foreach ($jsondata as $key => $value) { 
    echo "<p>$key | $value</p>";
}
*/

// if we get a placemark array and the status was good, get the addres
echo($jsondata);
if(is_array($jsondata )&& $jsondata ['Status']['code']==200)
{
      $addr = $jsondata ['Placemark'][0]['address'];
	echo($addr);
}
?> 
