<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");

if( isset($_GET["c"]) && isset($servs[$_GET["c"]])  ){
	exec("ssh -o 'StrictHostKeyChecking no' -i /home/kumabuchi/.ssh/id_dsa kumabuchi@".escapeshellarg($_GET["c"]).".cs.scitec.kobe-u.ac.jp top -b -n1 | sed '1,6d'", $out );
	print( json_encode(decodeComm($out)) );
}

function decodeComm( $commOut ){
	if( !is_Array( $commOut ) ){
		exit();
	}
	$tops;
	$cnt = 0;
	for( $j=1; $j<count($commOut); $j++ ){
		$arr = preg_split( "/[\s,]+/", $commOut[$j]);
		$prefix = $arr[0] == "" ? 1 : 0;

		if( $arr[1+$prefix] != "root" && $arr[11+$prefix] != "top" && $arr[11+$prefix] != "sshd" ){
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



