<?php
// set your API key here
//browser $api_key = "AIzaSyAoIAKFDCpYMDn1AERvoizZP9qWjVPWJOY";
$api_key = "AIzaSyBOlrqpu7CqbSMI0O7pYaMzjK5uUrKHlR8";
// format this string with the appropriate latitude longitude
//old version $url = "http://maps.google.com/maps/geo?q=40.714224,-73.961452&output=json&sensor=true_or_false&key=$api_key";
$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=41.802313,-87.929129&key=$api_key";
// make the HTTP request
echo "performing request on url = [" . $url . "]\n";
$data = file_get_contents($url);
// parse the json response
$jsondata = json_decode($data,true);
// if we get a placemark array and the status was good, get the addres
//echo($jsondata);
print_r($jsondata);
echo "jason data['results']\n" ;
//echo "jason data['results'][0]['address_components']: \n" ;
//foreach($jsondata['results'][0]['address_components'] as &$value)
/*
foreach ($arr as $key => $value) {
    echo "Key: $key; Value: $value<br />\n";
}
*/
function getIndex($name, $array){
    foreach($array as $key => $value){
        if(is_array($value) && $value['name'] == $name)
              return $key;
	else if(is_array($value) )
		foreach($value as $key1 => $value1){
			if(is_array($value1) && $value1['name'] == $name)
				return $key1;
		}
    }
    return null;
}

$mykey = getIndex("administrative_area_level_1", $jsondata['results']);
        //string(27) "administrative_area_level_1"
echo "mykey = [" . $mykey . "]\n";

foreach($jsondata['results'] as $key => $value){
	if($value['types'][0] == "administrative_area_level_1"){
		//array 5
		echo "found key matching admin [" . $key . "] [". $value['types'][0] . "]\n";
		echo "value address components [" . $value["address_components"]["long_name"] . "]\n";

		var_dump($value);
		echo "value long_name_0 = " . $value['long_name'][0] . "\n";
		echo "jsondata_key_long_name = [" . $jsondata[$key]['long_name'] . "]\n";
		echo "value_long_name = " . $value['long_name'] . "\n";
	}
}

echo "jsondata\[results\]5 long_name = [" . $jsondata['results'][5]['long_name'] . "]\n";
echo "jsondata['results']5 address_components = [" . $jsondata['results'][5]['address_components'] . "]\n";

echo "jsondata['results']5 types[0] = [" . $jsondata['results'][5]['types'][0] . "]\n";
echo "jsondata['results']5 types[1] = [" . $jsondata['results'][5]['types'][1] . "]\n";
foreach($jsondata['results'] as $key => $value)
	echo "Key: $key => $value \n";
	var_dump($value);
	foreach($value as $key1 => $value1)
		echo "\tKey1: $key1 => $value1 \n";
		foreach($value1 as $key2 => $value2)
			echo "\t\tKey2: $key2 => $value2 \n";
			foreach($value2 as $key3 => $value3)
				echo "\t\t\tKey3: $key3 => $value3 \n";
// $jsondata['results'][0]['address_components'];
//var_dump($jsondata['results'][0]['address_components']) . "]\n";
/*
echo "jason data[0] = [" . $jsondata['results'][0]['address_components'] . "]\n";
*/
?> 
