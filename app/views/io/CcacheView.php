<?php
header("Content-Type: application/json; charset=utf-8");
if( $this->get('cacheFile') != null ){
    file_put_contents( $this->get('cacheFile'), json_encode(array("status" => $this->get('status'), 'server' => $this->get('server'), 'data' => $this->get('data'))), LOCK_EX );
}
print( json_encode(array("status" => $this->get('status'))) );

