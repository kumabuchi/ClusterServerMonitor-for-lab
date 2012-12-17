<?php

$title   = "MONITOR DEV";
$url     = "http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/monitor/dev";
$logging = true;
$logdir  = "/mnt/monitor_log/dev";

$mail_command = "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@sydney.cs.scitec.kobe-u.ac.jp ";
	

// ###サーバ情報を4つまで記述### NAME => SSH_COMMAND
$servs = array(
		"foster"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@foster.cs.scitec.kobe-u.ac.jp",
		"rubicon"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@rubicon.cs.scitec.kobe-u.ac.jp"
	      );

// ###TOPコマンドで表示しない項目のユーザ,コマンド
$filter = array(
		"root",
		"mysql",
		"dbus",
		"ntp",
		"smmsp",
		"xfs",
		"haldaemo",
		"top",
		"sshd",
		"ssh",
		"bash",
		"sftp-server",
		"sshfs",
		"apache"
	       );


