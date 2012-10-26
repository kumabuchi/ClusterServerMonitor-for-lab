<?php

//アクセス制限
$ipAddress = $_SERVER["REMOTE_ADDR"];
if( !preg_match("/^###/", $ipAddress) ){
	exit(-1);
}

$dsa_key = "###";
$host1 = "###";
$host2 = "###";
$host3 = "###";
$host4 = "###";

system("ssh -o 'StrictHostKeyChecking no' -i ". $dsa_key ." ".$host1." 'w | head -n 1 | sed -e 's/min,//'; vmstat'");
system("ssh -o 'StrictHostKeyChecking no' -i ". $dsa_key ." ".$host2."  'w | head -n 1 | sed -e 's/min,//'; vmstat'");
system("ssh -o 'StrictHostKeyChecking no' -i ". $dsa_key ." ".$host3."  'w | head -n 1 | sed -e 's/min,//'; vmstat'");
system("ssh -o 'StrictHostKeyChecking no' -i ". $dsa_key ." ".$host4." 'w | head -n 1 | sed -e 's/min,//'; vmstat'");

