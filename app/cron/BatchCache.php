<?php
require_once(dirname(__FILE__).'/../../lib/functions.php');

/*
 * Read config file
 */ 
$ini = parse_ini_file(dirname(__FILE__).'/../../config/application.conf', true);

/*
 * Call create StatusCache API for each servers
 */
foreach($ini['server'] as $server => $command ){
    $response = file_get_contents($ini['site']['url'].'/status/show/'.$server.'/refresh');
    $json = json_decode($response, true);
    if( $json['status'] == 'OK' ){
        upServer($server);
    }else{
        downServer($server);
        if( $server == $ini['site']['master'] ){
            exit;
        }
    }
}

/*
 * Call create DfCache API for each servers
 */
foreach($ini['server'] as $server => $command ){
    $response = file_get_contents($ini['site']['url'].'/df/ccache/'.$server);
}

/*
 * Call create IoCache API for each servers
 */
foreach($ini['server'] as $server => $command ){
    $response = file_get_contents($ini['site']['url'].'/io/ccache/'.$server);
}

/*
 * Check Mail Alert
 */
foreach($ini['server'] as $server => $command ){
    $response = file_get_contents($ini['site']['url'].'/alert/check/'.$server);
}


