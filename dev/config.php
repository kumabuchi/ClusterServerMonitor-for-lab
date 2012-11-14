<?php

$title = "MONITOR DEV";
$url   = "http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/monitor/dev";

// ###サーバ情報を4つまで記述### NAME => SSH_COMMAND
$servs = array(
"foster"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@foster.cs.scitec.kobe-u.ac.jp"
);

// ###TOPコマンドで表示しない項目のユーザ,コマンド
$filter = array(
"root",
"top",
"sshd",
"ssh",
"bash",
"sftp-server",
"sshfs"
"apache"
);

// ***全ページ共通の処理***
// アクセスを学内に限定
$ipAddress = $_SERVER["REMOTE_ADDR"];
if( !preg_match("/^133.30.112./", $ipAddress) ){
	print("<html><body><h1>403 Access Forbidden!</h1></body></html>");
	exit(-1);
}
