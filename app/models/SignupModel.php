<?php

class SignupModel extends ModelBase{

	// TABLE NAME
    protected $name = 'sms_user';

    public function getPassword($user){
        $sql = sprintf('SELECT password FROM %s WHERE user = :user LIMIT 1', $this->name);
        $res = $this->query($sql, array('user'=>$user)); 
        if( count($res) == 0 ){
            return null;
        }
        return $res[0]['password'];
    }

    public function setPassword($user, $password){
        $sql = sprintf('REPLACE INTO %s values ( :user, :password )', $this->name);
        $this->query($sql, array('user' => $user, 'password' => $password) );
    }

}
	
