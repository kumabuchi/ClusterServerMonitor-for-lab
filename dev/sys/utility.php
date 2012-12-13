<?php
require_once("../config.php");

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

