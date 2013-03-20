<?php

class TopModel extends ModelBase{

    // TABLE NAME
    protected $name = null;

    public function getTop($serverComm, $filter){
        if( $serverComm != null ){
            exec( $serverComm . " \"top -b -n1 | sed '1,6d'\" 2>/dev/null", $out );
        }
        return $this->decodeCommand($out, $filter);
    }

    public function getTop4Mac($serverComm, $filter){
        if( $serverComm != null ){
            exec( $serverComm . " 'ps aux | grep -v root' 2>/dev/null", $out );
        }
        return $this->decodeCommand4Mac($out, $filter);
    }

    private function decodeCommand($commOut, $filter){
        if( !is_Array( $commOut ) ){
            return false;
        }
        $tops;
        $cnt = 0;
        for( $j=1; $j<count($commOut); $j++ ){
            $arr = preg_split( "/[\s,]+/", $commOut[$j]);
            $prefix = $arr[0] == "" ? 1 : 0;

            if( $arr[0+$prefix] != null && !(in_array($arr[1+$prefix], $filter) || in_array($arr[11+$prefix], $filter) ) ){
                $retArray["PID"] = $arr[0+$prefix];
                $retArray["USER"] = $arr[1+$prefix];
                $retArray["PR"] = $arr[2+$prefix];
                $retArray["NI"] = $arr[3+$prefix];
                $retArray["VIRT"] = $arr[4+$prefix];
                $retArray["RES"] = $arr[5+$prefix];
                $retArray["SHR"] = $arr[6+$prefix];
                $retArray["S"] = $arr[7+$prefix];
                $retArray["%CPU"] = $arr[8+$prefix];
                $retArray["%MEM"] = $arr[9+$prefix];
                $retArray["TIME"] = $arr[10+$prefix];
                $retArray["COMMAND"] = $arr[11+$prefix];
                $tops[$cnt] = $retArray;
                ++$cnt;
            }
        }
        return $tops;
    }

    private function decodeCommand4Mac($commOut, $filter){
        if( !is_Array( $commOut ) ){
            return false;
        }
        $tops;
        $cnt = 0;
        for( $j=1; $j<count($commOut); $j++ ){
            $arr = preg_split( "/[\s,]+/", $commOut[$j]);
            $prefix = $arr[0] == "" ? 1 : 0;

            if( $arr[0+$prefix] != null && !(in_array($arr[0+$prefix], $filter) || in_array($arr[10+$prefix], $filter) ) ){
                if( count(explode("/", $arr[10+$prefix])) == 1 ){
                    $retArray["PID"] = $arr[1+$prefix];
                    $retArray["USER"] = $arr[0+$prefix];
                    $retArray["PR"] = "--";
                    $retArray["NI"] = "--";
                    $retArray["VIRT"] = $arr[4+$prefix];
                    $retArray["RES"] = $arr[5+$prefix];
                    $retArray["SHR"] = "--";
                    $retArray["S"] = $arr[7+$prefix];
                    $retArray["%CPU"] = $arr[2+$prefix];
                    $retArray["%MEM"] = $arr[3+$prefix];
                    $retArray["TIME"] = $arr[9+$prefix];
                    $retArray["COMMAND"] = $arr[10+$prefix];
                    $tops[$cnt] = $retArray;
                    ++$cnt;
                }
            }
        }
        return $tops;
    }

}

