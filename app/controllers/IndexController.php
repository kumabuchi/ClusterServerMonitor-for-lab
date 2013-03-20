<?php

class IndexController extends ControllerBase{

    protected function index(){
        $this->set('title', $this->ini['site']['title']);
        $this->set('url', $this->ini['site']['url']);
    }

}
	
