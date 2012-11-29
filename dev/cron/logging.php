<?php
require(dirname(__FILE__)."/../config.php");

// cron処理を実行
if( !isset($_SERVER["REMOTE_ADDR"]) && file_exists($logdir) ){

	$date = date("Ymd");
	$time = date("His");

	error_log($time."||".file_get_contents($url."/sys/infos.php")."\n", 3,$logdir."/".$date.".infos");
	error_log($time."||".file_get_contents($url."/sys/iostat.php")."\n",3,$logdir."/".$date.".iostat");

}

