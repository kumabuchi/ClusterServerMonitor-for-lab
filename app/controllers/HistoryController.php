<?php

class HistoryController extends ControllerBase{

    protected function add(){
        if( $this->user != 'foster' ){
            $this->set('status', 'INVALID_USER');
            return;
        }
        $server = $this->request->getParam('0');
        if( !isset($this->ini['server'][$server]) ){
            $this->set('status', 'ERROR');
        }
        $status = json_decode(file_get_contents($this->ini['site']['url'].'/status/show/'.$server.'/refresh'), true);
        if( $status['status'] == 'OK' ){
            $status['data']['server'] = $status['server'];
            $this->model->add($status['data']);
            $this->set('status', 'OK');
        }else{
            $this->model->addError($server);
            $this->set('status', 'ERROR');
        }

    }

    protected function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('data', null);
            return;
        }
        $datetime = $this->request->getParam('0');
        $data = $this->model->show($datetime);
        if( $data == null ){
            $this->set('status', 'ERROR');
            $this->set('data', null);
            return;    
        }
        $this->set('status', 'OK');
        $this->set('data', $data);
    }

}

