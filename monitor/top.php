<?php

// アクセス制限
$ipAddress = $_SERVER["REMOTE_ADDR"];
if( !preg_match("/^###/", $ipAddress) ){
	exit(-1);
}

$dsa_key = "###";

if( isset($_GET["c"]) ){
	if( $_GET["c"] == "1" )
		$cluster = "host1";
	elseif( $_GET["c"] == "2" )
		$cluster = "host2";
	elseif( $_GET["c"] == "3" )
		$cluster = "host3";
	elseif( $_GET["c"] == "4" )
		$cluster = "host4";
	else
        exit(-1);
	system("ssh -o 'StrictHostKeyChecking no' -i ".$dsa_key." ".$cluster." top -b -n1 | sed '1,6d'");
}
