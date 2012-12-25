<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");
require_once("utility.php");
require_once("multilib.php");

if( isset($_GET["s"]) ){
        //system($servs[$_GET["s"]]." ' iostat 1 2'");
	if(  $iostat_avail[$_GET["s"]] ){
        	exec($servs[$_GET["s"]]." ' iostat 1 2'", $out);
	}else{
		$out = null;
	}
	print( json_encode(array( "server" => $_GET["s"], $_GET["s"] => decodeComm($out))) );
}else{
	write_log($_SERVER["REQUEST_URI"]);
        $url_list = array();
        foreach( $servs as $name => $comm ){
                $url_list[] = $url."/sys/iostat.php?s=".$name;
        }
        $res = fetch_multi_url($url_list);
        $index = 0;
        $clusterArray = "";
        foreach( $servs as $name => $comm ){
                $clusterArray[$name] = decodeComm($res[$index]);
                ++$index;
        }
        print( json_encode( $clusterArray ) );
}


function decodeComm( $commOut ){
	//$lines = explode("\n",$commOut);
	$lines = $commOut;
	if( !is_Array( $lines ) )
		return;
	$status;
        $flag = 0;
	$cnt = 0;
        for( $j=1; $j<count($lines)-2; $j++ ){
                $arr = preg_split( "/[\s,]+/", $lines[$j]);
		if( $arr[0] == "Device:" || $flag == 2 )
			++$flag;
                if( $flag >= 3 && count($arr) >= 6 ){
                        $retArray["device"] = $arr[0];
                        $retArray["tps"] = $arr[1];
                        $retArray["blkr_s"] = $arr[2];
                        $retArray["blkw_s"] = $arr[3];
                        $retArray["blkr"] = $arr[4];
                        $retArray["blkw"] = $arr[5];
                        $status[$cnt] = $retArray;
                        ++$cnt;
                }
        }
        return $status;
}	


