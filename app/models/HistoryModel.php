<?php

class HistoryModel extends ModelBase{

	// TABLE NAME
	protected $name = 'sms_history';
    
    public function add($params){
        $params['datetime'] = date('YmdHis');
        $this->insert($params);
    }

    public function addError($server){
        $params = array();
        $params['datetime'] = date('YmdHis');
        $params['server'] = $server;
        $params['lavg1']  = -1;
        $params['lavg5']  = -1;
        $params['lavg15'] = -1;
        $params['proc_r'] = -1;
        $params['proc_b'] = -1;
        $params['mem_sw'] = -1;
        $params['mem_fr'] = -1;
        $params['mem_bf'] = -1;
        $params['mem_ch'] = -1;
        $params['swap_i'] = -1;
        $params['swap_o'] = -1;
        $params['io_bi']  = -1;
        $params['io_bo']  = -1;
        $params['sys_in'] = -1;
        $params['sys_cs'] = -1;
        $params['cpu_us'] = -1;
        $params['cpu_sy'] = -1;
        $params['cpu_id'] = -1;
        $params['cpu_wt'] = -1;
        $this->insert($params);
    }

    public function show($datetime){
        $sql = sprintf('SELECT * FROM %s WHERE datetime BETWEEN \'%s000000\' AND \'%s235959\'', $this->name, $datetime, $datetime);
        return $this->query($sql);
    }

}
	
