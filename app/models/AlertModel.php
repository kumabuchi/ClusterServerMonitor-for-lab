<?php

class AlertModel extends ModelBase{

	// TABLE NAME
	protected $name = 'sms_alert';

    public function add($params){
        $sql = sprintf('SELECT * FROM %s WHERE server = :server and pid = :pid and user = :user and mail = :mail', $this->name);
        $bind = array ('server' => $params['server'], 'pid' => $params['pid'], 'user' => $params['user'], 'mail' => $params['mail'] );
        if( count($this->query($sql, $bind)) != 0 ){
            return false;
        }
        $this->insert($params);
        return true;
    }

    public function del($params){
        $where = 'server = :server and pid = :pid and user = :user and mail = :mail and rand = :rand';
        $this->delete($where, $params);
        return true;
    }

    public function show($user){
        $sql = sprintf('SELECT * FROM %s WHERE user = :user', $this->name);
        return $this->query($sql, array( 'user' => $user));
    }

    public function check($server){
        $sql = sprintf('SELECT * FROM %s WHERE server = :server', $this->name);
        return $this->query($sql, array( 'server' => $server));
    }

}
	
