<?php

require_once("../config.php");
require_once("../ipmap.php");

$alertfile = "alerts.rc";
if( !file_exists($logdir."/".$alertfile) ){
	if( !file_put_contents($logdir."/".$alertfile, json_encode(Array())) ){
		exit(); //error : can not create file
	}
	system("chmod 666 ".$logdir."/".$alertfile);
}

if( ! isset( $ipmap[$_SERVER['REMOTE_ADDR']] ) ){
	print(json_encode(Array( "identify" => "false", "alert" => "fail" , "delete" => "fail", "count"=>0 )));
	exit(); 
}
$user_name = $ipmap[$_SERVER['REMOTE_ADDR']];

if( isset($_GET["identify"]) && $_GET["identify"] == "true" ){
	if( !isset( $maillist[$user_name] ) ){
		print(json_encode(Array( "identify" => "false" )));
		exit();
	}else{
		print(json_encode(Array( "identify" => "true", "user_name" => $user_name, "mail_addr" => $maillist[$user_name])));
		exit();
	}
}

if(!(isset($_GET["comm"]) && isset($_GET["user"]) && isset($_GET["server"]) && isset($_GET['pid']) && isset($_GET["mailto"]))){
	$alerts = get_alerts();
	$json = Array();
	foreach($alerts as $key => $val ){
		$keys = explode(":",$key);
		if( $keys[0] == $user_name ){
			$json[$key] = $val;
		}
	}
	print(json_encode(Array( "count" => count($json), "alert" => $json)));
	exit();
}else{ 
	$pid = $_GET['pid'];
	$comm = $_GET['comm'];
	$comm_user = $_GET['user'];
	$server = $_GET['server'];
	$mailto = $_GET["mailto"];
	if( !isset($servs[$server]) ){
		print(json_encode(Array( "alert" => "fail")));
		exit();
	}
	$alerts = get_alerts();
	$key = $user_name.":".$server.":".$pid;
	$val = $comm.":".$comm_user.":".$mailto;
	if( isset($alerts[$key]) ){
		if( isset($_GET['del']) && $_GET['del'] === "true" ){
			unset($alerts[$key]);
			file_put_contents($logdir."/".$alertfile, json_encode($alerts));
			print(json_encode(Array( "delete" => "success") ));
			exit();
		}
		print(json_encode(Array( "alert" => "duplicated" ) ));
		exit();
	}else{
		if( isset($_GET['del']) && $_GET['del'] === "true" ){
			print(json_encode(Array ("delete" => "fail")));
			exit();
		}
		$alerts[$key] = $val;
	}
	file_put_contents($logdir."/".$alertfile, json_encode($alerts));
	print(json_encode(Array( "set" => "success" )));
	exit();
	
}

function get_alerts(){
	global $logdir;
	global $alertfile;
	return json_decode(file_get_contents($logdir."/".$alertfile),true);
}

