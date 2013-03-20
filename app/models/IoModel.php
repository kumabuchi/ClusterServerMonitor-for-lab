<?php

class IoModel extends ModelBase{

    // TABLE NAME
    protected $name = null;

    public function getIo($serverComm){
        if( $serverComm != null ){
            exec($serverComm." ' iostat 1 2' 2>/dev/null", $out);
        }
        return $this->decodeCommand($out);
    }

    private function decodeCommand($commOut){
        $lines = $commOut;
        if( count( $lines ) < 3 )
            return null;
        $status = null;
        $flag = 0;
        $cnt = 0;
        for( $j=1; $j<count($lines)-1; $j++ ){
            $arr = preg_split( "/[\s,]+/", $lines[$j]);
            if( $arr[0] == "Device:" || $flag == 2 )
                ++$flag;
            if( $flag >= 3 && count($arr) >= 6 ){
                $retArray["device"] = $arr[0];
                $retArray["tps"] = $arr[1];
                $retArray["blkr_s"] = $arr[2];
                $retArray["blkw_s"] = $arr[3];
                $retArray["blkr"] = $arr[4];
                $retArray["blkw"] = $arr[5];
                $status[$cnt] = $retArray;
                ++$cnt;
            }
        }
        return $status;
    }

}

