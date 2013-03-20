<?php

class DocsController extends ControllerBase{

    protected function show(){
        if( $this->user == null ){
            header("Location: " .$this->ini['site']['url']. "/signup/form");
            exit;
        }
        $this->set('url', $this->ini['site']['url']);
    }

}

