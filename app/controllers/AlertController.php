<?php

class AlertController extends ControllerBase{

    protected function add(){
        if( $this->user == null ){
            $this->set('json', array( 'status' => 'INVALID_USER' ));
            return;
        }
        $params = array();
        $params['server']   = $this->request->getParam('0'); 
        $params['pid']      = $this->request->getParam('1'); 
        $params['mail']     = $this->request->getParam('2'); 
        $params['command']  = $this->request->getParam('3'); 
        $params['commuser'] = $this->request->getParam('4');
        $params['user']     = $this->user;
        $params['rand']     = sha1(uniqid(mt_rand(), true)); 
        if( !isset( $this->ini['mail'][$params['user']] ) || !isset( $this->ini['server'][$params['server']] ) ){
            $this->set('json', array( 'status' => 'INVALID_REQUEST' ));
            return;
        }
        if( !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $params['mail'])){
            $this->set('json', array( 'status' => 'INVALID_MAIL_ADDRESS' ));
            return;
        }
        if( $this->model->add($params) == true ){
            $this->set('json', array( 'status' => 'OK' ));
        }else{
            $this->set('json', array( 'status' => 'SQL_ERROR' ));
        }
    }

    protected function del(){
        if( $this->user == null ){
            $this->set('json', array( 'status' => 'INVALID_USER' ));
            return;
        }
        $params = array();
        $params['server']   = $this->request->getParam('0'); 
        $params['pid']      = $this->request->getParam('1'); 
        $params['mail']     = $this->request->getParam('2'); 
        $params['rand']     = $this->request->getParam('3'); 
        $params['user']     = $this->user;
        if( $this->model->del($params) == true ){
            $this->set('json', array( 'status' => 'OK' ));
        }else{
            $this->set('json', array( 'status' => 'SQL_ERROR' ));
        }
    }

    protected function show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('data', null);
            return;
        }
        $this->set('status', 'OK');
        $this->set('data', $this->model->show($this->user));
    }

    protected function check(){
        if( $this->user != 'foster' ){
            $this->set('status', 'INVALID_USER');
            return;
        }
        $server = $this->request->getParam('0'); 
        $top = json_decode(file_get_contents($this->ini['site']['url'].'/top/show/'.$server), true);
        if( $top['status'] != 'OK' ){
            $this->set('status', 'ERROR');
            return;
        }
        $alert = $this->model->check($server);
        $this->checkAlert($alert, $top['data']);
        $this->set('status', 'OK');
    }

    private function checkAlert($alert, $top){
        $dels = array();
        for( $i=0; $i<count($alert); $i++){
            $flag = false;
            $rec = $alert[$i];
            for( $j=0; $j<count($top); $j++){
                if( $rec['pid'] == $top[$j]['PID'] ){
                    $flag = true;
                    break;
                } 
            }
            if( $flag === false ){
                $dels[] = $rec;
            }
        }
        for( $k=0; $k<count($dels); $k++){
            $this->sendAlert($dels[$k]);
            $params = array();
            $params['server'] = $dels[$k]['server']; 
            $params['pid']    = $dels[$k]['pid']; 
            $params['mail']   = $dels[$k]['mail']; 
            $params['rand']   = $dels[$k]['rand']; 
            $params['user']   = $dels[$k]['user'];
            if( $this->model->del($params) != true ){
                writeLog(1, 'ALERT DELETING ERROR : '.implode($params));
            }
        }
    }

    private function sendAlert($alert){
        $subject = "[PID:" . $alert['pid'] . "] Program Finished";
        $contents = "Hi, this is server-monitor Mail Alert Center.\nThe program that you registered finished.\n\n[PID]\t\t" . $alert['pid'] . "\n[SERVER]\t" . $alert['server'] . "\n[COMMAND]\t" . $alert['command'] . "\n[USER]\t\t" . $alert['commuser'] . "\n\nPlease check the program status and results.\n\n";
        sendMail($alert['mail'], $subject, $contents);
    } 

}

