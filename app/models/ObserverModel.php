<?php

class ObserverModel extends ModelBase{

	// TABLE NAME
    protected $name = 'sms_observer';

    public function updateMe($user){
        $sql = sprintf('REPLACE INTO %s values ( :user , null )', $this->name);
        $data = array( 'user' => $user );
        $this->query($sql, $data);
    }

    public function getObserver(){
        $sql = sprintf('SELECT * FROM %s WHERE timestamp > now() - interval 10 second', $this->name);
        return $this->query($sql);
    }

}
	
