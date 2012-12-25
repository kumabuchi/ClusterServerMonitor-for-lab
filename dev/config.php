<?php
/*********************************
 *** SERVER MONITORING SYSTEM  ***
 ***    congiguration file     ***
 *********************************/

/*
 * PAGE TITLE 
 */
$title   = "MONITOR DEV";

/*
 * SITE URL
 */
$url     = "http://www.ai.cs.kobe-u.ac.jp/~kumabuchi/monitor/dev";

/*
 * LOG SETTINGS
 * $logging : ログを取得する(true), ログを取得しない(false) 
 * (注 このパラメータはログの取得以外に，メール送信機能及びオブザーバー機能に影響します。)
 * $logdir  : ログ保存用ディレクトリへのパス 
 * (注 このパラメータはログの保存以外に，メール送信機能及びオブザーバー，ヒストリー機能に影響します。)
 */
$logging = true;
$logdir  = "/mnt/monitor_log/dev";

/*
 * MAIL SERVER SSH_COMMAND 
 * メールの送信を担当するサーバへのsshログインコマンド
 */
$mail_serv = "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_rsa_nopass kumabuchi@sydney.cs.scitec.kobe-u.ac.jp ";
	
/*
 * MONITORING SERVERS SSH_COMMAND
 * サーバ情報を4つまで記述 NAME => SSH_COMMAND
 */
$servs = array(
		"hermes"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa obc@hermes.cs.scitec.kobe-u.ac.jp",
		"foster"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@foster.cs.scitec.kobe-u.ac.jp",
		"rubicon"  => "ssh -o \"StrictHostKeyChecking no\" -i /home/kumabuchi/.ssh/id_dsa kumabuchi@rubicon.cs.scitec.kobe-u.ac.jp"
	      );

/*
 * IOSTAT COMMAND AVAILABLE SETTINGS
 * iostatの各サーバでの使用可否(Linuxではsysstatパッケージが必要)
 * 全てtrueでも問題無いが，httpdのエラーログに残る。
 */
$iostat_avail = array(
		"hermes" => true,
		"foster" => true,
		"rubicon"=> false
		);

/*
 * TOP COMMAND FILTERING LIST
 * TOPコマンドで表示しない項目のユーザ,コマンド
 */
$filter = array(
		"root",
		"zsh",
		"screen",
		"sh",
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

