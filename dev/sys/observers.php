<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");
include_once("../ipmap.php");
require_once("utility.php");

$observers_file = $logdir . "/observers";
$observer = $_SERVER['REMOTE_ADDR'];
$timestamp = strtotime ("now");
$observers = Array();

if( $logging ){
	if( file_exists( $observers_file ) ){
		$observers_raw = explode("\n", file_get_contents($logdir . "/observers"));
		for( $i = 0; $i < count($observers_raw)-1; $i++ ){
			$spLine = explode(",", $observers_raw[$i]);
			if( $timestamp - $spLine[0] <= 10 ){
				$observers[$spLine[1]] = $spLine[0]+0;
			}
		}
	}
	$observers[$observer] = $timestamp;
	foreach( $observers as $obs => $time ){
		$context .= $time . "," .$obs . "\n";
	}
	file_put_contents($observers_file, $context);

}
print(json_encode(addr2name($observers)));

function addr2name($obsarray){
	global $ipmap;
	if( !isset($ipmap) ){
		return Array( "guest" => count($obsarray) );
	}
	$guest_count = 0;
	$retArray = Array();
	foreach( $obsarray as $addr => $time ){
		if( isset($ipmap[$addr]) ){
			$retArray[$ipmap[$addr]] = 1;	
		}else{
			++$guest_count;
		}
	}
	if( $guest_count !== 0 ){
		$retArray["guest"] = $guest_count;
	}
	return $retArray;
}
