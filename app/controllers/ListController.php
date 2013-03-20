<?php

class ListController extends ControllerBase{

    protected function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('data', null);
            return;
        }
        $servers = array();
        foreach( $this->ini['server'] as $server => $command ){
            $servers[] = $server; 
        }
        $this->set('status', 'OK');
        $this->set('data', $servers);
    }

}
	
