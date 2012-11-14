<?php

require_once("../config.php");

$key_path = "Apache_Accessable_Directory/server_monitor_dsa";

exec("ssh-keygen -t dsa -f ".$key_path." -N '' -q", $out, $stat);

print_r($out);

// ### chenge permission or move other safe directory after ssh-keygen!!
