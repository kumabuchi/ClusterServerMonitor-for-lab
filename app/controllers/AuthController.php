<?php

class AuthController extends ControllerBase{

    protected function Show(){
        if( $this->user == null ){
            $this->set('status', 'INVALID_USER');
            $this->set('data', 'null');
            return;
        }
        if( !isset($this->ini['mail'][$this->user]) ){
            $this->set('status', 'MISSING_MAIL');
            $this->set('data', array( 'name' => $this->user, "mail" => null) );
            return;
        }
        $data = array( 'name' => $this->user, 'mail' => $this->ini['mail'][$this->user] );
        $this->set('status', 'OK');
        $this->set('data', $data);
    }

}
	
