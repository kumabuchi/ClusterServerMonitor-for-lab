<?php

class DfModel extends ModelBase{

    // TABLE NAME
    protected $name = null;

    public function getDf($serverComm){
        if( $serverComm != null ){
            exec($serverComm." 'df -Ph' 2>/dev/null", $out);
        }
        return $this->decodeCommand($out);
    }

    private function decodeCommand($commOut){
        $lines = $commOut;
        if( count( $lines ) < 3 )
            return null;
        for( $i=1; $i<count($lines); $i++ ){
            $arr = preg_split( "/[\s,]+/", $lines[$i]);
            if( count($arr) >= 5 ){
                $offset = $arr[0] == 'map' ? 1 : 0;
                if( $offset == 1 ){ 
                    $tmpArray["filesystem"] = $arr[0] . "-" . $arr[1];
                }else{
                    $tmpArray["filesystem"] = $arr[0];
                }
                $tmpArray["size"] = $arr[1+$offset];
                $tmpArray["used"] = $arr[2+$offset];
                $tmpArray["avail"]= $arr[3+$offset];
                $tmpArray["use%"] = $arr[4+$offset];
                $tmpArray["mount"]= $arr[5+$offset];
                if( count($arr) > 5 ){
                    for( $l = 6; $l < count($arr); $l++){
                        $tmpArray["mount"] .= $arr[$l];
                    }
                }
                $retArray[] = $tmpArray;
            }
        }
        return $retArray;
    }

}

