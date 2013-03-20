<?php

class TopController extends ControllerBase{

    public function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('data', 'null');
            return;
        }
        $server = $this->request->getParam('0');
        if( in_array($server, $this->ini['type']['mac']) ){
            $data = $this->model->getTop4Mac($this->ini['server'][$server], explode(",", $this->ini['filter']['filter']));
        }else{
            $data = $this->model->getTop($this->ini['server'][$server], explode(",", $this->ini['filter']['filter']));
        }
        if( $data === false ){
            $this->set('status', 'ERROR');
            $this->set('data', null);
            return;
        }
        $this->set('status', 'OK');
        $this->set('data', $data);
    }

}

