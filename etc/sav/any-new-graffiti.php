<?php

#include('/opt/lampp/graffiti/db-4-graf.php');
include('/home1/produdn8/etc/groupon/db-4-graf.php');

$sqlInstance = new SqlUtils($dbx['hostname'],$dbx['username'],$dbx['password'],$dbx['database']);

function any_new_graffiti($sqlInstance, &$graffitiMessage, $city_Id, $country_code){
        $testIfStar = "*";
        if($sqlInstance != NULL) {
                $arrayResults = array();
                if($country_code === $testIfStar)
                        ///$whereClause = sprintf(" WHERE `city_Id` = '%d' ;", $city_Id);
			$whereClause = sprintf(" WHERE graffiti_Id = (SELECT MAX(`graffiti_Id`) FROM `graffiti_transactions` WHERE `city_Id` = '%d' ) ; ", $city_Id);
		else 
			///$whereClause = sprintf(" WHERE `city_Id` = '%d' AND `country_code` LIKE '%s' ;", $city_Id, $country_code); 
			$whereClause = sprintf(" WHERE graffiti_Id = (SELECT MAX(`graffiti_Id`) FROM `graffiti_transactions` WHERE `city_Id` = '%d' AND `country_code` LIKE '%s' ); ", $city_Id, $country_code);
		if( $sqlInstance->anyNewGraffitiMessages($graffitiMessage, $whereClause)){
			//var_dump($graffitiMessage);
			//printf("count of array results = %d\n", count($graffitiMessage));
			printf("<graffitiMessageList>\n");
			printf("<graffiti_Id>%s</graffiti_Id>\n", $graffitiMessage->graffiti_Id);
			printf("<created_timestamp>%s</created_timestamp>\n", $graffitiMessage->created_datetime);
			printf("</graffitiMessageList>\n");
             	}else
			printf("</graffitiMessageList>\n");
	}else
		echo("failed on mytestSql\n");
}


$graffitiMessage = new graffitiMessageInstance(NULL);

#$country_code = "USA";
#$city_Id = 0;
#any_new_graffiti($sqlInstance, $graffitiMessage, $city_Id, $country_code);

?>
