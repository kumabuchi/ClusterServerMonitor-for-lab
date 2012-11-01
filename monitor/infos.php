<?php
header("Content-Type: application/json; charset=utf-8");

require_once("config.php");

$clusterArray = "";
exec("ssh -o \"StrictHostKeyChecking no\" -i /YOUR_SSH_KEY YOUR CLUSTER HOST 'w | head -n 1 | sed -e 's/min,//'; vmstat'", $nout);
$clusterArray["niagara"] = decodeComm($nout);
exec("ssh -o \"StrictHostKeyChecking no\" -i /YOUR_SSH_KEY YOUR CLUSTER HOST 'w | head -n 1 | sed -e 's/min,//'; vmstat'", $sout);
$clusterArray["sarajevo"] = decodeComm($sout);
exec("ssh -o \"StrictHostKeyChecking no\" -i /YOUR_SSH_KEY YOUR CLUSTER HOST 'w | head -n 1 | sed -e 's/min,//'; vmstat'", $eout);
$clusterArray["endevour"] = decodeComm($eout);
exec("ssh -o \"StrictHostKeyChecking no\" -i /YOUR_SSH_KEY YOUR CLUSTER HOST 'w | head -n 1 | sed -e 's/min,//'; vmstat'", $pout);
$clusterArray["phoenix"] = decodeComm($pout);

print( json_encode( $clusterArray ) );

function decodeComm( $commOut ){
	if( !is_Array( $commOut ) )
		return;
	$arr = preg_split( "/[\s,]+/", $commOut[0]);
	$retArray["lavg1"] = $arr[10];
	$retArray["lavg5"] = $arr[11];
	$retArray["lavg15"] = $arr[12];
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


