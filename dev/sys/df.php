<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");
require_once("utility.php");
//require_once("multilib.php");

foreach( $servs as $name => $comm ){
	$out = null;
	exec($comm." 'df -Ph;'", $out);
	$retArray[$name] = decodeComm($out);
}
print( json_encode($retArray) );
exit();

if( isset($_GET["s"]) ){
	system($servs[$_GET["s"]]." 'df -Ph;'");
}else{
	write_log($_SERVER['REQUEST_URI']);
	$url_list = array();
	foreach( $servs as $name => $comm ){
		$url_list[] = $url."/sys/df.php?s=".$name;
	}
	$res = fetch_multi_url($url_list);
	$index = 0;
	$clusterArray = "";
	foreach( $servs as $name => $comm ){
		$clusterArray[$name] = decodeComm($res[$index]);
		++$index;
	}
	print( json_encode( $clusterArray ) );
}

function decodeComm( $commOut ){
	//$lines = explode("\n",$commOut);
	$lines = $commOut;
	if( !is_Array( $lines ) )
		return;
	for( $i=1; $i<count($lines); $i++ ){
		$arr = preg_split( "/[\s,]+/", $lines[$i]);
		if( count($arr) >= 5 ){
			$tmpArray["filesystem"] = $arr[0];
			$tmpArray["size"] = $arr[1];
			$tmpArray["used"] = $arr[2];
			$tmpArray["avail"] = $arr[3];
			$tmpArray["use%"] = $arr[4];
			$tmpArray["mount"] = $arr[5];
			$retArray[] = $tmpArray;
		}
	} 
	return $retArray;
}	


