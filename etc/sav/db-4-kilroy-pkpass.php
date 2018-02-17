<?php
#require('/home1/produdn8/etc/groupon/dbcfg-2.php');
$DEBUG_DATABASE_FLAG = FALSE;//$GLOBALS['PHP_DEBUG'];
class SqlUtils2 {
	var $mysqli;
	var $host;
	var $user;
	var $pwd;
	var $database;
	public function __construct($hostname, $user, $pass, $database){
		$this->mysqli = new mysqli($hostname, $user, $pass, $database);
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			printf("parameters used = : hostname = %s, user = %s, pass= %s, and database = %s\n", $hostname, $user, $pass, $database);
			exit();
		}
	}
	public function __destructor(){
		mysqli_close($this->mysqli);
	}
}

#<?php
class SqlUtils3 {
	var $mysqli;
	var $host;
	var $user;
	var $pwd;
	var $database;
	public function __construct($hostname, $user, $pass, $database){
		$this->mysqli = new mysqli($hostname, $user, $pass, $database);
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			printf("parameters used = : hostname = %s, user = %s, pass= %s, and database = %s\n", $hostname, $user, $pass, $database);
			exit();
		}
	}
	public function __destructor(){
		mysqli_close($this->mysqli);
	}
}
class SqlUtils {
	var $mysqli;
	var $host;
	var $user;
	var $pwd;
	var $database;
	var $stores_backing_couponTable = "`stores_backing_coupon`";
	var $sqlGetNextSwiggenStr;
	var $sqlIncrementNextSwiggenStr;
	var $strSql;
	public function __construct($hostname, $user, $pass, $database){
		$this->mysqli = new mysqli($hostname, $user, $pass, $database);
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			printf("parameters used = : hostname = %s, user = %s, pass= %s, and database = %s\n", $hostname, $user, $pass, $database);
			exit();
		}
		$this->sqlIncrementNextSwiggenStr = "UPDATE " . $database . "." . $this->stores_backing_couponTable . " SET `next_swiggen_number`= `next_swiggen_number` + 1 WHERE " ;
		$this->sqlGetNextSwiggenStr = "SELECT `next_swiggen_number` FROM " . $database . "." . $this->stores_backing_couponTable . " WHERE " ;
	}
	public function __destructor(){
		mysqli_close($this->mysqli);
	}
	public function getNextSwiggen( $graffiti_Id, $store_Id, &$next_swiggen, &$OneWeekOut){
		$strSql = $this->sqlIncrementNextSwiggenStr . " `graffiti_Id` =\"" . $graffiti_Id . "\" AND store_Id = \"" . $store_Id . "\"" . ";"; 
		//if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
		//	echo("strSql = [" . $strSql . "]\n");
		$res = mysqli_query($this->mysqli, $strSql);
		if($res){
		    	//mysqli_free_result($res);
			$strSql = $this->sqlGetNextSwiggenStr . " `graffiti_Id` =\"" . $graffiti_Id . "\" AND store_Id = \"" . $store_Id . "\"" . ";"; 
			//if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
			//	echo("strSql = [" . $strSql . "]\n");
			$res = mysqli_query($this->mysqli, $strSql);
			if($res)
				while($resultsArrayFetch = mysqli_fetch_array($res, MYSQLI_ASSOC)){
					if(count($resultsArrayFetch) > 0) 
					{
						$next_swiggen = $resultsArrayFetch["next_swiggen_number"];
		    				//mysqli_free_result($res);
						//$OneWeekOut = $this->getNowPlusOneWeek();
						if($this->getNowPlusOneWeek($WeekNext) == true){
							$OneWeekOut = $WeekNext;
							return true;
						}
					}
				}
		}
		return false;
	}
	public function getNowPlusOneWeek(&$WeekNext){
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
				$WeekNext = $timeStampPlus;
				if($GLOBALS['DEBUG_DATABASE_FLAG'] == TRUE)
					echo "timeStampPlus = [" . $timeStampPlus . "]\n";
			}
		    	//mysqli_free_result($res);
			
			return true;
		} else {
			echo("mysqli_query failed "  . $res . "\n");
		}
		return false;
	}
}
?>
