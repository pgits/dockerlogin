<?php
// set your API key here
//browser $api_key = "AIzaSyAoIAKFDCpYMDn1AERvoizZP9qWjVPWJOY";
$api_key = "AIzaSyBOlrqpu7CqbSMI0O7pYaMzjK5uUrKHlR8";
// format this string with the appropriate latitude longitude
//old version $url = "http://maps.google.com/maps/geo?q=40.714224,-73.961452&output=json&sensor=true_or_false&key=$api_key";
$url = "https://maps.googleapis.com/maps/api/geocode/xml?latlng=41.802313,-87.929129&key=$api_key";
// make the HTTP request
echo "performing request on url = [" . $url . "]\n";
$xmldata = file_get_contents($url);
// print the data
print_r($xmldata);
?> 
