<?php

class ObserverController extends ControllerBase{

    protected function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('data', null);
            return;
        }
        $this->model->updateMe($this->user);
        $this->set('data', $this->model->getObserver());
        $this->set('status', 'OK');
    }

}

