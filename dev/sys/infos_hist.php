<?php

require("../config.php");

write_log($_SERVER["REQUEST_URI"]);

$time = date("His");
// check arguments
$date = isset($_GET["date"]) ? $_GET["date"] :  date("Ymd");
if( !is_numeric($date) ){
	exit();
}

$array = null;
$contents = file_get_contents($logdir."/".$date.".infos");
if( $contents != false ){
	$histories = explode("\n",$contents);
	for( $i = 0; $i<count($histories)-1; $i++){
		$data = explode("||",$histories[$i]);
		$array[$data[0]."\""] = json_decode($data[1]);
	}
}
print( json_encode($array) );

