<?php
header("Content-Type: application/json; charset=utf-8");
print( json_encode(array("status" => $this->get('status'), "server" => $this->get('server'), 'data' => $this->get('data'))) );

