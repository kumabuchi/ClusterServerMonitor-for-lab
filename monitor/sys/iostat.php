<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");

$clusterArray = "";
foreach( $servs as $name => $comm ){
        exec($comm." ' iostat'", $out);
        $clusterArray[$name] = decodeComm($out);
	$out = null;
}

print( json_encode( $clusterArray ) );

function decodeComm( $commOut ){
	if( !is_Array( $commOut ) )
		return;
	$status;
        $flag = 0;
	$cnt = 0;
        for( $j=1; $j<count($commOut)-1; $j++ ){
                $arr = preg_split( "/[\s,]+/", $commOut[$j]);
		if( $arr[0] == "Device:" || $flag == 1 )
			++$flag;
                if( $flag >= 2 ){
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


