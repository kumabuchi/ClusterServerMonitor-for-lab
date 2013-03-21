<?php

/*
 * Get HOST IP ADDRESS ( using Proxy )
 */
function getHostAddr(){
    return explode(",", $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
}

/*
 * Escape arguments
 */
function e($arg){
    return escapeshellarg($arg);
}


/*
 * Send mail using gmail
 */
function sendMail($addr, $subject, $body){
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    require_once(dirname(__FILE__)."/PHPMailer/class.phpmailer.php");

    date_default_timezone_set("Asia/Tokyo");

    $mail             = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host       = "smtp.gmail.com";
    $mail->Port       = 465;
    $mail->Username   = "***";
    $mail->Password   = "###";
    $mail->CharSet    = "iso-2022-jp";
    $mail->Encoding   = "7bit";
    $mail->From       = "***";
    $mail->FromName   = mb_encode_mimeheader(mb_convert_encoding("SERVER-MONITOR", "JIS", "utf-8"));
    $mail->AddReplyTo("***", mb_encode_mimeheader(mb_convert_encoding("SERVER-MONITOR", "JIS", "utf-8")));
    $mail->Subject    = mb_convert_encoding($subject, "JIS", "utf-8");
    $mail->Body       = mb_convert_encoding($body, "JIS", "utf-8");
    $mail->AddAddress($addr, mb_encode_mimeheader(mb_convert_encoding($addr, "JIS", "utf-8")));

    if(!$mail->Send()) {
        writeLog(1, "MAILER ERROR : ".$mail->ErrorInfo);
    } else {
        writeLog(0, "MAILER SENT : ".$addr. " subject : ".$subject);
    }
}

/*
 * Write system or error log
 * @param $mode : system(0) or error(1) , default is system 
 */
function writeLog( $mode, $body ){
    $date = date("Ymd");
    $time = date("His");
    $ini  = parse_ini_file(dirname(__FILE__).'/../config/application.conf', true);
    if( $mode == 1 ){
        error_log($date.'-'.$time.",".$body."\n", 3, $ini['site']['logdir'] . '/error.log');
        //exec("chmod 666 ".$ini['site']['logdir']."/error.log", $out);
    }else{
        error_log($date.'-'.$time.",".$body."\n", 3, $ini['site']['logdir'] . '/system.log');
        //exec("chmod 666 ".$ini['site']['logdir']."/system.log", $out);
    }
}

/*
 * Server down handling method
 * @param $server : down server name
 *        $mailinglist : alert mailinglist
 */
function downServer($server){
    $ini = parse_ini_file(dirname(__FILE__).'/../config/application.conf', true);
    $downFile = $ini['site']['downdir'].'/'.$server;
    if( !file_exists($downFile) ){
        touch($downFile);
        $subject = "[ALERT] ".$server." is DOWN!";
        $body = "This is server-monitor alert system.\nNow we have detected the server '".$server."' down or malfunction.\nPlease check the server if it is not your scheduled maintenance.";
        foreach( $ini['alert'] as $name => $addr ){
            sendMail($addr, $subject, $body);
        }
    }
}

/*
 * Server up handling method
 * @param $server : up server name
 *        $mailinglist : alert mailinglist
 */
function upServer($server){
    $ini = parse_ini_file(dirname(__FILE__).'/../config/application.conf', true);
    $downFile = $ini['site']['downdir'].'/'.$server;
    if( file_exists($downFile) ){
        exec("rm -f ".$downFile, $out);
        $subject = "[NOTICE] ".$server." is UP!";
        $body = "This is server-monitor alert system.\nNow we have detected that the server '".$server."' was recovered.";
        foreach( $ini['alert'] as $name => $addr ){
            sendMail($addr, $subject, $body);
        }
    }
}
