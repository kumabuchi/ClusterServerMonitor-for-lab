<?php

// ***全ページ共通の処理***

// アクセスを学内に限定
$ipAddress = $_SERVER["REMOTE_ADDR"];
if( !preg_match("/^133.30.112./", $ipAddress) ){
	exit(-1);
}

// ***関数***
