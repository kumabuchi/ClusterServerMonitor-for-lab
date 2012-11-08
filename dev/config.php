<?php

$title = "MONITOR DEV";
$url   = "THIS DIRECTORY's URL";

// ###サーバ情報を4つまで記述### NAME => SSH_COMMAND
$servs = array(
"servername"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa username@hostname"
);


// ***全ページ共通の処理***
// アクセスを学内に限定
$ipAddress = $_SERVER["REMOTE_ADDR"];
if( !preg_match("/^133.30.112./", $ipAddress) ){
	print("<html><body><h1><b>403</b> Access Forbidden!<br/>Access Only Laboratory.</h1></body></html>");
	exit(-1);
}
