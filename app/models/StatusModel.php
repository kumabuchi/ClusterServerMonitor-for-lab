<?php

class StatusModel extends ModelBase{

    // TABLE NAME
    protected $name = null;

    public function getStatus($serverComm){
        if( $serverComm != null){
            //exec($serverComm." ' uptime | sed -e 's/min,//'; vmstat 1 2;' 2>/dev/null",$out);
            exec($serverComm." 'uptime; vmstat 1 2;' 2>/dev/null",$out);
        }
        return $this->decodeCommand($out);
    }

    public function getStatus4Mac($serverComm){
        if( $serverComm != null){
            //exec($serverComm." 'uptime; top -l 1| head -n 10' 2>/dev/null", $out);
            exec($serverComm." 'uptime; top -l 2' 2>/dev/null", $out);
        }
        return $this->decodeCommand4Mac($out);
    }

    private function decodeCommand($commOut){
        $lines = $commOut;
        if( count( $lines ) < 4 ){
            return null;
        }
        $retArray = array();
        $arr = preg_split( "/[\s,]+/", $lines[0]);
        $index = 0;
        for($i=0; $i<count($arr); $i++ ){
            if( $arr[$i] == "days" || $arr[$i] == "day" ){
                $retArray["days"]  = $arr[$i-1];
            }
            if( $arr[$i] == "users" || $arr[$i] == "user" ){
                $retArray["users"] = $arr[$i-1];
            }
            if( $arr[$i] == "average:" ){
                $index = $i+1;
                break;
            }
        }
        if( !isset($retArray["days"]) ){
            $retArray["days"]  = "0";
        }
        if( !isset($retArray["users"]) ){
            $retArray["users"] = "0";
        }
        $retArray["lavg1"] = $arr[$index];
        $retArray["lavg5"] = $arr[$index+1];
        $retArray["lavg15"]= $arr[$index+2];
        $arr = preg_split( "/[\s,]+/", $lines[4]);
        $prefix = 0;
        if( $arr[0] != "" )
            $prefix = -1;
        $retArray["proc_r"] = $arr[1+$prefix];
        $retArray["proc_b"] = $arr[2+$prefix];
        $retArray["mem_sw"] = $arr[3+$prefix];
        $retArray["mem_fr"] = $arr[4+$prefix];
        $retArray["mem_bf"] = $arr[5+$prefix];
        $retArray["mem_ch"] = $arr[6+$prefix];
        $retArray["swap_i"] = $arr[7+$prefix];
        $retArray["swap_o"] = $arr[8+$prefix];
        $retArray["io_bi"]  = $arr[9+$prefix];
        $retArray["io_bo"]  = $arr[10+$prefix];
        $retArray["sys_in"] = $arr[11+$prefix];
        $retArray["sys_cs"] = $arr[12+$prefix];
        $retArray["cpu_us"] = $arr[13+$prefix];
        $retArray["cpu_sy"] = $arr[14+$prefix];
        $retArray["cpu_id"] = $arr[15+$prefix];
        $retArray["cpu_wt"] = $arr[16+$prefix];
        return $retArray;
    }


    private function decodeCommand4Mac($commOut){
        if( count( $commOut ) < 4 ){
            return null;
        }
        $flag = false;
        $cnt = 0;
        $margeOut = $commOut[0] . " ";
        for($k=5; $k<count($commOut); $k++){
            if( preg_match("/^Processes:/", $commOut[$k]) ){
                $flag = true;
            }
            if( $flag ){
                $margeOut .= $commOut[$k] . " ";
                ++$cnt;
            }
            if( $cnt > 10 ){
                break;
            }
        }
        $arr = preg_split("/[\s,]+/", $margeOut);

        $retArray = array();
        for($i=0; $i<count($arr); $i++ ){

            // templete
            //$retArray[""] = $arr[$i];

            if( $arr[$i] == "days" || $arr[$i] == "day" ){
                $retArray["days"]  = $arr[$i-1];
            }
            else if( !isset($retArray["users"]) && ($arr[$i] == "users" || $arr[$i] == "user") ){
                $retArray["users"] = $arr[$i-1];
            }
            else if( $arr[$i] == "averages:" ){
                $retArray["lavg1"] = $arr[$i+1];
                $retArray["lavg5"] = $arr[$i+2];
                $retArray["lavg15"] = $arr[$i+3];
            }
            else if( $arr[$i] == "running" ){
                $retArray["proc_r"] = $arr[$i-1];
                $retArray["proc_b"] = "0";
            }
            else if( $arr[$i] == "usage:" ){
                $retArray["cpu_us"] = str_replace("%", "", $arr[$i+1]);
                $retArray["cpu_sy"] = str_replace("%", "", $arr[$i+3]);
                $retArray["cpu_id"] = str_replace("%", "", $arr[$i+5]);
                $retArray["cpu_wt"] = "0";
            }
            else if( $arr[$i] == "PhysMem:" ){
                $retArray["mem_bf"] = str_replace("G", "000", str_replace("M", "", $arr[$i+1]));
                $retArray["mem_sw"] = str_replace("G", "000", str_replace("M", "", $arr[$i+3]));
                $retArray["mem_ch"] = str_replace("G", "000", str_replace("M", "", $arr[$i+5]));
                $retArray["mem_fr"] = str_replace("G", "000", str_replace("M", "", $arr[$i+9]));
            }
            else if( $arr[$i] == "pageins" ){
                preg_match("/\((\d+)\)/", $arr[$i-1], $swap_i );
                preg_match("/\((\d+)\)/", $arr[$i+1], $swap_o );
                $retArray["swap_i"] = count($swap_i) > 1 ? $swap_i[1] : "0";
                $retArray["swap_o"] = count($swap_o) > 1 ? $swap_o[1] : "0";
            }
        }
        $retArray["io_bi"]  = "0";
        $retArray["io_bo"]  = "0";
        $retArray["sys_in"] = "0";
        $retArray["sys_cs"] = "0";
        return $retArray;
    }

}

