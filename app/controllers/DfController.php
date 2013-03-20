<?php

class DfController extends ControllerBase{

    public function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('server', null);
            $this->set('data', null);
            return;
        }
        $server = $this->request->getParam('0');
        $cacheFile = $this->ini['site']['cachedir'].'/df-'.$server;
        if( file_exists($cacheFile) ){
            header("Content-Type: application/json; charset=utf-8");
            print(file_get_contents($cacheFile));
            exit;
        } 
        $data = $this->model->getDf($this->ini['server'][$server]);
        if( $data == null ){
            $this->set('status', 'ERROR');
            $this->set('server', $server);
            $this->set('data', null);
            return;
        }
        $this->set('status', 'OK');
        $this->set('server', $server);
        $this->set('data', $data);
    }

    public function ccache(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            exit;
        }
        $server = $this->request->getParam('0');
        $data = $this->model->getDf($this->ini['server'][$server]);
        if( $data === false ){
            $this->set('status', 'ERROR');
            return;
        }
        $cacheFile = $this->ini['site']['cachedir'].'/df-'.$server;
        $this->set('cacheFile', $cacheFile);
        $this->set('status', 'OK');
        $this->set('server', $server);
        $this->set('data', $data);
    }

}

