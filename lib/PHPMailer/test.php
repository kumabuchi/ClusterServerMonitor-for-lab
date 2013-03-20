<?php
mb_language("Japanese");
mb_internal_encoding("UTF-8");

include("class.phpmailer.php");

date_default_timezone_set("Asia/Tokyo");

$mail             = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "ssl";
$mail->Host       = "smtp.gmail.com";
$mail->Port       = 465;
$mail->Username   = "server.monitor.cs24@gmail.com";
$mail->Password   = "123456cs24";
$mail->CharSet    = "iso-2022-jp";
$mail->Encoding   = "7bit";
$mail->From       = "server.monitor.cs24@gmail.com";
$mail->FromName   = mb_encode_mimeheader(mb_convert_encoding("SERVER-MONITOR", "JIS", "utf-8"));
$mail->AddReplyTo("server.monitor.cs24@gmail.com", mb_encode_mimeheader(mb_convert_encoding("SERVER-MONITOR", "JIS", "utf-8")));
$mail->Subject    = mb_convert_encoding("TEST SUBJECT", "JIS", "utf-8");
$mail->Body       = mb_convert_encoding("TEST BODY", "JIS", "utf-8");
$mail->AddAddress("kumabuchi@ai.cs.kobe-u.ac.jp", mb_encode_mimeheader(mb_convert_encoding("kuma", "JIS", "utf-8")));

if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo . "\n";
} else {
      echo "Message has been sent" . "\n";
}

?>
