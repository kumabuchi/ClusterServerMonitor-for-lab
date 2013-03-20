<?php

class JsController extends ControllerBase{

    protected function monitor(){
        $group = $this->request->getParam('0');
        if( $this->user == null || !isset($this->ini['group'][$group]) ){
            header("HTTP/1.0 404 Not Found");
            exit;
        }
        $this->set('servs', explode(',', $this->ini['group'][$group]));
        $this->set('url', $this->ini['site']['url']); 
    }

}

