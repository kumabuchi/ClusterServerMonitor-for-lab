<?php

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

function check_alert( $server_name, $top_output ){
	global $logging;
	global $logdir;
	$alert_file = $logdir."/alerts.rc";
	if( $logging && file_exists($alert_file) ){
		$top_json = json_decode($top_output,true);
		$alerts_json = json_decode(file_get_contents($alert_file),true);
		$new_alerts = Array();
		foreach( $alerts_json as $key => $val ){
			$keys = explode(":",$key);
			$vals = explode(":",$val);
			$match_flag = false;
			if( $keys[1] == $server_name ){
				$send_flag = true;
				for( $i=0; $i<count($top_json); $i++ ){
					//print($top_json[$i]["PID"] ." and ". $keys[2] ."\n");//debug
					if( $top_json[$i]["PID"] == $keys[2] ){
						$send_flag = false;
						$match_flag = true;
						break;
					}
				}
				if( $send_flag == true ){
					send_alert_mail($vals[2],$keys[2],$keys[1],$vals[0],$vals[1]);
				}
			}else{
				$match_flag = true;
			}
			if( $match_flag == true ){
				$new_alerts[$key] = $val;
			}
		}
		file_put_contents($alert_file, json_encode($new_alerts));
	}			
}

function send_alert_mail( $mailto, $pid, $server, $comm, $commuser ){
	global $mail_command;
	$subject = "'[PID:".e($pid)."] Program Finished'";
	$contents = "'Hi, this is server-monitor Mail Alert Center.\nThe program that you registered finished.\n\n[PID]\t\t".e($pid)."\n[SERVER]\t".e($server)."\n[COMMAND]\t".e($comm)."\n[USER]\t\t".e($commuser)."\nPlease check the program status and results.\n\nThis mail is send only.\nIf you want to contact me, please send mail to kumabuchi@ai.cs.kobe-u.ac.jp'";
	//print($mail_command.' "echo -e ' .$contents. ' | mail -s ' .$subject. ' ' .$mailto. '"');
	system($mail_command.' "echo -e ' .$contents. ' | mail -s ' .$subject. ' ' .$mailto. '"');
}

function e($arg){
	return escapeshellarg($arg);
}
