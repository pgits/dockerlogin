<?php
// set your API key here
//browser $api_key = "AIzaSyAoIAKFDCpYMDn1AERvoizZP9qWjVPWJOY";
$api_key = "AIzaSyBOlrqpu7CqbSMI0O7pYaMzjK5uUrKHlR8";
// format this string with the appropriate latitude longitude
//old version $url = "http://maps.google.com/maps/geo?q=40.714224,-73.961452&output=json&sensor=true_or_false&key=$api_key";
$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=34.7564,32.417158&key=$api_key";
// make the HTTP request
echo "performing request on url = [" . $url . "]\n";
$data = file_get_contents($url);
// parse the json response
$jsondata = json_decode($data,true);
// if we get a placemark array and the status was good, get the addres
//echo($jsondata);
print_r($jsondata);
      $addr0 = $jsondata ['results'][0]['address'];
      $addr1 = $jsondata ['results'][1]['address'];
      $addr2 = $jsondata ['results'][2]['address'];
      $addr3 = $jsondata ['results'][3]['address'];
      $addr4 = $jsondata ['results'][4]['address'];
      $addr5 = $jsondata ['results'][5]['address'];
      $addr6 = $jsondata ['results'][6]['address'];
      $addr7 = $jsondata ['results'][7]['address'];
      $addr8 = $jsondata ['results'][8]['address'];
      $addr9 = $jsondata ['results'][9]['address'];
echo $addr0;
echo $addr1;
echo $addr2;
echo $addr3;
echo $addr4;
echo $addr5;
echo $addr6;
echo $addr7;
echo $addr8;
echo $addr9;
echo "jason data['results'][0]\n" ;
//echo "jason data['results'][0]['address_components']: \n" ;
//foreach($jsondata['results'][0]['address_components'] as &$value)
foreach($jsondata['results'][0] as &$value)
	echo "found value [" . $value . "]\n";
	foreach($value as &$valueInner1)
		echo "found value inner1 [" . $valueInner1 . "]\n";
		foreach($valueInner1 as &$valueInner2)
			echo "found value inner2 [" . $valueInner2 . "]\n";
			foreach($valueInner2 as &$valueInner3)
				echo "found value inner3 [" . $valueInner3 . "]\n";
// $jsondata['results'][0]['address_components'];
//var_dump($jsondata['results'][0]['address_components']) . "]\n";
/*
echo "jason data[0] = [" . $jsondata['results'][0]['address_components'] . "]\n";
*/
?> 
