<?php

$title = "CLUSTER SERVER MONITOR";
$url   = "THIS FILE'S DIRECTORY URL";

// ###サーバ情報を4つまで記述### NAME => SSH_COMMAND
$servs = array(
    "server1"  => "ssh -o \"StrictHostKeyChecking no\" -i your_dsa_key username@host1",
    "server2"  => "ssh -o \"StrictHostKeyChecking no\" -i your_dsa_key username@host2"
);

/*
// ***全ページ共通の処理***
// アクセス制限
$ipAddress = $_SERVER["REMOTE_ADDR"];
if( !preg_match("/^###.##.###./", $ipAddress) ){
	print("<html><body><h1>403 Access Forbidden!</h1></body></html>");
	exit(-1);
}
 */
