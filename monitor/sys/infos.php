<?php
header("Content-Type: application/json; charset=utf-8");

require_once("../config.php");

$clusterArray = "";
foreach( $servs as $name => $comm ){
	exec($comm." 'w | head -n 1 | sed -e 's/min,//'; vmstat'", $out);
	$clusterArray[$name] = decodeComm($out);
	$out = null;
}

print( json_encode( $clusterArray ) );

function decodeComm( $commOut ){
	if( !is_Array( $commOut ) )
		return;
	$arr = preg_split( "/[\s,]+/", $commOut[0]);
	$index = 0;
	for($i=0; $i<count($arr); $i++ ){
		if( $arr[$i] == "average:" ){
			$index = $i+1;
			break;
		}
	}
	$retArray["lavg1"] = $arr[$index];
	$retArray["lavg5"] = $arr[$index+1];
	$retArray["lavg15"]= $arr[$index+2];
	$arr = preg_split( "/[\s,]+/", $commOut[3]);
	$prefix = 0;
	if( count($arr) == 16 )
		$prefix = -1;
	$retArray["proc_r"] = $arr[1+$prefix];
	$retArray["proc_b"] = $arr[2+$prefix];
	$retArray["mem_sw"] = $arr[3+$prefix];
	$retArray["mem_fr"] = $arr[4+$prefix];
	$retArray["mem_bf"] = $arr[5+$prefix];
	$retArray["mem_ch"] = $arr[6+$prefix];
	$retArray["swap_i"] = $arr[7+$prefix];
	$retArray["swap_o"] = $arr[8+$prefix];
	$retArray["io_bi"] = $arr[9+$prefix];
	$retArray["io_bo"] = $arr[10+$prefix];
	$retArray["sys_in"] = $arr[11+$prefix];
	$retArray["sys_cs"] = $arr[12+$prefix];
	$retArray["cpu_us"] = $arr[13+$prefix];
	$retArray["cpu_sy"] = $arr[14+$prefix];
	$retArray["cpu_id"] = $arr[15+$prefix];
	$retArray["cpu_wt"] = $arr[16+$prefix];
	return $retArray;
}	


