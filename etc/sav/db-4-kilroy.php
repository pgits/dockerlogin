<?php
include('/home1/produdn8/etc/groupon/dbcfg-1.php');
$DEBUG_DATABASE_FLAG = FALSE;//$GLOBALS['PHP_DEBUG'];
class SqlUtils {
	var $mysqli;
	var $host;
	var $user;
	var $pwd;
	var $database;
	var $meeting2Subject     = "meetingId2meetingSubject";
	var $email2resourceTable = "email2resource";
	var $mac2userTable      = "mac2user";
	var $macTrackerTable    = "macTracker";
	var $productsTable	= "`products` ";
	var $categoriesTable	= "`categories` ";
	var $currenciesTable	= "`currencies` ";
	var $GapFillersTable	= "`GapFillers` ";
	var $GapInvestorsTable	= "`GapInvestors` ";
	var $net_revenue_royalty_paymentsTable = 	"`net_revenue_royalty_payments` ";
	var $percentage_paid_backTabl	= 		"`percentage_paid_back` ";
	var $product_investment_transactionsTable = 	"`product_investment_transactions` ";
	var $product_postTable	= "`product_posts` ";
	var $graffitiMessageTable	= "`graffiti_transactions`";
	var $VDMSMessageUrlTable        = "`DMS` ";//virtual DMS;
	var $couponDetailsTable         = "`coupon_details` ";//stores the link to where the actual coupon is stored, later to be dynamic
	var $storeDetailsTable          = "`store_details` ";//stores details 
	var $whereAmItable              = "`whereAmI`";
	var $citiesTable                = "`cities`";
	var $authorTable                = "`author`";
	var $whereAmItableDemoGps       = "`demoWhereAmILocation`";
	var $archivedWhereAmItable      = "`whereAmI_archived`";
	var $number_of_viewsTable	= "`number_of_views`";
	var $Kilroy_commentariesTable	= "`Kilroy_commentaries`";
	var $stores_backing_couponTable = "`stores_backing_coupon`";
	var $sqlGetProducts;
	var $sqlGetMeetingId;
	var $sqlCreateMeetingId;
	var $sqlGetResourceInfo;
	var $sqlGetMac4user;
        var $sqlGetRoomInfoGivenLongDescription;
        var $sqlTrackMac;
        var $removeTrackMac;
        var $sqlTrackMacExists;
	var $sqlRegMacAndUser;
	var $sqlGetGraffitiMessages;
	var $sqlGetGraffitiMessageUrls;
	var $sqlGetCouponMessageUrl;
	var $sql_AnyNewGraffitiMessages;
	var $sqlGetWhereAmI;
	var $sqlGetCityId;
	var $sqlAddCityId;
	var $sqlGetAuthorId;
	var $sqlAddAuthorId;
	var $sqlGetWhereAmIDemoGps;
	var $sqlGetWhereAmImac;
	var $sqlUpdateWhereAmIMseInProgress;
	var $sqlPostGraffitiMessage;
	var $sqlPostGraffitiMessage2;
	var $sqlPostUpdateLocationStr;
	var $sqlGetMseProcessIdFromArchiveStr;
	var $sqlUpdateMseProcessIdFromArchiveStr;
	var $sqlGetMacAddressFromArchiveStr;
	var $sqlUpdateIconNameStr;
	var $strSql;
	var $sqlGetCommentStr;
	var $sqlGetLikesStr;
	var $sqlILikedItStr;
	var $sqlIReportedItStr;
	var $sqlBanishedStr;
	var $sqlEmailSameStr;
	var $sqlEmailUpdateStr;
	var $sqlIsSwiggenReclaimedStr;
	var $sqlGetNextSwiggenStr;
	var $sqlIncrementNextSwiggenStr;

	public function __construct($hostname, $user, $pass, $database){
		$this->mysqli = new mysqli($hostname, $user, $pass, $database);
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			printf("parameters used = : hostname = %s, user = %s, pass= %s, and database = %s\n", $hostname, $user, $pass, $database);
			exit();
		}
		//printf("Host information: %s\n", mysqli_get_host_info($this->mysqli));
		$this->sqlGetProducts = "SELECT * FROM " . $this->productsTable ;
		$this->sqlGetGraffitiMessages = "SELECT t2.author_alias, t1.* FROM " . $database . "." . $this->graffitiMessageTable . " AS t1 INNER JOIN " . $database . "." . $this->authorTable . " AS t2 ON t1.graffiti_author_Id = t2.graffiti_author_Id " ;
		$this->sqlGetGraffitiMessageUrls = "SELECT * FROM " . $database . "." . $this->VDMSMessageUrlTable ;
		$this->sqlGetCouponMessageUrl = "SELECT * FROM " . $database . "." . $this->couponDetailsTable ;
		$this->sqlGetStoreDetails = "SELECT * FROM " . $database . "." . $this->storeDetailsTable ;
		$this->sql_AnyNewGraffitiMessages = "SELECT * FROM " . $database . "." . $this->graffitiMessageTable ;
		$this->sqlGetWhereAmImac        = "SELECT * FROM " . $this->whereAmItable ;
		$this->sqlGetWhereAmI      	= "SELECT * FROM " . $database . "." . $this->whereAmItable ;
		$this->sqlGetWhereAmIDemoGps    = "SELECT * FROM " . $database . "." . $this->whereAmItableDemoGps ;
		$this->sqlGetMacAddressFromArchiveStr= "SELECT `macAddress` FROM " . $database . "." . $this->archivedWhereAmItable ;
		$this->sqlUpdateMseProcessIdFromArchiveStr = "UPDATE " . $database . "." . $this->archivedWhereAmItable . " SET `process_stopped`= NOW() " ;
		$this->sqlIncrementNextSwiggenStr = "UPDATE " . $database . "." . $this->stores_backing_couponTable . " SET `next_swiggen_number`= `next_swiggen_number` + 1 WHERE " ;
		$this->sqlGetNextSwiggenStr = "SELECT `next_swiggen_number` FROM " . $database . "." . $this->stores_backing_couponTable . " WHERE " ;
		$this->sqlGetMseProcessIdFromArchiveStr= "SELECT `pid` FROM " . $database . "." . $this->archivedWhereAmItable ;
		$this->sqlDeleteWhereAmImac   = "DELETE FROM " . $this->whereAmItable ;
		$this->sqlUpdateWhereAmIMseInProgress = "UPDATE " . $database . "." . $this->whereAmItable . " SET `mseProcessing`=TRUE ";
		$this->sqlUpdateIconNameStr = "UPDATE " .  "`" . $database . "`" .  "." .  $this->graffitiMessageTable . " SET ";
		$this->sqlPostGraffitiMessage = "INSERT INTO " . $database . "." . $this->graffitiMessageTable . "(`graffiti_author_Id`, `message`, `country_code`, `x_coordinate`, `y_coordinate`, `z_coordinate`, `message_visible_distance`, `created_datetime`, `expires_datetime`) VALUES ( " ;
		$this->sqlPostGraffitiMessage2 = "INSERT INTO " . $database . "." . $this->graffitiMessageTable . "(`graffiti_author_Id`, `icon_name`, `message`, `city_Id`, `country_code`, `envelope_Id`, `x_coordinate`, `y_coordinate`, `z_coordinate`, `message_visible_distance`, `created_datetime`, `expires_datetime`) VALUES ( " ;
		$this->sqlInsertArchiveWhereAmI = "INSERT INTO " . $database . "." . $this->archivedWhereAmItable . "(`macAddress`, `graffiti_author_Id`, `last_mobile_request_time`, `last_mse_refresh_time`, `started_watching`, `pid`, `city_Id` ) VALUES ( " ;
		$this->sqlStartWatchingStr = "INSERT INTO " . $database . "." . $this->whereAmItable . "(`macAddress`, `graffiti_author_Id`, `city_Id`) VALUES ( " ;
		$this->sqlGetNewWatchersStr = "SELECT *, NOW() from " . "`" . $database . "`" .  "." . $this->whereAmItable . " WHERE `x_location`IS NULL AND `y_location` IS NULL AND `last_mse_refresh_time` IS NULL AND `mseProcessing` IS NULL AND `city_Id` = ";
		$this->sqlPostUpdateLocationStr = "UPDATE " .  "`" . $database . "`" .  "." .  $this->whereAmItable . " SET ";
        	$this->sqlGetRoomInfoGivenLongDescription = "SELECT * from email2resource WHERE longRoomName = ";
		$this->sqlGetMac4user  = "SELECT macAddress FROM mac2user WHERE username = ";//\'pgits\' LIMIT 0, 30 ";
		$this->sqlGetUserGivenMac  = "SELECT username FROM mac2user WHERE macAddress = ";//\'00:00:00:00:00:01\' LIMIT 0, 30 ";
		$this->sqlGetMeetingId = "SELECT meetingId FROM " . $this->meeting2Subject . " WHERE exchangeMeetingSubject = ";
		$this->sqlGetResourceInfo = "SELECT * FROM " . $this->email2resourceTable . " WHERE emailId = ";
		$this->sqlCreateMeetingId = "INSERT INTO " . $database . "." . $this->meeting2Subject . "(`meetingId`, `exchangeMeetingSubject`, `webExId`, `webExUrl`, `webExMeetingPassword`) VALUES (NULL, ";
		$this->sqlTrackMac = "INSERT INTO " . $database . "." . $this->macTrackerTable . "(`macAddress`, `trackStartTime`, `lastTimeRefreshed`) VALUES (";
		$this->sqlRegMacAndUser = "INSERT INTO `netlurkers`.`mac2user` ( `macAddress` , `username`) VALUES (";
		$this->removeTrackMac = "DELETE FROM `netlurkers`.`macTracker` WHERE `macTracker`.`macAddress` = ";
		$this->sqlTrackMacUpdates = "UPDATE " . $this->macTrackerTable . " SET lastTimeRefreshed = NOW() WHERE macAddress  = ";
		$this->sqlTrackMacExists = "SELECT * FROM " . $this->macTrackerTable . " WHERE macAddress  = ";
		$this->sqlGetCityId = "SELECT cityId FROM " . $database . "." . $this->citiesTable . " WHERE ";
		$this->sqlAddCityId = "INSERT INTO " . $database . "." . $this->citiesTable . "( `CityName` , `State` , `Country`, `CountryCode`) VALUES (";
		$this->sqlGetAuthorId = "SELECT graffiti_author_Id FROM " . $database . "." . $this->authorTable . " WHERE ";
		$this->sqlAddAuthorId = "INSERT INTO " . $database . "." . $this->authorTable . "( `author_alias`, `UDID_Vendor`, `city_Id` , `city` , `state` , `zip_code`, `country` , `country_code`, `gender`, `age`, `gps`, `latitude`, `longitude` ) VALUES (";
		$this->sqlILikedItStr = "INSERT INTO " . $database . "." . $this->number_of_viewsTable . "(`liked`, `graffiti_Id`, `city_Id`, `viewer_Id`) VALUES ( 1, " ;
		$this->sqlIReportedItStr = "INSERT INTO " . $database . "." . $this->number_of_viewsTable . "(`reported`, `graffiti_Id`, `city_Id`, `viewer_Id`) VALUES ( 1, " ;
		$this->sqlUpdateCommentStr = "UPDATE " .  "`" . $database . "`" .  "." .  $this->graffitiMessageTable . " SET message = "; //comment where graffiti_Id = AND city_Id = 
		$this->sqlAddCommentStr = "INSERT INTO " . "`" . $database . "`" . "." . $this->Kilroy_commentariesTable . "(`graffiti_Id`, `city_Id`, `review_author_Id`, `comment`) VALUES ( " ;
		$this->sqlBanishedStr = "SELECT * FROM " . "`" . $database . "`" . "." . $this->authorTable . " WHERE ";
		$this->sqlEmailSameStr = "SELECT * FROM " . "`" . $database . "`" . "." . $this->authorTable . " WHERE ";
		$this->sqlEmailUpdateStr = "UPDATE " .  "`" . $database . "`" .  "." .  $this->authorTable . " SET "; //where author_Id  = 
		$this->sqlReclaimSwiggenStr = "INSERT INTO " . "`" . $database . "`" . "." . $this->number_of_viewsTable . "(`graffiti_Id`, `viewer_Id`, `store_Id`, `swiggen_number`, `swiggen_descr`, `qr_swiggen_scanned`) VALUES ( " ;
		$this->sqlIsSwiggenReclaimedStr = "SELECT * FROM " . "`" . $database . "`" . "." . $this->number_of_viewsTable . " WHERE ";
		$this->sqlGetLikesStr = "SELECT COUNT(*) FROM `number_of_views` WHERE DATE(created) >= DATE(NOW() - INTERVAL 30 DAY) ";
		$this->sqlGetTheCommentStr = "SELECT message FROM " . $database . "." . $this->graffitiMessageTable . " WHERE ";
		$this->sqlGetCommentStr = "SELECT t2.author_alias, t1.comment, t1.voice_comment_link, t1.video_comment_link, t1.datestamp FROM " . $database . "." . $this->Kilroy_commentariesTable . " AS t1 INNER JOIN " . $database . "." . $this->authorTable . " AS t2 ON t1.review_author_Id = t2.graffiti_author_Id WHERE ";
	}

	public function __destructor(){
		mysqli_close($this->mysqli);
	}
//GRAFFITI
	public function sqlGetWhereAmI(&$arrayResults, $mac_address, $city_Id){
		$bInsert = false;
		$strSqlStep1 = sprintf(" WHERE `macAddress` = '%s' AND `city_Id` = '%d'", $mac_address, $city_Id);
		$strSql = $this->sqlGetWhereAmI . $strSqlStep1 . " ;";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlStartWatching:sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);

               if($res){
                        while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                                if(count($resultsArrayFetch) > 0)
                                {
                                        $productRowResult = new whereAmiInstance(NULL);
					$productRowResult->macAddress		= $resultsArrayFetch['macAddress'];
					$productRowResult->ipAddress		= $resultsArrayFetch['ipAddress'];
					$productRowResult->x_location		= $resultsArrayFetch['x_location'];
					$productRowResult->y_location		= $resultsArrayFetch['y_location'];
					$productRowResult->z_location		= $resultsArrayFetch['z_location'];
					$productRowResult->started_watching	= $resultsArrayFetch['started_watching'];
					$productRowResult->graffiti_author_id	= $resultsArrayFetch['graffiti_author_id'];
					$productRowResult->city_Id		= $resultsArrayFetch['city_Id'];
					$productRowResult->building_Id		= $resultsArrayFetch['building_Id'];
					$productRowResult->building_floor	= $resultsArrayFetch['building_floor'];
					$productRowResult->last_mse_refresh_time= $resultsArrayFetch['last_mse_refresh_time'];
					$productRowResult->latitude		= $resultsArrayFetch['latitude'];
					$productRowResult->longitude		= $resultsArrayFetch['longitude'];
                                        $bFound = true;
                                        array_push($arrayResults,$productRowResult);
                                } 
                    }//while
                    mysqli_free_result($res);
                    return true;
                } else {
                        printf("select $res must equal NULL, $res = [%d]\n", $res);
                        echo("mysqli_query failed "  . $res . "\n");
                        return false;
                }
                return true;
	}
	public function sqlGetWhereAmIDemoGps(&$arrayResults, $city_Id){
		$bInsert = false;
		$strSqlStep1 = sprintf(" WHERE `city_Id` = '%d'", $city_Id);
		$strSql = $this->sqlGetWhereAmIDemoGps . $strSqlStep1 . " ;";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlStartWatching:sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);

               if($res){
                        while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                                if(count($resultsArrayFetch) > 0)
                                {
                                        $productRowResult = new whereAmiInstance(NULL);
					$productRowResult->latitude		= $resultsArrayFetch['latitude'];
					$productRowResult->longitude		= $resultsArrayFetch['longitude'];
                                        $bFound = true;
                                        array_push($arrayResults,$productRowResult);
                                } 
                    }//while
                    mysqli_free_result($res);
                    return true;
                } else {
                        printf("select $res must equal NULL, $res = [%d]\n", $res);
                        echo("mysqli_query failed "  . $res . "\n");
                        return false;
                }
                return true;
	}
	public function SqlGetNewWatchers (&$arrayResults, $city_Id ){
		$bFound = false;
        	$sqlString = $this->sqlGetNewWatchersStr . "'" . $city_Id . "'" . ";";
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("SqlGetNewWatchers = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$productRowResult = new whereAmiInstance(NULL);
					$productRowResult->macAddress		= $resultsArrayFetch['macAddress'];
					$productRowResult->ipAddress		= $resultsArrayFetch['ipAddress'];
					$productRowResult->x_locaton		= $resultsArrayFetch['x_locaton'];
					$productRowResult->y_location		= $resultsArrayFetch['y_location'];
					$productRowResult->z_location		= $resultsArrayFetch['z_location'];
					$productRowResult->started_watching	= $resultsArrayFetch['started_watching'];
					$productRowResult->graffiti_author_id	= $resultsArrayFetch['graffiti_author_id'];
					$productRowResult->city_Id		= $resultsArrayFetch['city_Id'];
					$productRowResult->building_Id		= $resultsArrayFetch['building_Id'];
					$productRowResult->building_floor	= $resultsArrayFetch['building_floor'];
					$productRowResult->last_mse_refresh_time= $resultsArrayFetch['last_mse_refresh_time'];
                                        $productRowResult->latitude             = $resultsArrayFetch['latitude'];
                                        $productRowResult->longitude            = $resultsArrayFetch['longitude'];
                                        $productRowResult->sql_now              = $resultsArrayFetch['NOW()'];
					$bFound = true;
					array_push($arrayResults,$productRowResult);
				} else
					echo("failed to locate any new Watchers\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function sqlUpdateIcon($city_Id, $graffiti_message_id, $icon_name){
		$bInsert = false;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
//THERE
		echo("sqlUpdateIconNameStr begin = " . $this->sqlUpdateIconNameStr);

		$strSqlStep1 = sprintf("`icon_name` = '%s', ", $icon_name);
		$strSqlStep2 = sprintf("`icon_invisible` = 'FALSE', ");
		$strSqlStep3 = sprintf("`created_datetime` = NOW() WHERE ");
		$strSqlStep4 = sprintf("%s.`graffiti_id` = '%s' AND ", $this->graffitiMessageTable, $graffiti_message_id);
		$strSqlStep5 = sprintf("%s.`city_Id` = '%d' ", $this->graffitiMessageTable, $city_Id);

		$strSql = $this->sqlUpdateIconNameStr . $strSqlStep1 . $strSqlStep2 . $strSqlStep3 . $strSqlStep4 . $strSqlStep5 . ";";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlStartWatching:sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			return true;
		}
		return $bInsert;
	}
	public function sqlMseInProgress($macAddress, $city_Id, $pid){
		$bUpdate = false;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlUpdateWhereAmIMseInProgress begin = " . $this->sqlUpdateWhereAmIMseInProgress );
		$strSqlStep0 = sprintf(", `pid`=%s WHERE ", $pid);
		$strSqlStep1 = sprintf(" %s.`macAddress` = '%s' AND ", $this->whereAmItable, $macAddress);
		$strSqlStep2 = sprintf("%s.`city_Id` = '%d' ", $this->whereAmItable, $city_Id);

		$strSql = $this->sqlUpdateWhereAmIMseInProgress . $strSqlStep0 .  $strSqlStep1 . $strSqlStep2 . ";";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlStartWatching:sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			return true;
		}
		return $bUpdate;
	}
	public function sqlPostLocationUpdates($whereAmIinstance, $macAddress, $x, $y, $z, $city_Id, $building_Id, $building_floor){
		$bInsert = false;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlPostUpdateLocation begin = " . $this->sqlPostUpdateLocationStr);

		$strSqlStep1 = sprintf("`x_location` = '%d', ", $x);
		$strSqlStep2 = sprintf("`y_location` = '%d', ", $y);
		$strSqlStep3 = sprintf("`z_location` = '%d', ", $z);
		$strSqlStep4 = sprintf("`last_mobile_request_time` = NOW() WHERE ", $z);
		$strSqlStep5 = sprintf("%s.`macAddress` = '%s' AND ", $this->whereAmItable, $macAddress);
		$strSqlStep6 = sprintf("%s.`city_Id` = '%d' ", $this->whereAmItable, $city_Id);

//UPDATE `produdn8_Graffiti`.`whereAmI` SET `x_location` = '20', `y_location` = '40', `last_mobile_request_time` = '', `last_mse_refresh_time` = NOW() WHERE `whereAmI`.`macAddress` = '00:00:00:00:00:01';
		$strSql = $this->sqlPostUpdateLocationStr . $strSqlStep1 . $strSqlStep2 . $strSqlStep3 . $strSqlStep4 . $strSqlStep5 . $strSqlStep6 . ";";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlStartWatching:sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			//echo "inserted a new row\n";
			//$bInsert = mysqli_insert_id($this->mysqli);
			return true;
		}
		return $bInsert;
	}
	public function sqlStartWatching($whereAmIinstance, $mac_address, $username, $city_Id){
		$bInsert = false;
		//echo("sqlStartWatchingStr begin = " . $this->sqlStartWatchingStr);

		$strSqlStep1 = sprintf("'%s', '%s', '%d'", $mac_address, $username, $city_Id);
		$strSql = $this->sqlStartWatchingStr . $strSqlStep1 . ");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlStartWatching:sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			//echo "inserted a new row\n";
			//$bInsert = mysqli_insert_id($this->mysqli);
			return true;
		}
		return $bInsert;
	}
	public function sqlGetPid ($city_Id, $macAddress){
		$bFound = false;
		$whereClause = sprintf(" WHERE `macAddress` = '%s' AND `city_Id` = %d AND `process_stopped` IS NULL ;\n", $macAddress, $city_Id);
        	$sqlString = $this->sqlGetMseProcessIdFromArchiveStr . $whereClause;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlgetMseProcessIdFromArchiveStr = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					//var_dump($resultsArrayFetch);
					//echo $resultsArrayFetch;
					$pid = $resultsArrayFetch['pid'];
					return $pid;
				} else {
					echo("failed to locate" . $this->sqlGetMseProcessIdFromArchiveStr . "\n");
					return -1;
				}
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			echo("mysqli_query failed "  . $res . "\n");
			return -1;
		}
		return -1;
	}
	public function sqlPidKilled ($city_Id, $macAddress){
		$numRowsUpdated = 0;
		$whereClause = sprintf(" WHERE `city_Id` = %d AND `macAddress` = '%s' AND `process_stopped` IS NULL ;\n", $city_Id, $macAddress);
        	$sqlString = $this->sqlUpdateMseProcessIdFromArchiveStr . $whereClause;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlUpdateMseProcessIdFromArchiveStr = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res) {
			if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
				printf("number of rows affected = %d", $this->mysqli->affected_rows);
			$numRowsUpdated = $this->mysqli->affected_rows;
		}
		    //mysqli_free_result($res);
		return $numRowsUpdated;
	}
	public function sqlGetMacAddressToStopWatching ($arrayResults, $city_Id){
		$bFound = FALSE;
		$whereClause = sprintf(" WHERE `city_Id` = %d AND `process_stopped` IS NULL ;\n", $city_Id);
        	$sqlString = $this->sqlGetMacAddressFromArchiveStr . $whereClause;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlGetMacAddressFromArchiveStr = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$bFound = TRUE;
					array_push($arrayResults,$resultsArrayFetch['macAddress']);
					if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
					    printf("macAddress = [%s]\n", $resultsArrayFetch['macAddress']);
				} 
		    }//while
		}
		//mysqli_free_result($res);
		//printf("returning bFound = [%d]\n", $bFound);
		return $bFound;
	}
        public function sqlStopWatching($whereAmIinstance, $mac_address ){
                $bInsert = false;
                //echo("sqlStartWatchingStr begin = " . $this->sqlStartWatchingStr);

                $strSqlStep1 = sprintf("'%s', '%d'", $mac_address, $city_Id);
                $strSql = $this->sqlStartWatchingStr . $strSqlStep1 . ");";
                if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
                   echo("sqlStartWatching:sql = [" . $strSql . "\n");
                $res = mysqli_query($this->mysqli, $strSql);
                if($res){
                        //echo "inserted a new row\n";
                        //$bInsert = mysqli_insert_id($this->mysqli);
                        return true;
                }
                return $bInsert;
        }
	public function postGraffitiMessage2($username, $icon_name, $message, $city_Id, $country_code, $envelope_Id, $x, $y, $z, $away, $date, $until){
		$bInsert = false;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlPostGraffitiMessage begin = " . $this->sqlPostGraffitiMessage2);
		//$strSqlStep1 = sprintf(" '%s', '%s', '%s'", $username, $message, $country_code);
		//$strSqlStep2 = sprintf(", '%d', '%d', '%d', '%d'", $x, $y, $z, $away);
		//$strSqlStep3 = sprintf(", '%s', '%s'", $date, $until);

		$strSqlStep1 = sprintf("'%s', '%s', '%s', '%s', '%s', '%d', '%f', '%f', '%f', '%d', '%s', '%s'", $username, $icon_name, $message, $city_Id, $country_code, $envelope_Id, $x, $y, $z, $away, $date, $until);
		//$strSql = $this->sqlPostGraffitiMessage2 . $strSqlStep1 . $strSqlStep2 . $strSqlStep3 ");";
		$strSql = $this->sqlPostGraffitiMessage2 . $strSqlStep1 . ");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sql.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
		}
		return $bInsert;
	}
	public function postGraffitiMessage($username, $message, $country_code, $x, $y, $z, $away, $date, $until){
		$bInsert = false;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlPostGraffitiMessage begin = " . $this->sqlPostGraffitiMessage);
		//$strSqlStep1 = sprintf(" '%s', '%s', '%s'", $username, $message, $country_code);
		//$strSqlStep2 = sprintf(", '%d', '%d', '%d', '%d'", $x, $y, $z, $away);
		//$strSqlStep3 = sprintf(", '%s', '%s'", $date, $until);

		$strSqlStep1 = sprintf("'%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%s'", $username, $message, $country_code, $x, $y, $z, $away, $date, $until);
		//$strSql = $this->sqlPostGraffitiMessage . $strSqlStep1 . $strSqlStep2 . $strSqlStep3 ");";
		$strSql = $this->sqlPostGraffitiMessage . $strSqlStep1 . ");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sql.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
		}
		return $bInsert;
	}
	public function getGraffitiMessages (&$arrayResults, $whereClause){
		$bFound = false;
        $sqlString = $this->sqlGetGraffitiMessages . $whereClause;
		$increment = 0;
	//if($DEBUG_DATABASE_FLAG == TRUE)
		//echo("sqlGetGraffitiMessages = [" . $sqlString . "]\n");
        $res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$productRowResult = new graffitiMessageInstance(NULL);
					$productRowResult->city_Id    		= $resultsArrayFetch['city_Id'];
					$productRowResult->building_Id    	= $resultsArrayFetch['building_Id'];
					$productRowResult->building_floor  	= $resultsArrayFetch['building_floor'];
					$productRowResult->graffiti_Id    	= $resultsArrayFetch['graffiti_Id'];
					$productRowResult->envelope_Id    	= $resultsArrayFetch['envelope_Id'];
					$productRowResult->graffiti_author_Id   = $resultsArrayFetch['graffiti_author_Id'];
					$productRowResult->graffiti_author_alias= $resultsArrayFetch['author_alias'];
					$productRowResult->country_code   	= $resultsArrayFetch['country_code'];
					$productRowResult->created_datetime = $resultsArrayFetch['created_datetime'];
					$productRowResult->expires_datetime = $resultsArrayFetch['expires_datetime'];
					$productRowResult->to 				= $resultsArrayFetch['to'];
					$productRowResult->message 			= $resultsArrayFetch['message'];
					$productRowResult->message_font 	= $resultsArrayFetch['message_font'];
					$productRowResult->message_size 	= $resultsArrayFetch['message_size'];
					$productRowResult->icon_invisible 	= $resultsArrayFetch['icon_invisible'];
					$productRowResult->icon_visible_distance = $resultsArrayFetch['icon_visible_distance'];
					$productRowResult->icon_name 		= $resultsArrayFetch['icon_name'];
					$productRowResult->x_coordinate 	= $resultsArrayFetch['x_coordinate'];
					$productRowResult->y_coordinate 	= $resultsArrayFetch['y_coordinate'];
					$productRowResult->z_coordinate 	= $resultsArrayFetch['z_coordinate'];
					$productRowResult->message_visible_distance = $resultsArrayFetch['message_visible_distance'];
					$productRowResult->return_receipt_requested = $resultsArrayFetch['return_receipt_requested'];
					$productRowResult->notify_recipients 	= $resultsArrayFetch['notify_recipients'];
					$productRowResult->message_archived 	= $resultsArrayFetch['message_archived'];
					$productRowResult->encrypted_password_index = $resultsArrayFetch['encrypted_password_index'];
					$productRowResult->version_left_of_period = $resultsArrayFetch['version_left_of_period'];
					$productRowResult->version_right_of_period= $resultsArrayFetch['version_right_of_period'];
					$bFound = true;
					array_push($arrayResults,$productRowResult);
				} else
					echo("failed to locate graffitiMessages\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function getGraffitiMessageUrls (&$arrayResults, $whereClause){
		$bFound = false;
		//printf("sqlGetGraffitiMessageUrls = [%s]\n", $this->sqlGetGraffitiMessageUrls);
        	$sqlString = $this->sqlGetGraffitiMessageUrls . $whereClause;
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlGetGraffitiMessageUrls = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$productRowResult = new graffitiMessageUrlInstance(NULL);
					$productRowResult->dms_transaction    	= $resultsArrayFetch['dms_transaction'];
					$productRowResult->graffiti_Id    	= $resultsArrayFetch['graffiti_Id'];
					$productRowResult->city_Id    		= $resultsArrayFetch['city_Id'];
					$productRowResult->website_address_loop = $resultsArrayFetch['website_address_loop'];
					$productRowResult->pause_on_look_away   = $resultsArrayFetch['pause_on_look_away'];
					$productRowResult->next_web_push_delay  = $resultsArrayFetch['next_web_push_delay'];
					$productRowResult->gender  		= $resultsArrayFetch['gender'];
					$productRowResult->age  		= $resultsArrayFetch['age'];
					$productRowResult->loop_vendor   	= $resultsArrayFetch['loop_vendor'];
					$productRowResult->audit_trail 		= $resultsArrayFetch['audit_trail'];
					$bFound = true;
					array_push($arrayResults,$productRowResult);
				} else
					echo("failed to locate graffitiMessageUrls\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function getGraffitiCouponUrl (&$arrayResults, $whereClause){
		$bFound = false;
		//printf("sqlGetGraffitiMessageUrls = [%s]\n", $this->sqlGetCouponMessageUrl);
        	$sqlString = $this->sqlGetCouponMessageUrl . $whereClause;
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlGetCouponMessageUrl = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$productRowResult = new graffitiCouponUrlInstance(NULL);
					$productRowResult->graffiti_Id    	= $resultsArrayFetch['graffiti_Id'];
					$productRowResult->city_Id    		= $resultsArrayFetch['city_Id'];
					$productRowResult->business_name 	= $resultsArrayFetch['business_name'];
					$productRowResult->coupon_url 		= $resultsArrayFetch['coupon_url'];
					$productRowResult->coupon_url 		= $resultsArrayFetch['coupon_url'];
					$productRowResult->percentage_under_60 	= $resultsArrayFetch['percentage_under_60'];
					$productRowResult->percentage_under_120 = $resultsArrayFetch['percentage_under_120'];
					$productRowResult->percentage_over_120 	= $resultsArrayFetch['percentage_over_120'];
					$productRowResult->amount_off_under_60 	= $resultsArrayFetch['amount_off_under_60']; 
					$productRowResult->amount_off_under_120 = $resultsArrayFetch['amount_off_under_120']; 
					$productRowResult->amount_off_over_120 	= $resultsArrayFetch['amount_off_over_120'];
					$productRowResult->currency_symbol 	= $resultsArrayFetch['currency_symbol'];
					$productRowResult->expires 		= $resultsArrayFetch['expires'];
					$productRowResult->coupon_limit 	= $resultsArrayFetch['coupon_limit'];
					$productRowResult->downloaded_counter 	= $resultsArrayFetch['downloaded_counter'];
					$productRowResult->scanned_counter_under_60 = $resultsArrayFetch['scanned_counter_under_60'];
					$productRowResult->scanned_counter_under_120 = $resultsArrayFetch['scanned_counter_under_120'];
					$productRowResult->scanned_counter_under_time_limit = $resultsArrayFetch['scanned_counter_under_time_limit'];
					$productRowResult->latitude 		= $resultsArrayFetch['latitude'];
					$productRowResult->longitude 		= $resultsArrayFetch['longitude'];
					$productRowResult->nearest_store_Id 	= $resultsArrayFetch['nearest_store_Id'];
					$productRowResult->walking 		= $resultsArrayFetch['walking'];
					$bFound = true;
					array_push($arrayResults,$productRowResult);
				} else
					echo("failed to locate graffitiCouponUrl\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function getStoreDetails (&$arrayResults, $whereClause){
		$bFound = false;
		//printf("sqlGetStoreDetails = [%s]\n", $this->sqlGetStoreDetails);
        	$sqlString = $this->sqlGetStoreDetails . $whereClause;
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlGetStoreDetails = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$productRowResult = new storeDetailsInstance(NULL);
					$productRowResult->store_Id    		= $resultsArrayFetch['store_Id'];
					$productRowResult->store_name    	= $resultsArrayFetch['store_name'];
					$productRowResult->store_address1 	= $resultsArrayFetch['store_address1'];
					$productRowResult->store_address2 	= $resultsArrayFetch['store_address2'];
					$productRowResult->city 		= $resultsArrayFetch['city'];
					$productRowResult->state_province 	= $resultsArrayFetch['state_province'];
					$productRowResult->zip_code 		= $resultsArrayFetch['zip_code'];
					$productRowResult->country 		= $resultsArrayFetch['country'];
					$productRowResult->phone_number 	= $resultsArrayFetch['phone_number']; 
					$productRowResult->latitude 		= $resultsArrayFetch['latitude']; 
					$productRowResult->longitude 		= $resultsArrayFetch['longitude'];
					$productRowResult->website_url 		= $resultsArrayFetch['website_url'];
					$productRowResult->business_Id 		= $resultsArrayFetch['business_Id'];
					$bFound = true;
					array_push($arrayResults,$productRowResult);
				} else
					echo("failed to locate storeDetails\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function anyNewGraffitiMessages (&$arrayResults, $whereClause){
		$bFound = false;
        	$sqlString = $this->sql_AnyNewGraffitiMessages . $whereClause;
		$increment = 0;
		//if($DEBUG_DATABASE_FLAG == TRUE)
		//   echo("sqlAnyNewGraffitiMessages = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					//var_dump($resultsArrayFetch);
					//echo $resultsArrayFetch;
					$arrayResults->graffiti_Id    	= $resultsArrayFetch['graffiti_Id'];
					///$arrayResults->graffiti_Id    	= $resultsArrayFetch['MAX(`graffiti_Id`)'];
					$arrayResults->created_datetime = $resultsArrayFetch['created_datetime'];
					///$arrayResults->created_datetime = $resultsArrayFetch['MAX(`created_datetime`)'];
					$bFound = true;
				} else
					echo("failed to locate graffitiMessages\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function sqlStopWatchingSelect (&$theResult, $macAddressIn, $city_Id){
		$bFound = false;
        	$sqlString = $this->sqlGetWhereAmImac . " WHERE `macAddress` = " . "'" . $macAddressIn . "';";
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlArchiveStopWatching = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					$RowResult = new whereAmiInstance(NULL);
					$RowResult->macAddress    		= $resultsArrayFetch['macAddress'];
					$RowResult->ipAddress    		= $resultsArrayFetch['ipAddress'];
					$RowResult->graffiti_author_id  	= $resultsArrayFetch['graffiti_author_id'];
					$RowResult->started_watching  		= $resultsArrayFetch['started_watching'];
					$RowResult->last_mobile_request_time    = $resultsArrayFetch['last_mobile_request_time'];
					$RowResult->last_mse_refresh_time   	= $resultsArrayFetch['last_mse_refresh_time'];
					$RowResult->city_Id   			= $resultsArrayFetch['city_Id'];
					$RowResult->building_Id 		= $resultsArrayFetch['building_Id'];
					$RowResult->building_floor 		= $resultsArrayFetch['building_floor'];
					$RowResult->pid 			= $resultsArrayFetch['pid'];
					$RowResult->latitude 			= $resultsArrayFetch['latitude'];
					$RowResult->longitude 			= $resultsArrayFetch['longitude'];
					$bFound = true;
					$theResult = $RowResult;
					if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
						echo $RowResult;
				} else
					echo("failed to locate WhereAmI record\n");
		    }//while
		    mysqli_free_result($res);
		    return true;
		} else {
			printf("select $res must equal NULL, $res = [%d]\n", $res);
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function sqlStopWatchingArchive (&$theResult, $wi ){
		$bInsert = false;
		$sqlString1 = sprintf("'%s', '%s', '%s', '%s', '%s', '%s', '%s'", $wi->macAddress, $wi->graffiti_author_id, $wi->last_mobile_request_time, $wi->last_mse_refresh_time, $wi->started_watching, $wi->pid, $wi->city_Id);

	//	$this->sqlInsertArchiveWhereAmI = "INSERT INTO " . $database . "." . $this->archivedWhereAmItable . "(`macAddress`, `graffiti_author_Id`, `last_mobile_request_time`, `last_mse_refresh_time`, `started_watching`) VALUES ( " ;
        	$sqlString = $this->sqlInsertArchiveWhereAmI . $sqlString1 . ");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlArchiveStopWatching = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
		}
		return $bInsert;
	}
	public function sqlStopWatchingDelete (&$theResults, $macAddressIn){
		$bRemovedMac = false;
        	$sqlString = $this->sqlDeleteWhereAmImac . " WHERE `macAddress` = " . "'" . $macAddressIn . "';";
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sqlArchiveStopWatching = [" . $sqlString . "]\n");
        	$res = mysqli_query($this->mysqli, $sqlString);
		if($res)
			$bRemovedMac = true;
		return $bRemovedMac;
	}
	public function getProductsGG (&$arrayResults, $whereClause){
		$bFound = false;
                $sqlString = $this->sqlGetProducts . $whereClause;
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlGetProducts = [" . $sqlString . "]\n");
                $res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
			if(count($resultsArrayFetch) > 0) 
			{
				$productRowResult = new productsInstance(NULL);
				$productRowResult->product_Id    		= $resultsArrayFetch['product_Id'];
				$productRowResult->company_Id    		= $resultsArrayFetch['company_Id'];
				$productRowResult->product_name  		= $resultsArrayFetch['product_name'];
				$productRowResult->product_Id    		= $resultsArrayFetch['product_Id'];
				$productRowResult->company_Id    		= $resultsArrayFetch['company_Id'];
				$productRowResult->product_name 		= $resultsArrayFetch['product_name'];
				$productRowResult->product_category 		= $resultsArrayFetch['product_category'];
				$productRowResult->product_link 		= $resultsArrayFetch['product_link'];
				$productRowResult->product_picture 		= $resultsArrayFetch['product_picture'];
				$productRowResult->percentage_to_share 		= $resultsArrayFetch['percentage_to_share'];
				$productRowResult->amount_to_raise 		= $resultsArrayFetch['amount_to_raise'];
				$productRowResult->amount_raised 		= $resultsArrayFetch['amount_raised'];
				$productRowResult->years_to_share_revenue 	= $resultsArrayFetch['years_to_share_revenue'];
				$productRowResult->share_life_of_product 	= $resultsArrayFetch['share_life_of_product'];
				$productRowResult->open_date 			= $resultsArrayFetch['open_date'];
				$productRowResult->close_date 			= $resultsArrayFetch['close_date'];
				$productRowResult->fully_funded_date 		= $resultsArrayFetch['fully_funded_date'];
				$productRowResult->percentage_to_kick_off_development = $resultsArrayFetch['percentage_to_kick_off_development'];
				$productRowResult->date_development_started = $resultsArrayFetch['date_development_started'];
				$productRowResult->date_product_released 	= $resultsArrayFetch['date_product_released'];
				$productRowResult->currency 			= $resultsArrayFetch['currency'];
				$productRowResult->fully_funded 		= $resultsArrayFetch['fully_funded'];
				$productRowResult->archived 			= $resultsArrayFetch['archived'];
				$bFound = true;
				array_push($arrayResults,$productRowResult);
				
			} else
				echo("failed to locate products\n");
		    }//while
		    mysqli_free_result($res);
		} else {
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function getNowPlus (&$timeStamp, &$timeStampPlus){
		$bFound = false;
                //$sqlString = "SELECT NOW() from graffiti_transactions limit 1;";
                $sqlString = "SELECT NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH);"; 
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			printf("getNOW = [%s]\n", $sqlString);
                $res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$bFound = true;
				$timeStamp = $resultsArrayFetch['NOW()'];
				$timeStampPlus = $resultsArrayFetch['DATE_ADD(NOW(), INTERVAL 1 MONTH)'];
				if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
					echo "timeStamp now = [" . $timeStamp . "], timeStampPlus = [" . $timeStampPlus . "]\n";
			}
		    	mysqli_free_result($res);
		} else {
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function getNowPlusOneWeek(){
		$bFound = false;
                $sqlString = "SELECT DATE_ADD(NOW(), INTERVAL 1 WEEK);"; 
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			printf("getNowPlusOneWeek = [%s]\n", $sqlString);
                $res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$bFound = true;
				$timeStampPlus = $resultsArrayFetch['DATE_ADD(NOW(), INTERVAL 1 WEEK)'];
			//	if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			//		echo "timeStampPlus = [" . $timeStampPlus . "]\n";
			}
		    	mysqli_free_result($res);
			return $timeStampPlus;
		} else {
			echo("mysqli_query failed "  . $res . "\n");
			return date();
		}
		return date();
	}
	public function getNow (&$timeStamp){
		$bFound = false;
                $sqlString = "SELECT NOW();";
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			printf("getNOW = [%s]\n", $sqlString);
                $res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$bFound = true;
				$timeStamp = $resultsArrayFetch['NOW()'];
			}
		    	mysqli_free_result($res);
		} else {
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	public function getProductRowGG (&$arrayResults, $whereClause){
		$bFound = false;
                $sqlString = $this->sqlGetProducts . $whereClause;
		$increment = 0;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		   echo("sqlGetProducts = [" . $sqlString . "]\n");
                $res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			//while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_NUM))
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
//printf("resultsArrayFetch count = %d\n", count($resultsArrayFetch));
			if(count($resultsArrayFetch) > 0) 
			{
				$productRowResult = new productsInstance(NULL);
				$productRowResult->product_Id    		= $resultsArrayFetch['product_Id'];
				$productRowResult->company_Id    		= $resultsArrayFetch['company_Id'];
				$productRowResult->product_name  		= $resultsArrayFetch['product_name'];
				$productRowResult->product_Id    		= $resultsArrayFetch['product_Id'];
				$productRowResult->company_Id    		= $resultsArrayFetch['company_Id'];
				$productRowResult->product_name 		= $resultsArrayFetch['product_name'];
				$productRowResult->product_category 		= $resultsArrayFetch['product_category'];
				$productRowResult->product_link 		= $resultsArrayFetch['product_link'];
				$productRowResult->product_picture 		= $resultsArrayFetch['product_picture'];
				$productRowResult->percentage_to_share 		= $resultsArrayFetch['percentage_to_share'];
				$productRowResult->amount_to_raise 		= $resultsArrayFetch['amount_to_raise'];
				$productRowResult->amount_raised 		= $resultsArrayFetch['amount_raised'];
				$productRowResult->years_to_share_revenue 	= $resultsArrayFetch['years_to_share_revenue'];
				$productRowResult->share_life_of_product 	= $resultsArrayFetch['share_life_of_product'];
				$productRowResult->open_date 			= $resultsArrayFetch['open_date'];
				$productRowResult->close_date 			= $resultsArrayFetch['close_date'];
				$productRowResult->fully_funded_date 		= $resultsArrayFetch['fully_funded_date'];
				$productRowResult->percentage_to_kick_off_development = $resultsArrayFetch['percentage_to_kick_off_development'];
				$productRowResult->date_development_started = $resultsArrayFetch['date_development_started'];
				$productRowResult->date_product_released 	= $resultsArrayFetch['date_product_released'];
				$productRowResult->currency 			= $resultsArrayFetch['currency'];
				$productRowResult->fully_funded 		= $resultsArrayFetch['fully_funded'];
				$productRowResult->archived 			= $resultsArrayFetch['archived'];
				$bFound = true;
				array_push($arrayResults,$productRowResult);
				
			} else
				echo("failed to locate products\n");
		    }//while
		    mysqli_free_result($res);
		} else {
			echo("mysqli_query failed "  . $res . "\n");
			return false;
		}
		return true;
	}
	//returns the native array back
	public function getResourceEmailGivenLongName($longRoomName, &$tableResults){
		$sqlString = $this->sqlGetRoomInfoGivenLongDescription . "\"" . $longRoomName . "\";";
		$res = mysqli_query($this->mysqli, $sqlString);
		$bFound = false;
		if($res){
			$resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC);
			if(count($resultsArrayFetch) > 0) //if($DEBUG_DATABASE_FLAG == TRUE)
			{
				$tableResults->emailId    = $resultsArrayFetch['emailId'];
				$tableResults->resourceId = $resultsArrayFetch['resourceId'];
				$tableResults->buildingName = $resultsArrayFetch['buildingName'];
				$tableResults->floor = $resultsArrayFetch['floor'];
				$tableResults->longRoomName = $longRoomName;
				$tableResults->displayRoomName = $resultsArrayFetch['displayRoomName'];
				//printf("getResourceEmailGivenLongName %s\n", $tableResults);
				$bFound = true;
			}
			else
				echo("failed to locate room name = [" . $longRoomName . "]\n");
			mysqli_free_result($res);
		}
		return $bFound;
	}
	public function getResourceGivenEmail($emailId, &$attendee){
		$sqlString = $this->sqlGetRoomInfoGivenLongDescription . "\"" . $longRoomName . "\";";
		$res = mysqli_query($this->mysqli, $sqlString);
		$bFound = false;
		if($res){
			$resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC);
			//if($DEBUG_DATABASE_FLAG == TRUE)
			{
				$tableResults->emailId    = $resultsArrayFetch['emailId'];
				$tableResults->resourceId = $resultsArrayFetch['resourceId'];
				$tableResults->buildingName = $resultsArrayFetch['buildingName'];
				$tableResults->floor = $resultsArrayFetch['floor'];
				$tableResults->longRoomName = $longRoomName;
				$tableResults->displayRoomName = $resultsArrayFetch['displayRoomName'];
				printf("getResourceEmailGivenLongName %s\n", $tableResults);
				$bFound = true;
			}
			mysqli_free_result($res);
		}
		return $bFound;
	}
	//returns a single username given the macAddresses 
	public function getUserGivenMac($macAddress){
		$sqlString = $this->sqlGetUserGivenMac . "\"" . $macAddress . "\";";
		$res = mysqli_query($this->mysqli, $sqlString);
		$username = NULL;
		if($res){
			$macArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC);
			if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			{
				if(count($macArrayFetch) > 0){
					foreach($macArrayFetch as $macElement){
						$username = $macArrayFetch['username'];	
						//echo("DB - username = " . $username . "\n");
					}
				}else
					printf("macAddress [%s] is not registered with a username\n", $emailId);
			}
			mysqli_free_result($res);
		}
		return $username;
	}
	//this returns an array of strings containing only macAddresses not query results
	public function getMac4user($emailId){
		$sqlString = $this->sqlGetMac4user . "\"" . $emailId . "\";";
		$res = mysqli_query($this->mysqli, $sqlString);
		$macArray = array();
		if($res){
			while( $macArrayFetch = mysqli_fetch_array($res, MYSQLI_NUM)){
				$macAddress = $macArrayFetch[0];
				$macArray[] = $macAddress;
				echo("DB - macAddress = " . $macAddress . "\n");
			}
		}else
			printf("emailId [%s] is not registered with a mac address\n", $emailId);
		mysqli_free_result($res);
		return $macArray;
	}
	public function getResourceInfoReturnAttendee($emailId, &$attendee){
		$bFound = false;
		$sqlString = $this->sqlGetResourceInfo . "\"" . $emailId . "\";";
		$resourceArray = array();
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("getResourceInfo.sql = [" . $sqlString . "]\n");
		$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			$resourceArray = mysqli_fetch_array($res, MYSQLI_ASSOC);
			if( count($resourceArray) > 0){
				$bFound = true;
				$attendee->email2resourceInst->emailId 		= $resourceArray['emailId'];
				$attendee->email2resourceInst->resourceId 	= $resourceArray['resourceId'];
				$attendee->email2resourceInst->buildingName 	= $resourceArray['buildingName'];
				$attendee->email2resourceInst->floor 		= $resourceArray['floor'];
				$attendee->email2resourceInst->longRoomName 	= $resourceArray['longRoomName'];
				$attendee->email2resourceInst->displayRoomName  = $resourceArray['displayRoomName'];
//PETE
				if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE){
					$resouceId = $resourceArray['resourceId'];
					echo("resource Id = [" . $resourceId . "]\n");
					echo("building Name = [" . $buildingName . "]\n");
					echo("floor = [" . $floor . "]\n");
					echo("long Room Name = [" . $longRoomName . "]\n");
					echo("display Room Name = [" . $displayRoomName . "]\n");
				}
			}
			mysqli_free_result($res);
		}
		return $bFound;
	}
	public function getResourceInfo($emailId){
		$sqlString = $this->sqlGetResourceInfo . "\"" . $emailId . "\";";
		$resourceArray = array();
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("getResourceInfo.sql = [" . $sqlString . "]\n");
		$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			$resourceArray = mysqli_fetch_array($res, MYSQLI_ASSOC);
			if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE){
				$resouceId = $resourceArray['resourceId'];
				echo("resource Id = [" . $resourceId . "]\n");
				echo("building Name = [" . $buildingName . "]\n");
				echo("floor = [" . $floor . "]\n");
				echo("long Room Name = [" . $longRoomName . "]\n");
				echo("display Room Name = [" . $displayRoomName . "]\n");
			}
			mysqli_free_result($res);
		}
		return $resourceArray;
	}
	public function getMeetingId($meetingSubject){
		$sqlString = $this->sqlGetMeetingId . "\"" .$meetingSubject . "\";";
		$meetingId = -1;
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("getMeetingId.sql = [" . $sqlString . "]\n");
		$res = mysqli_query($this->mysqli, $sqlString);
		if($res){
			$number_of_rows = mysqli_num_rows($res);
			while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$meetingId = $newArray['meetingId'];
				if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
					echo("meetingId = " . $meetingId);
			}	
			mysqli_free_result($res);
		}
		return $meetingId;
	}
	public function createMeeting($meetingSubject, $webExId, $webExUrl, $webExPass){
		$strSql = $this->sqlCreateMeetingId . "\"". $meetingSubject . "\", "; 
		$meetingId = -1;
		if($webExId === NULL)
			$strSql .= "NULL, ";
		else
			$strSql .= "\"" . $webExId . "\", ";
		if($webExUrl === NULL)
                        $strSql .= "NULL, ";
                else    
                        $strSql .= "\"" . $webExUrl . "\", ";
		if($webExPass === NULL)
                        $strSql .= "NULL);";
                else    
                        $strSql .= "\"" . $webExPass . "\");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		echo("createMeeting.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$meetingId = mysqli_insert_id($this->mysqli);
		}
		return $meetingId;
	}
	public function trackMacUpdate($macAddress){
		$strSql = $this->sqlTrackMacUpdates . "\"". $macAddress . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("trackMac.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		$bAlreadyTracking = $res;
/*
		if($res){
			$number_of_rows = mysqli_num_rows($res);
			while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$trackStartTime = $newArray['trackStartTime'];
				if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
					echo("trackStartTime = " . $trackStartTime);
				$bAlreadyTracking = true;
			}	
			mysqli_free_result($res);
		}
*/
		return $bAlreadyTracking;
	}
	public function addMacAndUsername($macAddress, $username){
//'00:23:33:1B:F3:2E', 'pgits')
		$bInsert = false;
//PETE
		$strSql = $this->sqlRegMacAndUser . "'" . $macAddress . "', '". $username . "' );"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("sql.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
		}
		return $bInsert;
	}
	public function trackMac($macAddress){
		$bInsert = false;
		$strSql = $this->sqlTrackMac . "\"". $macAddress . "\", NOW(), NOW());"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("trackMac.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
			//mysqli_free_result($res);
		}
		return $bInsert;
	}
	//tests to see if you are already tracking this macAddress
	public function isTrackingMac($macAddress){
		$bFoundMac = false;
		$strSql = $this->sqlTrackMacExists . "\"". $macAddress . "\";"; 
		//if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("trackMac.sql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$macAddressFound = $newArray['macAddress'];
				echo("found macAddress = " . $macAddressFound . "\n");
				$bFoundMac = true;
				echo("Found Mac in database\n");
			}
			if($bFoundMac == false)
				echo("failed to locate mac in database\n");
		}
		else
			echo("failed to find Mac in database\n");
		//mysqli_free_result($res);
		return $bFoundMac;
	}
	public function addCityId($city, $state, $country, $country_abbr){
		$strSql = $this->sqlAddCityId . "\"" . $city . "\", \"" . $state . "\", \"" . $country . "\", \"" . $country_abbr . "\");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<cityId>" . $bInsert . "</cityId>");
			return true;	
		}
		return false;
	}
	public function getCityId($city, $state, $country, $country_abbr){
		$bFoundId = false;
		$strSql = $this->sqlGetCityId .  "CityName = \"" . $city . "\" AND " . " State = \"" . $state . "\" AND Country = \"" . $country . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$city_Id = $newArray['cityId'];
				echo("<cityId>" . $city_Id . "</cityId>");
				$bFoundId = true;
			}
			if($bFoundId == false)
				$bFoundId = $this->addCityId($city, $state, $country, $country_abbr);
		}
		if($bFoundId == false)
			echo("</cityId>");
	}
	public function getCityIdSimple($city, $state, $country, $country_abbr){
		$bFoundId = false;
		$strSql = $this->sqlGetCityId .  "CityName = \"" . $city . "\" AND " . "\" AND `CountryCode` = \"" . $country_abbr . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$city_Id = $newArray['cityId'];
				echo("<cityId>" . $city_Id . "</cityId>");
				$bFoundId = true;
			}
			if($bFoundId == false)
				$bFoundId = $this->addCityId($city, $state, $country, $country_abbr);
		}
		if($bFoundId == false)
			echo("</cityId>");
	}
	public function addAuthorId( $author_alias, $adGUID, $street, $city_Id, $city, $state, $zip_code, $country, $country_abbr, $gender, $age, $gps, $latitude, $longitude){
		$strSql = $this->sqlAddAuthorId . "\"" . $author_alias . "\", \"" . $adGUID . "\", \"" . $city_Id . "\", \"" . $city . "\", \"" . $state . "\", \"" . $zip_code . "\", \"" . $country . "\", \"" . $country_abbr . "\", \"" . $gender . "\", \"" . $age . "\", \"" . $gps . "\", \"" . $latitude . "\", \"" . $longitude . "\");";
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<graffiti_author_Id>" . $bInsert . "</graffiti_author_Id>\n");
			return true;	
		}
		else
			echo("</graffiti_author_Id>\n");
		return false;
	}
	public function getAuthorId($emailOrAlias, $adGUID, $street, $city_Id, $city, $state, $zip_code, $country, $country_abbr, $gender, $age, $gps, $latitude, $longitude){
		$bFoundId = false;
		$strSql = $this->sqlGetAuthorId .  "author_alias = \"" . $emailOrAlias . "\" AND " . "UDID_Vendor = \"" . $adGUID . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				$graffiti_author_Id = $newArray['graffiti_author_Id'];
				echo("<graffiti_author_Id>" . $graffiti_author_Id . "</graffiti_author_Id>\n");
				$bFoundId = true;
				return true;
			}
			if($bFoundId == false)
				$bFoundId =  $this->addAuthorId($emailOrAlias, $adGUID, $street, $city_Id, $city, $state, $zip_code, $country, $country_abbr, $gender, $age, $gps, $latitude, $longitude);
		}
		if($bFoundId == false)
			echo("</graffiti_author_Id>");
	}
	public function iGetNumberOfLikes($graffiti_Id, $city_Id, $graffiti_author_Id){
		$strSql = $this->sqlGetLikesStr . " AND `liked` = 1 AND `graffiti_Id` = " . "\"" . $graffiti_Id . "\" AND `city_Id` =  \"" . $city_Id . "\";"; 
//check to see the number of likes within the last 30 days
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
                        while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                                $totalLikesWithin30Days = $newArray['COUNT(*)'];
                                echo("<likes>" . $totalLikesWithin30Days . "</likes>\n");
                                return true;
                        }
                }
		else
			echo("<likes>0</likes>\n");
		return false;
	}
	public function iLikedItWithin30days($graffiti_Id, $city_Id, $graffiti_author_Id){
		$strSql = $this->sqlGetLikesStr . " AND `liked` = 1 AND `viewer_Id` = " . "\"" . $graffiti_author_Id . "\" AND `graffiti_Id` = " . "\"" . $graffiti_Id . "\" AND `city_Id` =  \"" . $city_Id . "\";"; 
//check to see the number of likes within the last 30 days
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
                        while($newArray = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                                $totalLikesWithin30Days = $newArray['COUNT(*)'];
                                echo("<likes>" . $totalLikesWithin30Days . "</likes>\n");
                                return true;
                        }
                }
		else
			echo("<likes>0</likes>\n");
		return false;
	}
	public function iLikedIt( $graffiti_Id, $city_Id, $graffiti_author_Id){
		$strSql = $this->sqlILikedItStr . "\"" . $graffiti_Id . "\", \"" . $city_Id . "\", \"" . $graffiti_author_Id . "\");"; 
//check to see if you have already liked this graffiti_Id within the last 30 days
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("<liked>SUCCESS</liked>\n");
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<transaction_Id>" . $bInsert . "</transaction_Id>\n");
			return true;	
		}
		else
			echo("<liked>FAIL</liked>\n");
		return false;
	}
	public function iReportedIt( $graffiti_Id, $city_Id, $graffiti_author_Id){
		$strSql = $this->sqlIReportedItStr . "\"" . $graffiti_Id . "\", \"" . $city_Id . "\", \"" . $graffiti_author_Id . "\");"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("<reported>SUCCESS</reported>\n");
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<transaction_Id>" . $bInsert . "</transaction_Id>\n");
			return true;	
		}
		else
			echo("<reported>FAIL</reported>\n");
		return false;
	}
	public function updateComment( $graffiti_Id, $city_Id, $graffiti_author_Id, $comment){
		//$this->sqlUpdateCommentStr = "UPDATE " .  "`" . $database . "`" .  "." .  $this->graffitiMessageTable . " SET message = "; //comment where graffiti_Id = AND city_Id = 
		$strSql = $this->sqlUpdateCommentStr . "\"" . $comment . "\" WHERE graffiti_Id = \"" . $graffiti_Id . "\" AND city_Id = \"" . $city_Id . "\" AND graffiti_author_Id = \"" . $graffiti_author_Id . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("<updateComment>SUCCESS</updateComment>\n");
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<transaction_Id>" . $bInsert . "</transaction_Id>\n");
			return true;	
		}
		else
			echo("<updateComment>FAIL</updateComment>\n");
		return false;
	}
	public function isUserBanished( $city_Id, $author_Id, $email){
		$strSql = $this->sqlBanishedStr . " `removed` = 1 AND `city_Id` = " . $city_Id . " AND `graffiti_author_Id` = " .  $author_Id . ";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			if(mysqli_num_rows($res) > 0){
				echo("<userBanished>TRUE</userBanished>\n");
				$bInsert = mysqli_insert_id($this->mysqli);
				return true;	
			}
			else {
				echo("<userBanished>FALSE</userBanished>\n");
				if($this->isEmailTheSame($author_Id, $email) == FALSE)
					$this->updateEmailAddress($author_Id, $email);
			}
		}
		return false;
	}
	public function isEmailTheSame( $author_Id, $email){
		$strSql = $this->sqlEmailSameStr . " `author_alias` = \"" . $email . "\" AND `graffiti_author_Id` = " .  $author_Id . ";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			if(mysqli_num_rows($res) > 0){
				echo("<emailSame>TRUE</emailSame>\n");
				return true;	
			}
			else
				echo("<emailSame>FALSE</emailSame>\n");
		}
		return false;
	}
	public function updateEmailAddress( $author_Id, $email){
		$strSql = $this->sqlEmailUpdateStr . " `author_alias` = \"" . $email . "\" WHERE `graffiti_author_Id` = " .  $author_Id . ";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("number of affected rows = " . mysqli_affected_rows ( $this->mysqli ));
			if(mysqli_affected_rows ( $this->mysqli ) > 0){
				echo("<updateEmail>SUCCESS</updateEmail>\n");
				return true;	
			}
			else
				echo("<updateEmail>FAIL</updateEmail>\n");
		}
		return false;
	}
	public function addComment( $graffiti_Id, $city_Id, $review_author_Id, $comment){
		$strSql = $this->sqlAddCommentStr . "\"" . $graffiti_Id . "\", \"" . $city_Id . "\", \"" . $review_author_Id . "\", \"" . $comment . "\");"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("<addComment>SUCCESS</addComment>\n");
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<transaction_Id>" . $bInsert . "</transaction_Id>\n");
			return true;	
		}
		else
			echo("<addComment>FAIL</addComment>\n");
		return false;
	}
	public function getNextSwiggen( $graffiti_Id, $store_Id, &$next_swiggen, &$OneWeekOut){
		$strSql = $this->sqlIncrementNextSwiggenStr . " `graffiti_Id` =\"" . $graffiti_Id . "\" AND store_Id = \"" . $store_Id . "\"" . ";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
		    	mysqli_free_result($res);
			$strSql = $this->sqlGetNextSwiggenStr . " `graffiti_Id` =\"" . $graffiti_Id . "\" AND store_Id = \"" . $store_Id . "\"" . ";"; 
			if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
				echo("strSql = [" . $strSql . "]\n");
			$res = mysqli_query($this->mysqli, $strSql);
			if($res)
				while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
					if(count($resultsArrayFetch) > 0) 
					{
					$next_swiggen = $resultsArrayFetch["next_swiggen_number"];
		    			mysqli_free_result($res);
					$OneWeekOut = $this->getNowPlusOneWeek();
					//return $resultsArrayFetch["next_swiggen_number"];
					}
				}
			return true;
		}
		return false;
	}
	public function isSwiggenAlreadyReclaimed( $graffiti_Id, $store_Id, $swiggen_number, $swiggen_descr){
		$strSql = $this->sqlIsSwiggenReclaimedStr . "`qr_swiggen_scanned` = \"1\" AND  `graffiti_Id` =\"" . $graffiti_Id . "\" AND store_Id = \"" . $store_Id . "\" AND swiggen_number = \"" . $swiggen_number . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) {
					echo("<html>\n");
					echo("<head>\n");
					echo("<title> Kilroy Was Here Swiggen Discount </title>\n");
					echo("</head>\n");
					//echo("<meta http-equiv="Content-Type" content="text/html; charset=utf-8">\n");
					//echo("<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width">\n");
					echo("<body>\n");
					echo("<reclaimSwiggen>FAIL</reclaimSwiggen>\n");
					echo("<p>This Swiggen has already be reclaimed</p>\n");
					echo("</body>\n");
					echo("</html>\n");
					return true;
				}
				return false;
			}
		}
		return false;
	}
	public function reclaimSwiggen( $graffiti_Id, $review_author_Id, $store_Id, $swiggen_number, $swiggen_descr){
		if($graffiti_Id == 0)
			;
		else if($this->isSwiggenAlreadyReclaimed( $graffiti_Id, $store_Id, $swiggen_number, $swiggen_descr) == TRUE)
			return FALSE;
		$strSql = $this->sqlReclaimSwiggenStr . "\"" . $graffiti_Id . "\", \"" . $review_author_Id . "\", \"" . $store_Id . "\", \"" . $swiggen_number . "\", \"" . $swiggen_descr . "\", 1);"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("<html>\n");
			echo("<head>\n");
			echo("<title> Kilroy Was Here Swiggen Discount approved</title>\n");
			echo("</head>\n");
			//echo("<meta http-equiv="Content-Type" content="text/html; charset=utf-8">\n");
			//echo("<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width">\n");
			echo("<body>\n");
			echo("<p>Reclaim Swiggen = SUCCESS</p>\n");
			if($store_Id == 0)
				echo("<p>$50 off setup fee, normally $250, now $200</p>");
			$bInsert = mysqli_insert_id($this->mysqli);
			echo("<p>transaction_Id = " . $bInsert . "</p>\n");
			echo("</body>\n");
			echo("</html>\n");
			return true;	
		}
		else {
			echo("<html>\n");
			echo("<head>\n");
			echo("<title> Kilroy Was Here Swiggen Discount </title>\n");
			echo("</head>\n");
			//echo("<meta http-equiv="Content-Type" content="text/html; charset=utf-8">\n");
			//echo("<meta name="viewport" id="iphone-viewport" content="minimum-scale=1.0, maximum-scale=1.0, width=device-width">\n");
			echo("<body>\n");
			echo("<reclaimSwiggen>FAIL</reclaimSwiggen>\n");
			echo("</body>\n");
			echo("</html>\n");
		}
		return false;
	}
//returns JSON formatted
	public function getCommentsBugIn5thElement( $graffiti_Id, $city_Id ){
		//$strSql = $this->sqlGetCommentStr . " t1.graffiti_Id = \"" . $graffiti_Id . "\" AND t1.city_Id = \"" . $city_Id . "\" ORDER BY t1.datestamp ASC;"; //order by datestamp
		$strSql = $this->sqlGetCommentStr . " t1.graffiti_Id = \"" . $graffiti_Id . "\" AND t1.city_Id = \"" . $city_Id . "\" ORDER BY t1.datestamp DESC;"; //order by datestamp
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			//echo("{\n\"comments\":[\n");
			//echo("{\n\"comments\":[\n");
			$incr = 2;
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
				if($incr == 2)
				  echo("[\n");
				echo("{");
				echo("\"author_alias\": \"" 		. $resultsArrayFetch["author_alias"] . "\",");
				echo("\"datestamp\": \"" 		. $resultsArrayFetch["datestamp"] . "\",");
				$tcomment = $resultsArrayFetch["comment"];
				if($tcomment != NULL)
					echo("\"comment\": \"" 		. $tcomment . "\"");
				$voiceLink = $resultsArrayFetch["voice_comment_link"];
				if($voiceLink != NULL)
					echo(", \"voice_comment_link\": \"" 	. $voiceLink . "\"");
				$videoLink = $resultsArrayFetch["video_comment_link"];
				if($videoLink  != NULL)
					echo(", \"video_comment_link\": \"" 	. $videoLink . "\"");
				$incr += 1;
				if($incr >= count($resultsArrayFetch) ){
					//printf("}\n]\n incr = %d, resultsArrayFetch-count = %d\n", $incr, count($resultsArrayFetch));
					echo("}\n");
				}
				else {
					//printf("}\n]\n incr = %d, resultsArrayFetch-count = %d\n", $incr, count($resultsArrayFetch));
					echo("},\n");
					
				}
			    } //end of if
			}//end of while
			if($incr > 2)
				echo("]\n");
			else
				echo("<comments>EMPTY</comments>\n");
			//echo("]\n}\n");
		}
		else
			echo("<comments>EMPTY</comments>\n");
		return false;
	}
//returns JSON formatted
	public function getComments( $graffiti_Id, $city_Id ){
		//$strSql = $this->sqlGetCommentStr . " t1.graffiti_Id = \"" . $graffiti_Id . "\" AND t1.city_Id = \"" . $city_Id . "\" ORDER BY t1.datestamp ASC;"; //order by datestamp
		$strSql = $this->sqlGetCommentStr . " t1.graffiti_Id = \"" . $graffiti_Id . "\" AND t1.city_Id = \"" . $city_Id . "\" ORDER BY t1.datestamp DESC;"; //order by datestamp
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			//echo("{\n\"comments\":[\n");
			//echo("{\n\"comments\":[\n");
			$incr = 0;
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
					if($incr == 0){
				  		echo("[\n");
				  		$incr++;
					}else {
				  		echo(",\n");
					}
					echo("{");
					echo("\"author_alias\": \"" 		. $resultsArrayFetch["author_alias"] . "\",");
					echo("\"datestamp\": \"" 		. $resultsArrayFetch["datestamp"] . "\",");
					$tcomment = $resultsArrayFetch["comment"];
					if($tcomment != NULL)
						echo("\"comment\": \"" 		. $tcomment . "\"");
					$voiceLink = $resultsArrayFetch["voice_comment_link"];
					if($voiceLink != NULL)
						echo(", \"voice_comment_link\": \"" 	. $voiceLink . "\"");
					$videoLink = $resultsArrayFetch["video_comment_link"];
					if($videoLink  != NULL)
						echo(", \"video_comment_link\": \"" 	. $videoLink . "\"");
					echo("}");
				}
			}//end of while
			if($incr > 0)
				echo("\n]\n");
			else
				echo("<comments>EMPTY</comments>\n");
		}
		else
			echo("<comments>EMPTY</comments>\n");
		return false;
	}
	public function getCommentsXml( $graffiti_Id, $city_Id ){
		$strSql = $this->sqlGetCommentStr . " t1.graffiti_Id = \"" . $graffiti_Id . "\" AND t1.city_Id = \"" . $city_Id . "\";"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			echo("<comments>\n");
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
				echo("<comment>\n");
				echo("<author_alias>" 		. $resultsArrayFetch["author_alias"] . "</author_alias>\n");
				echo("<datestamp>" 		. $resultsArrayFetch["datestamp"] . "</datestamp>\n");
				$tcomment = $resultsArrayFetch["comment"];
				if($tcomment != NULL)
					echo("<comment>" 		. $tcomment . "</comment>\n");
				$voiceLink = $resultsArrayFetch["voice_comment_link"];
				if($voiceLink != NULL)
					echo("<voice_comment_link>" 	. $voiceLink . "</voice_comment_link>\n");
				$videoLink = $resultsArrayFetch["video_comment_link"];
				if($videoLink  != NULL)
					echo("<video_comment_link>" 	. $videoLink . "</video_comment_link>\n");
				echo("</comment>\n");
				}
			}
			echo("</comments>\n");
		}
		else
			echo("<comments>FAIL</comments>\n");
		return false;
	}
	public function getComment( $graffiti_Id, $city_Id ){
		//$this->sqlGetTheCommentStr = "SELECT message FROM " . $database . "." . $this->graffitiMessageTable . " WHERE ";
		$strSql = $this->sqlGetTheCommentStr . " graffiti_Id = \"" . $graffiti_Id . "\" AND city_Id = \"" . $city_Id . "\" ;"; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
			while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
				if(count($resultsArrayFetch) > 0) 
				{
				echo("<message>" . $resultsArrayFetch["message"] . "</message>");
				}
			}
		}
		else
			echo("</message>\n");
		return false;
	}
	public function removeTrackMac($macAddress){
		$bRemovedMac = false;
		//$this->removeTrackMac = "DELETE FROM `netlurkers`.`macTracker` WHERE `macTracker`.`macAddress` = ";
		$strSql = $this->removeTrackMac . "\"". $macAddress . "\""; 
		if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			echo("removeTrackMac.sql = [" . $strSql . "\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res)
			$bRemovedMac = true;
		//mysqli_free_result($res);
		return $bRemovedMac;
	}
}
class whereAmiInstance {
	var $macAddress;
	var $ipAddress;
	var $x_location;
	var $y_location;
	var $z_location;
	var $graffiti_author_id;
	var $started_watching;
	var $last_mobile_request_time;
	var $last_mse_refresh_time;
  	var $city_Id;
	var $building_Id;
	var $building_floor;
	var $pid;
	var $sql_now; //this is not part of the normal database record, just used to retrieve extra current information
	var $latitude;
	var $longitude;

	public function __construct(){
	}

	public function __toString(){
		$outString =  "<whereAmI>\n";
		if($this->macAddress != NULL)
	        	$outString .= "\t <macAddress>".  			$this->macAddress  . "</macAddress>\n";
		if($this->ipAddress != NULL)
	        	$outString .= "\t <ipAddress>".  		$this->ipAddress  . "</ipAddress>\n";
		if($this->x_location != NULL)
	        	$outString .= "\t <x_location>" . 			$this->x_location . "</x_location>\n";
		if($this->y_location != NULL)
	        	$outString .= "\t <y_location>" . 	 		$this->y_location . "</y_location>\n";
		if($this->z_location != NULL)
	        	$outString .= "\t <z_location>" . 	 		$this->z_location . "</z_location>\n";
		if(isset($this->graffiti_author_Id))
			if($this->graffiti_author_Id != NULL)
	        		$outString .= "\t <graffiti_author_Id>". $this->graffiti_author_Id  . "</graffiti_author_Id>\n";
		if($this->started_watching != NULL)
	        $outString .= "\t <started_watching>" .  		$this->started_watching  . "</started_watching>\n";
		if($this->last_mobile_request_time != NULL)
	        $outString .= "\t <last_mobile_request_time>" . 	$this->last_mobile_request_time . "</last_mobile_request_time>\n";
		if($this->last_mse_refresh_time != NULL)
	        $outString .= "\t <last_mse_refresh_time>" . 		$this->mse_refresh_time . "</mse_refresh_time>\n";
		if($this->city_Id != NULL)
		$outString .= " <city_Id>" . 	 			$this->city_Id. "</city_Id>\n";
		if($this->building_Id != NULL)
		$outString .= " <building_Id>" . 	 		$this->building_Id. "</building_Id>\n";
		if($this->building_floor != NULL)
		$outString .= " <building_floor>" . 	 		$this->building_floor. "</building_floor>\n";
		if(isset($this->now))
			if($this->now != NULL)
				$outString .= " <now>" . 	$this->sql_now . "</now>\n";
		if($this->pid != NULL)
			$outString .= " <pid>" .				$this->pid . "</pid>\n";
		if($this->latitude != NULL)
			$outString .= " <latitude>" .				$this->latitude . "</latitude>\n";
		if($this->longitude != NULL)
			$outString .= " <longitude>" .				$this->longitude . "</longitude>\n";
		$outString .= " </whereAmI>\n";
		return $outString;
	}
}
class whereAmiInstanceArchived {
	var $macAddress;
	var $ipAddress;
	var $x_location;
	var $y_location;
	var $z_location;
	var $graffiti_author_id;
	var $started_watching;
	var $last_mobile_request_time;
	var $last_mse_refresh_time;
  	var $city_Id;
	var $building_Id;
	var $building_floor;
	var $pid;
	var $sql_now; //this is not part of the normal database record, just used to retrieve extra current information

	public function __construct(){
	}

	public function __toString(){
		$outString =  "<whereAmIArchived>\n";
	        $outString .= "\t <macAddress>".  			$this->macAddress  . "</macAddress>\n";
	        $outString .= "\t <x_location>" . 			$this->x_location . "</x_location>\n";
	        $outString .= "\t <y_location>" . 	 		$this->y_location . "</y_location>\n";
	        $outString .= "\t <z_location>" . 	 		$this->z_location . "</z_location>\n";
		if($this->graffiti_author_Id != NULL)
	        $outString .= "\t <graffiti_author_Id>".  		$this->graffiti_author_Id  . "</graffiti_author_Id>\n";
		if($this->started_watching != NULL)
	        $outString .= "\t <started_watching>" .  		$this->started_watching  . "</started_watching>\n";
		if($this->last_mobile_request_time != NULL)
	        $outString .= "\t <last_mobile_request_time>" . 	$this->last_mobile_request_time . "</last_mobile_request_time>\n";
		if($this->last_mse_refresh_time != NULL)
	        $outString .= "\t <last_mse_refresh_time>" . 		$this->mse_refresh_time . "</mse_refresh_time>\n";
		if($this->city_Id != NULL)
		$outString .= " <city_Id>" . 	 			$this->city_Id. "</city_Id>\n";
		if($this->building_Id != NULL)
		$outString .= " <building_Id>" . 	 		$this->building_Id. "</building_Id>\n";
		if($this->building_floor != NULL)
		$outString .= " <building_floor>" . 	 		$this->building_floor. "</building_floor>\n";
		if($this->now != NULL)
		$outString .= " <now>" . 	$this->sql_now . "</now>\n";
		if($this->pid != NULL)
		$outString .= " <pid>" .				$this->pid . "</pid>\n";
		$outString .= " </whereAmIArchived>\n";
		return $outString;
	}
}
class graffitiMessageInstance {
  	var $city_Id;
  	var $building_Id;
  	var $building_floor;
  	var $graffiti_Id;
	var $envelope_Id;
  	var $graffiti_author_Id;
  	var $graffiti_author_alias;
  	var $country_code;
  	var $created_datetime;
  	var $expires_datetime;
  	var $to;
  	var $message;
  	var $message_font;
  	var $message_size;
  	var $icon_invisible;
  	var $icon_visible_distance;
  	var $icon_name;
  	var $x_coordinate;
  	var $y_coordinate;
  	var $z_coordinate;
  	var $message_visible_distance;
  	var $return_receipt_requested;
  	var $notify_recipients;
  	var $message_archived;
  	var $encrypted_password_index;
  	var $version_left_of_period;
  	var $version_right_of_period;

	public function __construct(){
	}

	public function __toString(){
		$outString =  "<graffitiMessage>\n";
	        $outString .= "\t <graffiti_Id>" .  		$this->graffiti_Id . 			"</graffiti_Id>\n";
	        $outString .= "\t <envelope_Id>" .  		$this->envelope_Id . 			"</envelope_Id>\n";
		if($this->graffiti_author_Id != NULL)
	        $outString .= "\t <graffiti_author_Id>".     	$this->graffiti_author_Id  . 		"</graffiti_author_Id>\n";
		if($this->graffiti_author_alias != NULL)
	        $outString .= "\t <graffiti_author_alias>".     		$this->graffiti_author_alias  . 			"</graffiti_author_alias>\n";
		if($this->to != NULL)
	        $outString .= "\t <to>" .  			$this->to . 				"</to>\n";
	        $outString .= "\t <message>" . 			$this->message . 			"</message>\n";
		if($this->message_font != NULL)
	        $outString .= "\t <message_font>" . 		$this->message_font . 			"</message_font>\n";
		if($this->message_size != NULL)
	        $outString .= "\t <message_size>" . 	 	$this->message_size . 			"</message_size>\n";
		if($this->icon_invisible != NULL)
	        $outString .= "\t <icon_invisible>" . 		$this->icon_invisible . 		"</icon_invisible>\n";
		if($this->icon_visible_distance != NULL)
	        $outString .= "\t <icon_visible_distance>" . 	$this->icon_visible_distance . 		"</icon_visible_distance>\n";
		if($this->icon_name != NULL)
	        $outString .= "\t <icon_name>" . 		$this->icon_name . 			"</icon_name>\n";
	        $outString .= "\t <x_coordinate>" . 		$this->x_coordinate . 			"</x_coordinate>\n";
	        $outString .= "\t <y_coordinate>" . 	 	$this->y_coordinate . 			"</y_coordinate>\n";
	        $outString .= "\t <z_coordinate>" . 	 	$this->z_coordinate . 			"</z_coordinate>\n";
	        $outString .= "\t <message_visible_distance>" .	$this->message_visible_distance . 	"</message_visible_distance>\n";
		if($this->country_code != NULL)
	        $outString .= "\t <country_code>" . 		$this->country_code  . 			"</country_code>\n";
		if($this->return_receipt_requested != NULL)
	        $outString .= "\t <return_receipt_requested>" .	$this->return_receipt_requested . 	"</return_receipt_requested>\n";
		if($this->notify_recipients != NULL)
	        $outString .= "\t <notify_recipients>" . 	$this->notify_recipients . 		"</notify_recipients>\n";
		if($this->message_archived != NULL)
	        $outString .= "\t <message_archived>" . 	$this->message_archived . 		"</message_archived>\n";
		if($this->encrypted_password_index != NULL)
	        $outString .= "\t <encrypted_password_index>" .	$this->encrypted_password_index . 	"</encrypted_password_index>\n";
		if($this->created_datetime != NULL)
	        $outString .= "\t <created_datetime>" .  	$this->created_datetime  . 		"</created_datetime>\n";
		if($this->expires_datetime != NULL)
	        $outString .= "\t <expires_datetime>" . 	$this->expires_datetime . 		"</expires_datetime>\n";
		if($this->version_left_of_period != NULL)
	        $outString .= "\t <version_left_of_period>" . 	$this->version_left_of_period . 	"</version_left_of_period>\n";
		if($this->version_right_of_period != NULL)
	        $outString .= "\t <version_right_of_period>" .	$this->version_right_of_period . 	"</version_right_of_period>\n";
		if($this->city_Id != NULL)
		$outString .= "\t <city_Id>" . 	 		$this->city_Id. 			"</city_Id>\n";
		if($this->building_Id != NULL)
	        $outString .= "\t <building_Id>" . 		$this->building_Id . 			"</building_Id>\n";
		if($this->building_floor != NULL)
                $outString .= "\t <building_floor>" . 		$this->building_floor . 		"</building_floor>\n";
		$outString .= "</graffitiMessage>\n";
		return $outString;
	}
}
class graffitiMessageUrlInstance {
	var $dms_transaction;//unique id
  	var $graffiti_Id;
  	var $city_Id;
	var $website_address_loop;//varchar(200)
	var $pause_on_look_away;//tinyint(1)bool
	var $next_web_push_delay;//tinyint(1)
	var $gender;
	var $age;
	var $loop_vendor; //varchar(50)
	var $audit_trail;//tinyInt

	public function __construct(){
	}

	public function __toString(){
		$outString =  "<Vdms_graffitiMessageUrl>\n";
	        $outString .= "\t <Vdms_dms_transaction>" .  		$this->dms_transaction . 		"</Vdms_dms_transaction>\n";
	        $outString .= "\t <Vdms_graffiti_Id>" .  		$this->graffiti_Id . 			"</Vdms_graffiti_Id>\n";
	        $outString .= "\t <Vdms_city_Id>" .  			$this->city_Id . 			"</Vdms_city_Id>\n";
		if($this->website_address_loop != NULL)
	        	$outString .= "\t <Vdms_website_address_loop>". $this->website_address_loop  . 		"</Vdms_website_address_loop>\n";
	        $outString .= "\t <Vdms_pause_on_look_away>" .  	$this->pause_on_look_away . 		"</Vdms_pause_on_look_away>\n";
	        $outString .= "\t <Vdms_next_web_push_delay>" . 	$this->next_web_push_delay . 		"</Vdms_next_web_push_delay>\n";
		if($this->gender != NULL)
	        	$outString .= "\t <Vdms_gender>".     		$this->gender  . 			"</Vdms_gender>\n";
	        $outString .= "\t <Vdms_age>".     			$this->age  . 				"</Vdms_age>\n";
		if($this->loop_vendor != NULL)
	        	$outString .= "\t <Vdms_loop_vendor>" . 	$this->loop_vendor . 			"</Vdms_loop_vendor>\n";
		if($this->audit_trail != NULL)
	        	$outString .= "\t <Vdms_audit_trail>" . 	 $this->audit_trail . 			"</Vdms_audit_trail>\n";
		$outString .= "</Vdms_graffitiMessageUrl>\n";
		return $outString;
	}
}
class graffitiCouponUrlInstance {
  	var $graffiti_Id;
  	var $city_Id;
	var $business_name;
	var $coupon_url;
	var $percentage_under_60;
	var $percentage_under_120;
	var $percentage_over_120;
	var $amount_off_under_60;
	var $amount_off_under_120;
	var $amount_off_over_120;
	var $currency_symbol;
	var $expires;
	var $coupon_limit;
	var $downloaded_counter;
	var $scanned_counter_under_60;
	var $scanned_counter_under_120;
	var $scanned_counter_under_time_limit;
	var $scanned_counter_over_time_limit;
	var $latitude;
	var $longitude;
	var $nearest_store_Id;
	var $walking;
	var $outString;

	public function __construct(){
	}

	public function __toString(){
		$outString = NULL;
	        $outString .= "\t <graffiti_Id>" .  		$this->graffiti_Id . 			"</graffiti_Id>\n";
	        $outString .= "\t <city_Id>" .  		$this->city_Id . 			"</city_Id>\n";
		if($this->business_name != NULL)
	        	$outString .= "\t <business_name>". 	$this->business_name  . 		"</business_name>\n";
		if($this->coupon_url != NULL)
	        	$outString .= "\t <coupon_url>". 	$this->coupon_url  . 			"</coupon_url>\n";
		if($this->percentage_under_60 != NULL)
	        	$outString .= "\t <percentage_under_60>". $this->percentage_under_60  . 	"</percentage_under_60>\n";
		if($this->percentage_under_120 != NULL)
	        	$outString .= "\t <percentage_under_120>". $this->percentage_under_120  . 	"</percentage_under_120>\n";
		if($this->percentage_over_120 != NULL)
	        	$outString .= "\t <percentage_over_120>". $this->percentage_over_120  . 	"</percentage_over_120>\n";
		if($this->amount_off_under_60 != NULL)
	        	$outString .= "\t <amount_off_under_60>". $this->amount_off_under_60  . 	"</amount_off_under_60>\n";
		if($this->amount_off_under_120 != NULL)
	        	$outString .= "\t <amount_off_under_120>". $this->amount_off_under_120  . 	"</amount_off_under_120>\n";
		if($this->amount_off_over_120 != NULL)
	        	$outString .= "\t <amount_off_over_120>". $this->amount_off_over_120  . 	"</amount_off_over_120>\n";
		if($this->currency_symbol != NULL)
	        	$outString .= "\t <currency_symbol>".    $this->currency_symbol  . 		"</currency_symbol>\n";
		if($this->expires != NULL)
	        	$outString .= "\t <expires>". 		  $this->expires  . 			"</expires>\n";
		if($this->coupon_limit != NULL)
	        	$outString .= "\t <coupon_limit>". 	  $this->coupon_limit  . 		"</coupon_limit>\n";
		if($this->downloaded_counter != NULL)
	        	$outString .= "\t <downloaded_counter>".   $this->downloaded_counter  . 	"</downloaded_counter>\n";
		if($this->scanned_counter_under_60 != NULL)
	        	$outString .= "\t <scanned_counter_under_60>". $this->scanned_counter_under_60  . "</scanned_counter_under_60>\n";
		if($this->scanned_counter_under_120 != NULL)
	        	$outString .= "\t <scanned_counter_under_120>". $this->scanned_counter_under_120  . "</scanned_counter_under_120>\n";
		if($this->scanned_counter_under_time_limit != NULL)
	        	$outString .= "\t <scanned_counter_under_time_limit>". $this->scanned_counter_under_time_limit  . "</scanned_counter_under_time_limit>\n";
		if($this->scanned_counter_over_time_limit != NULL)
	        	$outString .= "\t <scanned_counter_over_time_limit>". $this->scanned_counter_over_time_limit  . "</scanned_counter_over_time_limit>\n";
		if($this->latitude != NULL)
	        	$outString .= "\t <latitude>". 			$this->latitude  . 		"</latitude>\n";
		if($this->longitude != NULL)
	        	$outString .= "\t <longitude>". 		$this->longitude  . 		"</longitude>\n";
	        $outString .= "\t <nearest_store_Id>". 			$this->nearest_store_Id  . 	"</nearest_store_Id>\n";
	        $outString .= "\t <walking>". 				$this->walking  . 		"</walking>\n";
		return $outString;
	}
}
class storeDetailsInstance {
  	var $store_Id;
  	var $store_name;
	var $store_address1;
	var $store_address2;
	var $city;
	var $state_province;
	var $zip_code;
	var $country;
	var $phone_number;
	var $latitude;
	var $longitude;
	var $website_url;
	var $business_Id;

	var $outString;

	public function __construct(){
	}

	public function __toString(){
		$outString = NULL;
	        $outString .= "\t <store_Id>" .  		$this->store_Id . 			"</store_Id>\n";
		if($this->store_name != NULL)
	        $outString .= "\t <store_name>" .  		$this->store_name . 			"</store_name>\n";
		if($this->store_address1 != NULL)
	        	$outString .= "\t <store_address1>". 	$this->store_address1  . 		"</store_address1>\n";
		if($this->store_address2 != NULL)
	        	$outString .= "\t <store_address2>". 	$this->store_address2  . 			"</store_address2>\n";
		if($this->city != NULL)
	        	$outString .= "\t <city>". $this->city  . 	"</city>\n";
		if($this->state_province != NULL)
	        	$outString .= "\t <state_province>". $this->state_province  . 	"</state_province>\n";
		if($this->zip_code != NULL)
	        	$outString .= "\t <zip_code>". $this->zip_code  . 	"</zip_code>\n";
		if($this->country != NULL)
	        	$outString .= "\t <country>". $this->country  . 	"</country>\n";
		if($this->phone_number != NULL)
	        	$outString .= "\t <phone_number>". $this->phone_number  . 	"</phone_number>\n";
		if($this->latitude != NULL)
	        	$outString .= "\t <latitude>". $this->latitude  . 	"</latitude>\n";
		if($this->longitude != NULL)
	        	$outString .= "\t <longitude>".    $this->longitude  . 		"</longitude>\n";
		if($this->website_url != NULL)
	        	$outString .= "\t <website_url>". 		  $this->website_url  . 			"</website_url>\n";
		if($this->business_Id != NULL)
	        	$outString .= "\t <business_Id>". 	  $this->business_Id  . 		"</business_Id>\n";
		return $outString;
	}
}
class productsInstance {
	var $product_Id;
	var $company_Id;
	var $product_name;
	var $product_category;
	var $product_link;
	var $product_picture;
	var $percentage_to_share;
	var $amount_to_raise;
	var $amount_raised;
	var $years_to_share_revenue;
	var $share_life_of_product;
	var $open_date;
	var $close_date;
	var $fully_funded_date;
	var $percentage_to_kick_off_development;
	var $date_development_started;
	var $date_product_released;
	var $currency;
	var $fully_funded;
	var $archived;
	var $outString;

	public function __construct(){
	}

	public function __toString(){
		$outString =  "Class products = {\n";
		$outString .= " product_Id = [" . 	 $this->product_Id . "]\n";
	        $outString .= "\t company_Id = [" . 	 $this->company_Id . "]\n";
                $outString .= "\t product_name = [" . 	 $this->product_name . "]\n";
	        $outString .= "\t product_category = [" .  $this->product_category . "]\n";
	        $outString .= "\t product_link = [" 	.  $this->product_link  . "]\n";
	        $outString .= "\t product_picture = [" 	.  $this->product_picture  . "]\n";
	        $outString .= "\t percentage_to_share = [" . $this->percentage_to_share . "]\n";
	        $outString .= "\t amount_to_raise = [" .  $this->amount_to_raise . "]\n";
	        $outString .= "\t amount_raised   = [" .  $this->amount_raised . "]\n";
	        $outString .= "\t years_to_share_revenue = [" . $this->years_to_share_revenue . "]\n";
	        $outString .= "\t share_life_of_product = [" . $this->share_life_of_product . "]\n";
	        $outString .= "\t open_date = [" . 	 $this->open_date . "]\n";
	        $outString .= "\t close_date = [" . 	 $this->close_date . "]\n";
	        $outString .= "\t fully_funded_date = [" . $this->fully_funded_date . "]\n";
	        $outString .= "\t percentage_to_kick_off_development = [" . $this->percentage_to_kick_off_development . "]\n";
	        $outString .= "\t date_development_started = [" . $this->date_development_started . "]\n";
	        $outString .= "\t date_product_released = [" . 	 $this->date_product_released . "]\n";
	        $outString .= "\t currency = [" . 	 $this->currency . "]\n";
	        $outString .= "\t fully_funded = [" . 	 $this->fully_funded . "]\n";
	        $outString .= "\t archived = [" . 	 $this->archived . "]\n";
		return $outString;
	}
}
/* this test inserts a new row for the meeting id and subject... may need to have a transaction eventually */
function unitTestDbGetMeeting($sqlInstance, $subject){
	echo("getMeetingId = [" . $sqlInstance->getMeetingId($subject) . "]\n");
}
function DbGetOrCreateMeeting($sqlInstance, $subject){
	$returnValue = $sqlInstance->getMeetingId($subject);
	if($returnValue === -1){
		$meetingId = $sqlInstance->createMeeting($subject, NULL, NULL, NULL);
		echo("createMeeting = [" . $meetingId . "]\n");
		return $meetingId;
	}
	return $returnValue;
}
function unitTestDbGetResourceInfo($sqlInstance, $emailId){
	$resourceRow = $sqlInstance->getResourceInfo($emailId);	
	echo("resourceId = [" . $resourceRow['resourceId'] . "]\n");
	echo("buildingName = [" . $resourceRow['buildingName'] . "]\n");
	echo("floor = [" . $resourceRow['floor'] . "]\n");
	echo("longRoomName = [" . $resourceRow['longRoomName'] . "]\n");
	echo("displayRoomName = [" . $resourceRow['displayRoomName'] . "]\n");
}
function unitTestDB3(){
	$sqlInstance = new SqlUtils("xen-1.cisco.com", "root", "", "netlurkers");
	//unitTestDbGetMeeting($sqlInstance, "testing");
	//DbGetOrCreateMeeting($sqlInstance, "testing2");
	unitTestDbGetResourceInfo($sqlInstance, "CONF_5555");
}
?>
