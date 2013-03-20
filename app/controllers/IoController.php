<?php

class IoController extends ControllerBase{

    public function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('server', null);
            $this->set('data', null);
            return;
        }
        $server = $this->request->getParam('0');
        $useCache = $this->request->getParam('1') == 'refresh' ? false : true;
        $cacheFile = $this->ini['site']['cachedir'].'/io-'.$server;
        if( $useCache == true && file_exists($cacheFile) && time() - filectime($cacheFile) < $this->ini['site']['cachelifetime'] ){
            header("Content-Type: application/json; charset=utf-8");
            print(file_get_contents($cacheFile));
            exit;
        }
        if( in_array($server, $this->ini['type']['mac']) ){
            $data = null;
        }else{
            $data = $this->model->getIo($this->ini['server'][$server]);
        }
        if( $data == null ){
            $this->set('status', 'IOSTAT_IS_NOT_AVAILABLE');
        }else{
            $this->set('status', 'OK');
        }
        $this->set('cacheFile', $cacheFile);
        $this->set('server', $server);
        $this->set('data', $data);
    }

    public function ccache(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            exit;
        }
        $server = $this->request->getParam('0');
        $data = $this->model->getIo($this->ini['server'][$server]);
        if( $data == null ){
            $this->set('status', 'IOSTAT_IS_NOT_AVAILABLE');
        }else{
            $this->set('status', 'OK');
        }
        $this->set('cacheFile', $this->ini['site']['cachedir'].'/io-'.$server);
        $this->set('server', $server);
        $this->set('data', $data);
    }
}

