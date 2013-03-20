<?php

class MonitorController extends ControllerBase{

    protected function show(){
        if( $this->user == null ){
            header("Location: " .$this->ini['site']['url']. "/signup/form");
            exit;
        }
        if( !isset($this->ini['group'][$this->controller_origin]) ){
            header("Location: " .$this->ini['site']['url']);
            exit;
        }
        $this->set('server', explode(',', $this->ini['group'][$this->controller_origin]));
        $this->set('group', $this->controller_origin);
        $this->set('title', $this->ini['site']['title']);
        $this->set('url', $this->ini['site']['url']);
    }

}

