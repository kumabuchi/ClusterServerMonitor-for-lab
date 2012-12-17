<?php
require_once(dirname(__FILE__)."/../config.php");
require_once(dirname(__FILE__)."/../sys/utility.php");

// cron処理を実行
if( !isset($_SERVER["REMOTE_ADDR"]) && file_exists($logdir) ){

	$date = date("Ymd");
	$time = date("His");

	error_log($time."||".file_get_contents($url."/sys/infos.php")."\n", 3,$logdir."/".$date.".infos");
	error_log($time."||".file_get_contents($url."/sys/iostat.php")."\n",3,$logdir."/".$date.".iostat");

	foreach( $servs as $name => $ssh ){
		check_alert($name,file_get_contents($url."/sys/top.php?c=".$name));
	}

}

