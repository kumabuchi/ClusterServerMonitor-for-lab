<?php

require_once("config.php");

if( isset($_GET["c"]) ){
	if( $_GET["c"] == "1" )
		$cluster = "niagara";
	elseif( $_GET["c"] == "2" )
		$cluster = "sarajevo";
	elseif( $_GET["c"] == "3" )
		$cluster = "endevour";
	elseif( $_GET["c"] == "4" )
		$cluster = "phoenix";
	else
		exit(-1);
	system("ssh -o 'StrictHostKeyChecking no' -i /home/kumabuchi/.ssh/id_dsa kumabuchi@".$cluster.".cs.scitec.kobe-u.ac.jp top -b -n1 | sed '1,6d'");
}
