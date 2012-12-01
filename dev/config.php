<?php

$title = "MONITOR DEV";
$url   = "http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/monitor/dev";
$logging = true;
$logdir= "/mnt/monitor_log/dev";

// ###サーバ情報を4つまで記述### NAME => SSH_COMMAND
$servs = array(
		"foster"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@foster.cs.scitec.kobe-u.ac.jp",
		"rubicon"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@rubicon.cs.scitec.kobe-u.ac.jp"
	      );

// ###TOPコマンドで表示しない項目のユーザ,コマンド
$filter = array(
		"root",
		"top",
		"sshd",
		"ssh",
		"bash",
		"sftp-server",
		"sshfs",
		"apache"
	       );




// ###functions###

// ***アクセスログ取得***
function write_log( $mode ){
	global $logging;
	global $logdir;
	if( $logging && file_exists($logdir) ){
		$date = date("Ymd");
		$time = date("His");
		error_log($time.",".$_SERVER["REMOTE_ADDR"].",".gethostbyaddr($_SERVER["REMOTE_ADDR"]).",".$_SERVER["HTTP_USER_AGENT"].",".$mode."\n",3,$logdir."/".$date.".log");
	}
}


