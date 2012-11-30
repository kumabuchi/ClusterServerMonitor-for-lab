<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");

write_log($_SERVER["REQUEST_URI"]);
access_control();

if( isset($_GET["c"]) && isset($servs[$_GET["c"]])  ){
	exec( $servs[$_GET['c']] . " top -b -n1 | sed '1,6d'", $out );
	print( json_encode(decodeComm($out)) );
}

function decodeComm( $commOut ){
	global $filter;
	if( !is_Array( $commOut ) ){
		exit();
	}
	$tops;
	$cnt = 0;
	for( $j=1; $j<count($commOut); $j++ ){
		$arr = preg_split( "/[\s,]+/", $commOut[$j]);
		$prefix = $arr[0] == "" ? 1 : 0;

		if( $arr[0+$prefix] != null && !(in_array($arr[1+$prefix], $filter) || in_array($arr[11+$prefix], $filter) ) ){
			$retArray["PID"] = $arr[0+$prefix];
			$retArray["USER"] = $arr[1+$prefix];
			$retArray["PR"] = $arr[2+$prefix];
			$retArray["NI"] = $arr[3+$prefix];
			$retArray["VIRT"] = $arr[4+$prefix];
			$retArray["RES"] = $arr[5+$prefix];
			$retArray["SHR"] = $arr[6+$prefix];
			$retArray["S"] = $arr[7+$prefix];
			$retArray["%CPU"] = $arr[8+$prefix];
			$retArray["%MEM"] = $arr[9+$prefix];
			$retArray["TIME"] = $arr[10+$prefix];
			$retArray["COMMAND"] = $arr[11+$prefix];
			$tops[$cnt] = $retArray;
			++$cnt;
		}
	}
	return $tops;
}


