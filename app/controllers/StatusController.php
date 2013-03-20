<?php

class StatusController extends ControllerBase{

    protected function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('server', null);
            $this->set('data', null);
            return;
        }
        $server = $this->request->getParam('0');
        $cacheFile = $this->ini['site']['cachedir'].'/status-'.$server;
        $useCache = $this->request->getParam('1') == 'refresh' ? false : true;
        if( $useCache == true && file_exists($cacheFile) && time() - filectime($cacheFile) < $this->ini['site']['cachelifetime'] ){
            header("Content-Type: application/json; charset=utf-8");
            print(file_get_contents($cacheFile));
            exit;
        }
        if( in_array($server, $this->ini['type']['mac']) ){
            $data = $this->model->getStatus4Mac($this->ini['server'][$server]);
        }else{
            $data = $this->model->getStatus($this->ini['server'][$server]);
        }
        $this->set('cacheFile', $cacheFile);
        if( $data == null ){
            // request again
            if( in_array($server, $this->ini['type']['mac']) ){
                $data = $this->model->getStatus4Mac($this->ini['server'][$server]);
            }else{
                $data = $this->model->getStatus($this->ini['server'][$server]);
            }
            if( $data == null ){
                $this->set('status', 'ERROR');
                $this->set('server', $server);
                $this->set('data', null);
                return;
            }
        }
        $this->set('status', 'OK');
        $this->set('server', $server);
        $this->set('data', $data);
    }    

}

